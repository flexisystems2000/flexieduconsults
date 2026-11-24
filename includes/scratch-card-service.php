<?php
declare(strict_types=1);
require_once __DIR__ . '/firestore-server.php';

function flexi_json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, private');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function flexi_paystack_request(string $method, string $path, ?array $payload = null): array
{
    $secret = getenv('PAYSTACK_SECRET_KEY') ?: '';
    if ($secret === '') throw new RuntimeException('Paystack server secret is not configured.');
    $headers = "Authorization: Bearer {$secret}\r\nAccept: application/json\r\n";
    $body = null;
    if ($payload !== null) {
        $headers .= "Content-Type: application/json\r\n";
        $body = json_encode($payload, JSON_THROW_ON_ERROR);
    }
    $ctx = stream_context_create(['http' => [
        'method' => $method, 'header' => $headers, 'content' => $body ?? '',
        'timeout' => 25, 'ignore_errors' => true
    ]]);
    $raw = @file_get_contents('https://api.paystack.co' . $path, false, $ctx);
    $status = $http_response_header[0] ?? '';
    $data = $raw !== false ? json_decode($raw, true) : null;
    if (!preg_match('/\s2\d\d\s/', $status) || !is_array($data) || empty($data['status'])) {
        throw new RuntimeException('Paystack could not complete the requested operation.');
    }
    return $data['data'] ?? [];
}

function flexi_start_checkout(string $flow, string $item, string $email, int $amountKobo): never
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Enter a valid email address.');
    }
    if ($amountKobo < 10000) throw new RuntimeException('Invalid checkout amount.');

    $callback = $flow === 'stock'
        ? 'https://flexieduconsult.com.ng/purchase.php'
        : 'https://flexieduconsult.com.ng/prep.php';

    $data = flexi_paystack_request('POST', '/transaction/initialize', [
        'email' => $email,
        'amount' => $amountKobo,
        'currency' => 'NGN',
        'callback_url' => $callback,
        'metadata' => [
            'flexi_flow' => $flow,
            'flexi_item' => $item,
            'flexi_amount_kobo' => $amountKobo,
            'custom_fields' => [[
                'display_name' => 'Flexi product',
                'variable_name' => 'flexi_product',
                'value' => $item
            ]]
        ]
    ]);

    $url = (string)($data['authorization_url'] ?? '');
    if (!filter_var($url, FILTER_VALIDATE_URL) || !str_starts_with($url, 'https://checkout.paystack.com/')) {
        throw new RuntimeException('Paystack did not return a valid checkout URL.');
    }
    header('Cache-Control: no-store, private');
    header('Location: ' . $url, true, 303);
    exit;
}

function flexi_verified_payment(string $reference, string $expectedFlow, string $expectedItem, int $expectedKobo): array
{
    if ($reference === '' || strlen($reference) > 120 || !preg_match('/^[A-Za-z0-9._-]+$/', $reference)) {
        throw new RuntimeException('Invalid payment reference.');
    }
    $data = flexi_paystack_verify($reference);
    $metadata = $data['metadata'] ?? [];
    if (is_string($metadata)) $metadata = json_decode($metadata, true) ?: [];
    if (($metadata['flexi_flow'] ?? '') !== $expectedFlow || ($metadata['flexi_item'] ?? '') !== $expectedItem) {
        throw new RuntimeException('This payment does not match the selected product.');
    }
    if ((int)($data['amount'] ?? 0) !== $expectedKobo || strtoupper((string)($data['currency'] ?? '')) !== 'NGN') {
        throw new RuntimeException('The verified payment amount does not match this product.');
    }
    return $data;
}

function flexi_sale_document_id(string $reference): string
{
    return 'sale_' . hash('sha256', $reference);
}

function flexi_existing_sale(FlexiFirestore $db, string $collection, string $reference): ?array
{
    $doc = $db->get($collection . '/' . flexi_sale_document_id($reference));
    return $doc ? $db->decodeFields($doc['fields'] ?? []) : null;
}

function flexi_record_payment_issue(FlexiFirestore $db, string $type, string $reference, string $email, string $reason): void
{
    $now = gmdate('Y-m-d\TH:i:s\Z');
    $id = 'issue_' . hash('sha256', $reference);
    $document = $db->encodedDocument('payment_issues/' . $id, [
        'paymentReference' => $reference,
        'email' => $email,
        'item' => $type,
        'reason' => $reason,
        'status' => 'needs_admin_review',
        'createdAt' => $now
    ]);
    $document['fields']['createdAt'] = ['timestampValue' => $now];
    try {
        $db->commit($db->beginTransaction(), [[
            'update' => $document,
            'currentDocument' => ['exists' => false]
        ]]);
    } catch (Throwable $ignored) {
        // A duplicate issue record is harmless; the original remains available to staff.
    }
}

