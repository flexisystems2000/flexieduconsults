<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/scratch-card-service.php';

function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function naira(int $amount): string { return '₦' . number_format($amount); }

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
        $email = trim((string)($_POST['email'] ?? ''));
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

$siteUrl = 'https://flexieduconsult.com.ng/purchase.php';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Purchase WAEC scratch cards and NECO result checker tokens securely from Flexi Educational Consult.">
<link rel="canonical" href="<?= e($siteUrl) ?>">
<meta name="theme-color" content="#003366">
<title>Purchase Scratch Cards | Flexi Educational Consult</title>
<style>
:root{--blue:#003366;--green:#2E8B57;--gold:#FFD700;--bg:#f4f7f6}
*{box-sizing:border-box}body{margin:0;background:var(--bg);font-family:Inter,system-ui,Arial,sans-serif;color:#172033}
header{background:var(--blue);color:#fff;padding:16px max(4%,calc((100% - 1050px)/2));border-bottom:4px solid var(--green);display:flex;align-items:center;gap:16px}
header a{color:#fff;text-decoration:none;font-size:22px}.wrap{max-width:960px;width:92%;margin:34px auto 60px}
.intro{text-align:center;margin:0 auto 26px;max-width:680px}.intro h1{color:var(--blue);margin-bottom:8px}.intro p{color:#526174;line-height:1.7}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px}
.card,.result,.notice{background:#fff;border:1px solid #e5eaf0;border-radius:18px;padding:24px;box-shadow:0 8px 28px rgba(0,0,0,.05)}
.card{text-align:center}.card img{width:100%;height:145px;object-fit:contain}.card h2{color:var(--blue);font-size:21px;margin:12px 0 6px}.muted{color:#64748b;font-size:14px;line-height:1.6}.price{font-size:28px;font-weight:800;color:var(--green);margin:18px}
label{display:block;text-align:left;font-size:13px;font-weight:700;color:#334155;margin:12px 0 6px}
input{width:100%;padding:13px;border:1px solid #cbd5e1;border-radius:10px;font:inherit}
button{width:100%;border:0;border-radius:11px;padding:14px;background:var(--blue);color:#fff;font-weight:800;font-size:15px;cursor:pointer;margin-top:14px}
button:disabled{background:#94a3b8;cursor:not-allowed}.result{text-align:center;margin:24px auto;max-width:650px;border:2px solid var(--green)}
.pin{padding:18px;background:#f1f5f9;border:1px dashed var(--blue);border-radius:12px;font:700 18px/1.8 monospace;overflow-wrap:anywhere;margin:18px 0}
.alert{padding:14px;border-radius:10px;background:#fff7ed;color:#9a3412;margin:16px 0}.success{color:var(--green)}
footer{background:#011f38;color:#cbd5e1;padding:25px;text-align:center}footer a{color:#fff}
@media(max-width:520px){.wrap{margin-top:22px}.card,.result{padding:18px}}
</style>
</head>
<body>
<header><a href="/index.php" aria-label="Back to home">‹</a><strong>FLEXI EDUCATIONAL CONSULT · SECURE CHECKOUT</strong></header>
<main class="wrap">
<section class="intro"><h1>Purchase Examination Scratch Cards</h1><p>Choose your card, enter the email address for your payment receipt, and complete checkout through Paystack. Your card details are released only after the server confirms the successful payment.</p></section>
<?php if ($error !== ''): ?><div class="alert" role="alert"><?= e($error) ?></div><?php endif; ?>
<?php if ($sale): ?>
<section class="result">
<h2 class="success">Payment verified</h2>
<p>Your <?= e($sale['examType']) ?> card has been allocated successfully.</p>
<div class="pin">
<?php if ($sale['examType'] === 'WAEC'): ?>
<div>SERIAL: <?= e($sale['serial'] ?? '') ?></div><div>PIN: <?= e($sale['pinCode'] ?? '') ?></div>
<?php else: ?><div>TOKEN: <?= e($sale['token'] ?? '') ?></div><?php endif; ?>
</div>
<p class="muted">Keep this information private. Your payment reference is <?= e($_GET['reference'] ?? '') ?>.</p>
<p><a href="/index.php">Return to Flexi Educational Consult</a></p>
</section>
<?php else: ?>
<div class="grid">
<?php foreach ([
 'WAEC' => ['title'=>'WAEC Scratch Card','description'=>'For checking WAEC examination results.','image'=>'https://i.postimg.cc/tTvwhTSh/product3-2.jpg','button'=>'Purchase WAEC Card'],
 'NECO' => ['title'=>'NECO Result Checker Token','description'=>'For checking NECO examination results.','image'=>'https://i.postimg.cc/hvS8PbbN/product28-2.jpg','button'=>'Purchase NECO Token']
] as $type => $product): ?>
<section class="card">
<img src="<?= e($product['image']) ?>" alt="<?= e($product['title']) ?>" loading="lazy">
<h2><?= e($product['title']) ?></h2><p class="muted"><?= e($product['description']) ?></p>
<div class="price"><?= e(naira($prices[$type])) ?></div>
<form method="post" action="/purchase.php">
<input type="hidden" name="product" value="<?= e($type) ?>">
<label for="email-<?= e($type) ?>">Email address</label>
<input id="email-<?= e($type) ?>" type="email" name="email" autocomplete="email" required maxlength="254" placeholder="you@example.com">
<button type="submit" <?= !$stock[$type] ? 'disabled' : '' ?>><?= $stock[$type] ? e($product['button']) : 'Currently out of stock' ?></button>
</form>
</section>
<?php endforeach; ?>
</div>
<?php endif; ?>
</main>
<footer>© <?= date('Y') ?> Flexi Educational Consult · <a href="/about-us.php">About Us</a> · <a href="/policy.php">Privacy</a></footer>
</body>
</html>