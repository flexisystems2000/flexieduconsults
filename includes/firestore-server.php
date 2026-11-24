<?php
declare(strict_types=1);

/**
 * Server-only Firebase/Firestore and Paystack helpers.
 * Required server environment variables:
 * FIREBASE_SERVICE_ACCOUNT_JSON = complete Firebase service-account JSON
 * PAYSTACK_SECRET_KEY = Paystack secret key (never expose to browser)
 */
final class FlexiFirestore
{
    private string $projectId;
    private string $apiKey;

    public function __construct()
    {
        // Use the same public Firebase Web API key and project already used by
        // the website's browser-side Firebase SDK configuration.
        $this->projectId = 'waec2026jamb2027';
        $this->apiKey = 'AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM';
    }

    private function http(string $method, string $url, array $headers = [], ?string $body = null): string
    {
        $context = stream_context_create(['http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'content' => $body ?? '',
            'timeout' => 20,
            'ignore_errors' => true
        ]]);
        $result = @file_get_contents($url, false, $context);
        $statusLine = $http_response_header[0] ?? '';
        if ($result === false || !preg_match('/\s(2\d\d)\s/', $statusLine)) {
            throw new RuntimeException('Remote server request failed (' . ($statusLine ?: 'no response') . ').');
        }
        return $result;
    }

    public function request(string $method, string $path, ?array $payload = null, array $query = []): array
    {
        $base = 'https://firestore.googleapis.com/v1/projects/' . rawurlencode($this->projectId)
            . '/databases/(default)/documents';
        $path = ltrim($path, '/');
        $url = str_starts_with($path, ':')
            ? $base . $path
            : $base . '/' . $path;
        $query['key'] = $this->apiKey;
        $url .= '?' . http_build_query($query);
        // No service-account token is used: Firestore applies the project's
        // normal Firebase Security Rules to requests made with this Web API key.
        $headers = ['Accept: application/json'];
        $body = null;
        if ($payload !== null) {
            $headers[] = 'Content-Type: application/json';
            $body = json_encode($payload, JSON_THROW_ON_ERROR);
        }
        $response = $this->http($method, $url, $headers, $body);
        return $response === '' ? [] : (json_decode($response, true) ?: []);
    }

    public function get(string $path): ?array
    {
        try { return $this->request('GET', $path); }
        catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), '404')) return null;
            throw $e;
        }
    }

    public function decodeFields(array $fields): array
    {
        $out = [];
        foreach ($fields as $key => $value) $out[$key] = $this->decodeValue($value);
        return $out;
    }

    private function decodeValue(array $value): mixed
    {
        foreach (['stringValue','integerValue','doubleValue','booleanValue','timestampValue','referenceValue','bytesValue'] as $key) {
            if (array_key_exists($key, $value)) return $value[$key];
        }
        if (isset($value['nullValue'])) return null;
        if (isset($value['mapValue']['fields'])) return $this->decodeFields($value['mapValue']['fields']);
        if (isset($value['arrayValue']['values'])) return array_map(fn($v) => $this->decodeValue($v), $value['arrayValue']['values']);
        return null;
    }

    public function encodeFields(array $fields): array
    {
        $out = [];
        foreach ($fields as $key => $value) $out[$key] = $this->encodeValue($value);
        return $out;
    }

    private function encodeValue(mixed $value): array
    {
        if (is_string($value)) return ['stringValue' => $value];
        if (is_int($value)) return ['integerValue' => (string)$value];
        if (is_float($value)) return ['doubleValue' => $value];
        if (is_bool($value)) return ['booleanValue' => $value];
        if ($value === null) return ['nullValue' => null];
        if (is_array($value)) {
            if (array_is_list($value)) return ['arrayValue' => ['values' => array_map(fn($v) => $this->encodeValue($v), $value)]];
            return ['mapValue' => ['fields' => $this->encodeFields($value)]];
        }
        throw new InvalidArgumentException('Unsupported Firestore field value.');
    }

    public function listCollection(string $collection, int $pageSize = 100): array
    {
        $result = $this->request('GET', $collection, null, ['pageSize' => $pageSize]);
        $docs = [];
        foreach (($result['documents'] ?? []) as $doc) {
            $name = (string)($doc['name'] ?? '');
            $id = basename($name);
            $docs[] = ['id' => $id, 'fields' => $this->decodeFields($doc['fields'] ?? []), 'updateTime' => $doc['updateTime'] ?? null];
        }
        return $docs;
    }

    public function query(string $collection, array $filters, int $limit = 10, ?string $transaction = null): array
    {
        $filterNodes = [];
        foreach ($filters as [$field, $operator, $value]) {
            $filterNodes[] = ['fieldFilter' => [
                'field' => ['fieldPath' => $field],
                'op' => $operator,
                'value' => $this->encodeValue($value)
            ]];
        }
        $where = count($filterNodes) === 1 ? $filterNodes[0] : [
            'compositeFilter' => ['op' => 'AND', 'filters' => $filterNodes]
        ];
        $structured = [
            'from' => [['collectionId' => $collection]],
            'where' => $where,
            'limit' => $limit
        ];
        $payload = ['structuredQuery' => $structured];
        if ($transaction !== null) $payload['transaction'] = $transaction;
        $response = $this->request('POST', ':runQuery', $payload);
        $docs = [];
        foreach ($response as $row) {
            if (empty($row['document'])) continue;
            $doc = $row['document'];
            $docs[] = [
                'id' => basename((string)$doc['name']),
                'path' => preg_replace('#^projects/[^/]+/databases/\(default\)/documents/#', '', (string)$doc['name']),
                'fields' => $this->decodeFields($doc['fields'] ?? []),
                'updateTime' => $doc['updateTime'] ?? null
            ];
        }
        return $docs;
    }

    public function beginTransaction(): string
    {
        $result = $this->request('POST', ':beginTransaction', ['options' => ['readWrite' => new stdClass()]]);
        return (string)($result['transaction'] ?? '');
    }

    public function commit(string $transaction, array $writes): array
    {
        return $this->request('POST', ':commit', ['transaction' => $transaction, 'writes' => $writes]);
    }

    public function documentName(string $path): string
    {
        return 'projects/' . $this->projectId . '/databases/(default)/documents/' . ltrim($path, '/');
    }

    public function encodedDocument(string $path, array $fields): array
    {
        return ['name' => $this->documentName($path), 'fields' => $this->encodeFields($fields)];
    }

    public function projectId(): string { return $this->projectId; }
}

function flexi_paystack_verify(string $reference): array
{
    $secret = getenv('PAYSTACK_SECRET_KEY') ?: '';
    if ($secret === '') throw new RuntimeException('Paystack server secret is not configured.');
    $url = 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference);
    $context = stream_context_create(['http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer {$secret}\r\nAccept: application/json\r\n",
        'timeout' => 20,
        'ignore_errors' => true
    ]]);
    $raw = @file_get_contents($url, false, $context);
    $status = $http_response_header[0] ?? '';
    $result = $raw !== false ? json_decode($raw, true) : null;
    if (!preg_match('/\s2\d\d\s/', $status) || !is_array($result) || empty($result['status']) || ($result['data']['status'] ?? '') !== 'success'
        || !hash_equals($reference, (string)($result['data']['reference'] ?? ''))) {
        throw new RuntimeException('Payment could not be verified as successful.');
    }
    return $result['data'];
}
