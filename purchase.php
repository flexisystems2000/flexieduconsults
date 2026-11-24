<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/scratch-card-service.php';
header('Cache-Control: no-store, private');
header('Referrer-Policy: no-referrer');

function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function naira(int $amount): string { return number_format($amount); }

$db = null;
$error = '';
$sale = null;
$prices = ['WAEC' => 5500, 'NECO' => 4000];
$stock = ['WAEC' => false, 'NECO' => false];

try {
    $db = new FlexiFirestore();
    $priceDoc = $db->get('store/prices');
    if ($priceDoc) {
        $stored = $db->decodeFields($priceDoc['fields'] ?? []);
        foreach (['WAEC' => 'waec', 'NECO' => 'neco'] as $type => $field) {
            $value = preg_replace('/[^0-9]/', '', (string)($stored[$field] ?? ''));
            if ($value !== '' && (int)$value > 0) $prices[$type] = (int)$value;
        }
    }
    foreach (array_keys($stock) as $type) {
        $stock[$type] = count($db->query('pins', [
            ['examType', 'EQUAL', $type], ['status', 'EQUAL', 'available']
        ], 1)) > 0;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = strtoupper(trim((string)($_POST['product'] ?? '')));
        $email = trim((string)($_POST['email'] ?? 'billing@flexitutors.com'));
        if (!isset($prices[$type])) throw new InvalidArgumentException('Choose a valid card type.');
        if (!$stock[$type]) throw new RuntimeException('This card type is currently out of stock.');
        flexi_start_checkout('stock', $type, $email, $prices[$type] * 100);
    }

    $reference = trim((string)($_GET['reference'] ?? ''));
    if ($reference !== '') {
        $verified = flexi_paystack_verify($reference);
        $metadata = $verified['metadata'] ?? [];
        if (is_string($metadata)) $metadata = json_decode($metadata, true) ?: [];
        $type = strtoupper((string)($metadata['flexi_item'] ?? ''));
        $amount = (int)($metadata['flexi_amount_kobo'] ?? 0);
        if (($metadata['flexi_flow'] ?? '') !== 'stock' || !isset($prices[$type])
            || $amount !== $prices[$type] * 100
            || (int)($verified['amount'] ?? 0) !== $amount
            || strtoupper((string)($verified['currency'] ?? '')) !== 'NGN') {
            throw new RuntimeException('The payment could not be matched to a valid scratch card order.');
        }
        $email = (string)($verified['customer']['email'] ?? '');
        $sale = flexi_claim_stock_pin($db, $type, $reference, $email);
        $sale['examType'] = $type;
    }
} catch (Throwable $exception) {
    error_log('[Flexi scratch-card checkout] ' . $exception->getMessage());
    $error = $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" sizes="32x32" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
<link rel="icon" type="image/png" sizes="16x16" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
<link rel="apple-touch-icon" sizes="180x180" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="referrer" content="no-referrer">
<title>Flexi Tutors | Purchase Cards</title>
<style>
        :root { --blue: #003366; --green: #2E8B57; --yellow: #FFD700; --bg: #f4f7f6; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; padding: 0; }
        header { 
            background: var(--blue); color: white; height: 1.2cm; 
            display: flex; align-items: center; padding: 0 15px;
            border-bottom: 3px solid var(--green); position: sticky; top: 0; z-index: 1000;
        }
        .back-btn { color: white; text-decoration: none; font-size: 22px; margin-right: 15px; }
        .container { max-width: 500px; margin: 20px auto; width: 92%; padding-bottom: 50px; }
        .product-card {
            background: white; border-radius: 15px; overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px;
            border: 1px solid #eee; text-align: center; padding: 20px;
        }
        .product-img { width: 100%; max-height: 160px; object-fit: contain; margin-bottom: 15px; border-radius: 10px; }
        .product-name { font-size: 19px; font-weight: bold; color: var(--blue); margin-bottom: 5px; }
        .product-desc { font-size: 13px; color: #666; margin-bottom: 15px; }
        .product-price { font-size: 24px; font-weight: 800; color: var(--green); margin-bottom: 20px; }
        .buy-btn {
            background: var(--blue); color: white; border: none;
            padding: 15px 30px; border-radius: 30px; font-size: 16px;
            font-weight: bold; cursor: pointer; width: 100%; transition: 0.2s ease;
        }
        .buy-btn:disabled { background: #888 !important; cursor: not-allowed; transform: none; }
        .buy-btn:active:not(:disabled) { transform: scale(0.97); }
        
        #result-display {
            background: white; padding: 30px; border-radius: 20px;
            text-align: center; border: 2px solid var(--green); margin-top: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .pin-box { 
            background: #f0f4f8; padding: 20px; border-radius: 12px; 
            border: 2px dashed var(--blue); margin: 20px 0; font-family: monospace;
            font-size: 1.1rem; line-height: 1.8;
        }
        footer { background: var(--blue); color: white; padding: 30px 15px; text-align: center; border-top: 5px solid var(--green); margin-top: 40px; }
    
        .checkout-email { width:100%; box-sizing:border-box; padding:12px; border:1px solid #ddd; border-radius:9px; margin:0 0 12px; font:inherit; }
        .error-message { background:#fff3f3; color:#a12626; border:1px solid #f0caca; border-radius:10px; padding:12px; margin-bottom:16px; }
        .stock-note { font-size:12px; color:#a12626; margin-top:9px; }
        .success-note { color:var(--green); font-weight:bold; }
</style>
</head>
<body>
<header>
    <a href="index.html" class="back-btn">❮</a>
    <span style="font-weight: bold; font-size: 16px;">Secure Checkout</span>
</header>

<div class="container" id="main-content">
    <?php if ($error !== ''): ?><div class="error-message" role="alert"><?= e($error) ?></div><?php endif; ?>
    <?php if ($sale): ?>
    <div id="result-display">
        <h2 class="success-note">Payment Successful!</h2>
        <p>Your <?= e($sale['examType']) ?> card has been allocated.</p>
        <div class="pin-box">
            <?php if ($sale['examType'] === 'WAEC'): ?>
            <b>SERIAL:</b> <?= e($sale['serial'] ?? '') ?><br><b>PIN:</b> <?= e($sale['pinCode'] ?? '') ?>
            <?php else: ?><b>TOKEN:</b> <?= e($sale['token'] ?? '') ?><?php endif; ?>
        </div>
        <p style="font-size:12px;color:red;font-weight:bold;">Save these details securely. They may not be shown again.</p>
        <button type="button" onclick="window.location.href='index.html'" class="buy-btn" style="background:var(--green);margin-top:15px;">Return Home</button>
    </div>
    <?php else: ?>
    <div class="product-card">
        <img src="https://i.postimg.cc/tTvwhTSh/product3-2.jpg" class="product-img" alt="WAEC">
        <div class="product-name">WAEC Scratch Card</div>
        <div class="product-desc">Instant Result Checker & Registration PIN</div>
        <div class="product-price">₦<span id="price-waec"><?= e(naira($prices['WAEC'])) ?></span></div>
        <form method="post" action="/purchase.php">
            <input type="hidden" name="product" value="WAEC">
            <input type="hidden" name="email" value="billing@flexitutors.com">
            <button id="btn-WAEC" type="submit" class="buy-btn" <?= !$stock['WAEC'] ? 'disabled' : '' ?>><?= $stock['WAEC'] ? 'Purchase WAEC Card' : 'OUT OF STOCK' ?></button>
        </form>
    </div>

    <div class="product-card">
        <img src="https://i.postimg.cc/hvS8PbbN/product28-2.jpg" class="product-img" alt="NECO">
        <div class="product-name">NECO Token</div>
        <div class="product-desc">Official NECO Result Checker Token</div>
        <div class="product-price">₦<span id="price-neco"><?= e(naira($prices['NECO'])) ?></span></div>
        <form method="post" action="/purchase.php">
            <input type="hidden" name="product" value="NECO">
            <input type="hidden" name="email" value="billing@flexitutors.com">
            <button id="btn-NECO" type="submit" class="buy-btn" <?= !$stock['NECO'] ? 'disabled' : '' ?>><?= $stock['NECO'] ? 'Purchase NECO Token' : 'OUT OF STOCK' ?></button>
        </form>
    </div>
    <?php endif; ?>
</div>

<footer>
    <div>&copy; <?= date('Y') ?> flexieduconsult</div>
    <p><a href="/about-us.php" style="color:white;">About Us</a></p>
</footer>
</body>
</html>