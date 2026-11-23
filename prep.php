<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/scratch-card-service.php';

function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function safe_image(mixed $url): string
{
    $url = trim((string)$url);
    return preg_match('#^https://#i', $url) ? $url : '';
}

$db = null;
$error = '';
$sale = null;
$study = null;
$subjectOptions = [
    'Account','Agricultural Science','Animal Husbandry','Arabic','Biology',
    'Catering Craft Practice','Chemistry','Christian Religious Studies (CRS)',
    'Book Keeping','Civic Education','Commerce','Computer Studies','Data Processing',
    'Economics','Fine Arts','Food and Nutrition','French','Further Mathematics',
    'Geography','Government','Hausa','History','Home Economics','Igbo','Insurance',
    'Home Management','Islamic Religious Knowledge (IRK)','Literature In English',
    'Livestock Management','Marketing','Mathematics','Music','Office Practice',
    'Physical Education','Physics','Technical Drawing','Yoruba'
];

try {
    $db = new FlexiFirestore();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = (string)($_POST['action'] ?? '');
        if ($action === 'buy') {
            $subject = trim((string)($_POST['subject'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            if (!in_array($subject, $subjectOptions, true)) throw new InvalidArgumentException('Choose a valid subject.');
            if (!$db->get('expo_pins/' . rawurlencode($subject))) {
                throw new RuntimeException('The access PIN for this subject is not available yet.');
            }
            flexi_start_checkout('subject', $subject, $email, 1000 * 100);
        }

        if ($action === 'unlock') {
            $pin = trim((string)($_POST['pin'] ?? ''));
            if ($pin === '' || strlen($pin) > 40) throw new InvalidArgumentException('Enter your access PIN.');
            $matches = $db->query('expo_pins', [['pinCode', 'EQUAL', $pin]], 2);
            if (!$matches) throw new RuntimeException('The access PIN is not valid.');
            $subject = (string)($matches[0]['fields']['subject'] ?? '');
            if ($subject === '') throw new RuntimeException('This PIN is not linked to a subject.');
            $dataDoc = $db->get('expo_data/' . rawurlencode($subject));
            if (!$dataDoc) throw new RuntimeException('Study content for this subject has not been uploaded yet.');
            $study = ['subject' => $subject, 'pin' => $pin, 'data' => $db->decodeFields($dataDoc['fields'] ?? [])];
        }
    }

    $reference = trim((string)($_GET['reference'] ?? ''));
    if ($reference !== '') {
        $verified = flexi_paystack_verify($reference);
        $metadata = $verified['metadata'] ?? [];
        if (is_string($metadata)) $metadata = json_decode($metadata, true) ?: [];
        $subject = trim((string)($metadata['flexi_item'] ?? ''));
        $amount = (int)($metadata['flexi_amount_kobo'] ?? 0);
        if (($metadata['flexi_flow'] ?? '') !== 'subject'
            || !in_array($subject, $subjectOptions, true)
            || $amount !== 100000
            || (int)($verified['amount'] ?? 0) !== 100000
            || strtoupper((string)($verified['currency'] ?? '')) !== 'NGN') {
            throw new RuntimeException('The payment could not be matched to a valid subject PIN order.');
        }
        $email = (string)($verified['customer']['email'] ?? '');
        $sale = flexi_issue_subject_pin($db, $subject, $reference, $email);
    }
} catch (Throwable $exception) {
    error_log('[Flexi subject PIN portal] ' . $exception->getMessage());
    $error = $exception->getMessage();
}

$images = [];
if ($study) {
    $data = $study['data'];
    if (!empty($data['objectiveImg'])) $images[] = $data['objectiveImg'];
    $qImages = $data['questionImages'] ?? $data['questionImage'] ?? [];
    if (is_array($qImages)) $images = array_merge($images, $qImages);
    elseif ($qImages) $images[] = $qImages;
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Access Flexi Educational Consult subject study resources and examination preparation materials.">
<link rel="canonical" href="https://flexieduconsult.com.ng/prep.php">
<meta name="theme-color" content="#003366">
<title>Student Study Hub | Flexi Educational Consult</title>
<style>
:root{--blue:#003366;--green:#2E8B57;--bg:#f4f7f6}*{box-sizing:border-box}
body{margin:0;background:var(--bg);font-family:Inter,system-ui,Arial,sans-serif;color:#172033}
header{background:var(--blue);color:white;padding:16px max(4%,calc((100% - 900px)/2));border-bottom:4px solid var(--green)}
header a{color:white;text-decoration:none}.wrap{max-width:850px;width:92%;margin:28px auto 60px}
.card,.answer{background:white;border:1px solid #e5eaf0;border-radius:16px;padding:22px;margin-bottom:18px;box-shadow:0 6px 22px rgba(0,0,0,.045)}
h1,h2,h3{color:var(--blue)}p{line-height:1.7;color:#526174}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px}
label{display:block;font-weight:700;font-size:13px;margin:12px 0 6px}
input,select{width:100%;padding:13px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;background:white}
button{width:100%;padding:14px;border:0;border-radius:10px;background:var(--blue);color:white;font-weight:800;margin-top:14px;cursor:pointer}
.green{background:var(--green)}.notice{padding:14px;background:#fff7ed;color:#9a3412;border-radius:10px;margin:14px 0}
.pin{font:800 20px monospace;padding:16px;background:#f0fdf4;border:1px dashed var(--green);border-radius:10px;overflow-wrap:anywhere}
.answer{white-space:pre-wrap;line-height:1.7}.answer img,.study-image{display:block;max-width:100%;height:auto;border-radius:10px;margin:12px 0}
.small{font-size:13px;color:#64748b}.question-title{font-weight:800;color:var(--blue);margin-bottom:8px}
footer{text-align:center;padding:25px;background:#011f38;color:#cbd5e1}footer a{color:white}
</style>
</head>
<body>
<header><a href="/index.php">‹ Back to Flexi Educational Consult</a></header>
<main class="wrap">
<h1>Flexi Student Study Hub</h1>
<p>Purchase a subject access PIN or enter a PIN you already have. Payment verification and access checks are performed by the website server.</p>
<?php if ($error !== ''): ?><div class="notice" role="alert"><?= e($error) ?></div><?php endif; ?>
<?php if ($sale): ?>
<section class="card">
<h2>Payment verified</h2><p>Your subject access PIN is ready.</p>
<div class="pin"><?= e($sale['pinCode'] ?? '') ?></div>
<p class="small">Subject: <?= e($sale['subject'] ?? '') ?> · Payment reference: <?= e($_GET['reference'] ?? '') ?></p>
</section>
<?php endif; ?>
<?php if (!$study): ?>
<div class="grid">
<section class="card">
<h2>Purchase Subject PIN</h2>
<p>Choose the subject and provide your email. The subject access PIN costs ₦1,000.</p>
<form method="post" action="/prep.php">
<input type="hidden" name="action" value="buy">
<label for="subject">Subject</label>
<select name="subject" id="subject" required>
<option value="">Select a subject</option>
<?php foreach ($subjectOptions as $option): ?><option value="<?= e($option) ?>"><?= e($option) ?></option><?php endforeach; ?>
</select>
<label for="email">Email address</label>
<input type="email" name="email" id="email" autocomplete="email" maxlength="254" required placeholder="you@example.com">
<button type="submit" class="green">Continue to secure payment · ₦1,000</button>
</form>
</section>
<section class="card">
<h2>Already have a PIN?</h2>
<p>Enter your subject access PIN to open the study material associated with it.</p>
<form method="post" action="/prep.php">
<input type="hidden" name="action" value="unlock">
<label for="pin">Access PIN</label>
<input type="text" name="pin" id="pin" maxlength="40" required autocomplete="off" placeholder="Enter your PIN">
<button type="submit">Unlock Study Hub</button>
</form>
</section>
</div>
<?php else: ?>
<section class="card">
<h2><?= e($study['subject']) ?></h2>
<p>Access PIN: <strong><?= e($study['pin']) ?></strong></p>
<?php
$objectiveText = trim((string)($data['objectiveText'] ?? ''));
if ($objectiveText !== ''):
?><h3>Objective Answers</h3><div class="answer"><?= e(str_replace('*', '', $objectiveText)) ?></div>
<?php elseif (!empty($data['objectiveImg'])): ?>
<h3>Objective Answers</h3><img class="study-image" src="<?= e(safe_image($data['objectiveImg'])) ?>" alt="Objective answers" loading="lazy">
<?php endif; ?>
<?php
$qImages = $data['questionImages'] ?? $data['questionImage'] ?? [];
if (!is_array($qImages)) $qImages = $qImages ? [$qImages] : [];
if ($qImages):
?><h3>Questions</h3>
<?php foreach ($qImages as $index => $url): $url = safe_image($url); if ($url !== ''): ?>
<img class="study-image" src="<?= e($url) ?>" alt="Question page <?= (int)($index+1) ?>" loading="lazy">
<?php endif; endforeach; endif; ?>
<h3>Theory Answers</h3>
<?php $hasTheory = false; for ($i=1; $i<=20; $i++): $answer = trim((string)($data['ans'.$i] ?? '')); $image = safe_image($data['ansImg'.$i] ?? ''); if ($answer !== '' || $image !== ''): $hasTheory = true; ?>
<article class="answer"><div class="question-title">NUMBER <?= $i ?></div><?php if ($answer !== ''): ?><div><?= e(str_replace('*', '', $answer)) ?></div><?php endif; ?><?php if ($image !== ''): ?><img src="<?= e($image) ?>" alt="Theory answer <?= $i ?>" loading="lazy"><?php endif; ?></article>
<?php endif; endfor; if (!$hasTheory): ?><p>No theory answers have been uploaded for this subject yet.</p><?php endif; ?>
</section>
<?php endif; ?>
</main>
<footer>© <?= date('Y') ?> Flexi Educational Consult · <a href="/about-us.php">About Us</a></footer>
</body>
</html>