function flexi_claim_stock_pin(FlexiFirestore $db, string $type, string $reference, string $email): array
{
    $saleId = flexi_sale_document_id($reference);
    $existing = flexi_existing_sale($db, 'scratch_card_sales', $reference);
    if ($existing) return $existing;

    // Retry optimistic transaction conflicts so simultaneous buyers can claim
    // different remaining cards instead of unnecessarily entering manual review.
    for ($attempt = 0; $attempt < 3; $attempt++) {
        $transaction = $db->beginTransaction();
        $available = $db->query('pins', [
            ['examType', 'EQUAL', $type],
            ['status', 'EQUAL', 'available']
        ], 2, $transaction);

        if (!$available) {
            flexi_record_payment_issue($db, $type, $reference, $email, 'Payment verified but inventory was empty at allocation time.');
            throw new RuntimeException('Payment verified, but the last card was just sold. Please contact support and keep your payment reference.');
        }

        $pin = $available[0];
        $fields = $pin['fields'];
        $now = gmdate('Y-m-d\TH:i:s\Z');
        $sale = [
            'paymentReference' => $reference,
            'email' => $email,
            'examType' => $type,
            'serial' => (string)($fields['serial'] ?? ''),
            'pinCode' => (string)($fields['pinCode'] ?? ''),
            'token' => (string)($fields['token'] ?? ''),
            'createdAt' => $now
        ];

        $encodedSale = $db->encodedDocument('scratch_card_sales/' . $saleId, $sale);
        $encodedSale['fields']['createdAt'] = ['timestampValue' => $now];
        $pinFields = $db->encodeFields([
            'status' => 'sold',
            'soldAt' => $now,
            'paymentRef' => $reference
        ]);
        $pinFields['soldAt'] = ['timestampValue' => $now];

        $writes = [[
            'update' => [
                'name' => $db->documentName($pin['path']),
                'fields' => $pinFields
            ],
            'updateMask' => ['fieldPaths' => ['status', 'soldAt', 'paymentRef']],
            'currentDocument' => ['updateTime' => $pin['updateTime']]
        ], [
            'update' => $encodedSale,
            'currentDocument' => ['exists' => false]
        ]];

        if (count($available) === 1) {
            $alertId = 'restock_' . substr(hash('sha256', $reference), 0, 32);
            $alert = $db->encodedDocument('restock_alerts/' . $alertId, [
                'item' => $type, 'status' => 'unread', 'timestamp' => $now
            ]);
            $alert['fields']['timestamp'] = ['timestampValue' => $now];
            $writes[] = [
                'update' => $alert,
                'currentDocument' => ['exists' => false]
            ];
        }

        try {
            $db->commit($transaction, $writes);
            return $sale;
        } catch (Throwable $exception) {
            $existing = flexi_existing_sale($db, 'scratch_card_sales', $reference);
            if ($existing) return $existing;
            if ($attempt < 2) {
                usleep(100000 * ($attempt + 1));
                continue;
            }
            flexi_record_payment_issue($db, $type, $reference, $email, 'Payment verified but inventory allocation encountered repeated transaction conflicts.');
            throw new RuntimeException('Payment verified, but card allocation needs review. Please contact support and keep your payment reference.');
        }
    }

    throw new RuntimeException('Card allocation could not be completed. Keep your payment reference and contact support.');
}

function flexi_issue_subject_pin(FlexiFirestore $db, string $subject, string $reference, string $email): array
{
    $existing = flexi_existing_sale($db, 'expo_pin_sales', $reference);
    if ($existing) return $existing;

    $pinDoc = $db->get('expo_pins/' . rawurlencode($subject));
    if (!$pinDoc) throw new RuntimeException('The access PIN for this subject is not available yet.');
    $pinFields = $db->decodeFields($pinDoc['fields'] ?? []);
    $pinCode = (string)($pinFields['pinCode'] ?? '');
    if ($pinCode === '') throw new RuntimeException('The access PIN for this subject is not configured.');

    $sale = [
        'paymentReference' => $reference,
        'email' => $email,
        'subject' => $subject,
        'pinCode' => $pinCode,
        'createdAt' => gmdate('Y-m-d\TH:i:s\Z')
    ];
    $saleDocument = $db->encodedDocument('expo_pin_sales/' . flexi_sale_document_id($reference), $sale);
    $saleDocument['fields']['createdAt'] = ['timestampValue' => $sale['createdAt']];
    try {
        $db->commit($db->beginTransaction(), [[
            'update' => $saleDocument,
            'currentDocument' => ['exists' => false]
        ]]);
    } catch (Throwable $exception) {
        $existing = flexi_existing_sale($db, 'expo_pin_sales', $reference);
        if ($existing) return $existing;
        throw new RuntimeException('The payment was verified, but PIN delivery needs support review. Keep your payment reference.');
    }
    return $sale;
}
