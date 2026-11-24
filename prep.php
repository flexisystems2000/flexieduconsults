<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/scratch-card-service.php';
header('Cache-Control: no-store, private');
header('Referrer-Policy: no-referrer');

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


        if ($action === 'unlock') {
            $pin = trim((string)($_POST['pin'] ?? ''));
            if ($pin === '' || strlen($pin) > 40) throw new InvalidArgumentException('Enter your access PIN.');
            $matches = $db->query('expo_pins', [['pinCode', 'EQUAL', $pin]], 2);
            if (!$matches) throw new RuntimeException('Invalid PIN');
            $subject = (string)($matches[0]['fields']['subject'] ?? '');
            if ($subject === '') throw new RuntimeException('This PIN is not linked to a subject.');
            $dataDoc = $db->get('expo_data/' . rawurlencode($subject));
            if (!$dataDoc) throw new RuntimeException('Exam data not yet available for this subject.');
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

$data = $study['data'] ?? [];
$questionImages = $data['questionImages'] ?? $data['questionImage'] ?? [];
if (!is_array($questionImages)) $questionImages = $questionImages ? [$questionImages] : [];
$questionImages = array_values(array_filter(array_map('safe_image', $questionImages)));
$objectiveImage = safe_image($data['objectiveImg'] ?? '');
$lightboxUrls = [];
$theoryImageIndexes = [];
if ($objectiveImage !== '') $lightboxUrls[] = $objectiveImage;
foreach ($questionImages as $url) $lightboxUrls[] = $url;
for ($i = 1; $i <= 20; $i++) {
    $url = safe_image($data['ansImg' . $i] ?? '');
    if ($url !== '') {
        $theoryImageIndexes[$i] = count($lightboxUrls);
        $lightboxUrls[] = $url;
    }
}
$imgIndex = 0;
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
<script src="https://js.paystack.co/v2/inline.js"></script>
<title>Flexi Tutors | Student Hub</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<style>
:root { 
    --blue: #003366; 
    --green: #2E8B57; 
    --light-bg: #f8fafc;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: var(--light-bg);
    color: #1a202c;
}

header { 
    background: var(--blue);
    color: white;
    height: 60px; 
    display: flex;
    align-items: center;
    padding: 0 20px;
    border-bottom: 4px solid var(--green);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.brand-name { font-weight: 800; font-size: 0.9rem; margin-left: 10px; }

.container { max-width: 550px; margin: 20px auto; width: 92%; }
.card { background: white; padding: 25px; border-radius: 16px; box-shadow: var(--card-shadow); margin-bottom: 20px; }

input, select {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 10px;
    font-size: 0.95rem;
    outline: none;
    box-sizing: border-box;
}

.btn { width: 100%; padding: 15px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; margin-top: 15px; }
.btn-blue { background: var(--blue); color: white; }
.btn-green { background: var(--green); color: white; }

.info-strip {
    background: white;
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border-left: 5px solid var(--blue);
}
.info-label { font-size: 0.75rem; font-weight: bold; color: #718096; text-transform: uppercase; }
.info-value { font-weight: 800; color: var(--blue); font-size: 1.1rem; }

.img-wrapper { position: relative; margin-bottom: 15px; }
.img-actions { display: flex; gap: 8px; margin-top: 8px; }
.action-btn {
    background: #e7f3ef;
    color: var(--green);
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-icon { height: 16px; width: 16px; }

.ans-section {
    border-left: 4px solid var(--green);
    background: #fff;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 0 12px 12px 0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    white-space: pre-wrap; 
    word-wrap: break-word;
}
.ans-num { font-weight: 800; color: var(--blue); margin-bottom: 5px; font-size: 0.9rem; }

/* NEW OBJECTIVE STYLING */
.obj-container {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    border: 2px dashed #cbd5e0;
    margin-bottom: 25px;
}

#lightbox {
    display: none;
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.98);
    z-index: 3000;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

#lightbox-img { max-width: 95%; max-height: 85%; object-fit: contain; }

.lb-top-bar {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 12px;
}

.lb-btn {
    background: rgba(255,255,255,0.15);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.lb-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.1);
    color: white;
    font-size: 2.5rem;
    border: none;
    padding: 30px 15px;
    cursor: pointer;
    transition: 0.3s;
}
.lb-nav:disabled { opacity: 0.1; cursor: not-allowed; }
.lb-prev { left: 0; border-radius: 0 15px 15px 0; }
.lb-next { right: 0; border-radius: 15px 0 0 15px; }

#expo-display { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #f4f7f6; overflow-y: auto; z-index: 2000; }

#question-area p {
    white-space: pre-wrap;
    line-height: 1.6;
}

.error-message { background:#fff3f3; color:#a12626; border:1px solid #f0caca; border-radius:10px; padding:12px; margin-bottom:16px; }
.pin-result { background:#f0fdf4; border-left:5px solid var(--green); padding:15px; border-radius:10px; margin-bottom:20px; }
.study-image { width:100%; border-radius:12px; cursor:pointer; display:block; }
.objective-text { white-space:pre-wrap; font-weight:bold; color:var(--green); }
</style>
</head>
<body>

<div id="lightbox" onclick="closeOnBg(event)">
    <div class="lb-top-bar">
        <a class="lb-btn" id="lb-download" target="_blank" rel="noopener">
            <img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" class="btn-icon" style="filter: invert(1);"> Download
        </a>
        <button class="lb-btn" id="lb-share" type="button">
            <img src="https://cdn-icons-png.flaticon.com/512/1358/1358023.png" class="btn-icon" style="filter: invert(1);"> Share
        </button>
    </div>
    <button class="lb-nav lb-prev" id="lb-prev-btn" type="button">&lt;</button>
    <img id="lightbox-img" src="" alt="Study material">
    <button class="lb-nav lb-next" id="lb-next-btn" type="button">&gt;</button>
</div>

<div id="main-page" style="display:<?= $study ? 'none' : 'block' ?>;">
    <header>
        <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" style="height:35px;border-radius:5px;" alt="Flexi">
        <span class="brand-name">Flexi Educational Consult</span>
    </header>

    <div class="container">
        <?php if ($error !== ''): ?><div class="error-message" role="alert"><?= e($error) ?></div><?php endif; ?>
        <?php if ($sale): ?>
        <div class="card pin-result">
            <strong>Payment Successful!</strong>
            <p>Your subject access PIN is:</p>
            <div style="font-size:1.25rem;font-weight:800;letter-spacing:3px;"><?= e($sale['pinCode'] ?? '') ?></div>
            <p>Subject: <?= e($sale['subject'] ?? '') ?></p>
        </div>
        <?php endif; ?>

        <div class="card">
            <h3>🔑 Unlock Exam Portal</h3>
            <form method="post" action="/prep.php">
                <input type="hidden" name="action" value="unlock">
                <input type="text" name="pin" id="login-pin" maxlength="4" placeholder="ENTER PIN" autocomplete="off" required style="text-align:center;letter-spacing:8px;font-weight:bold;">
                <button class="btn btn-blue" id="unlock-btn" type="submit">VIEW ANSWERS</button>
            </form>
        </div>

        <div class="card">
            <h3>🛒 Purchase Subject PIN</h3>
            <form id="subject-purchase-form">
                <input type="email" name="email" id="user-email" placeholder="Enter email" autocomplete="email" maxlength="254" required style="margin-bottom:10px;">
                <select id="buy-subject" name="subject" required>
                    <?php foreach ($subjectOptions as $subjectOption): ?>
                    <option value="<?= e($subjectOption) ?>"><?= e($subjectOption) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-green" id="pay-btn" type="submit">PAY WITH PAYSTACK</button>
            </form>
        </div>
    </div>
</div>

<div id="expo-display" style="display:<?= $study ? 'block' : 'none' ?>;">
    <header>
        <span class="brand-name">EXAMINATION HUB</span>
    </header>
    <div class="container">
        <div class="info-strip">
            <span class="info-label">Subject:</span><br>
            <span class="info-value" id="display-subject-name"><?= e($study['subject'] ?? '--') ?></span><br><br>
            <span class="info-label">Access PIN:</span><br>
            <span class="info-value" id="display-active-pin" style="color:var(--green);"><?= e($study['pin'] ?? '----') ?></span>
        </div>

        <h4 style="margin-bottom:10px;color:var(--blue);">QUESTIONS</h4>
        <div id="question-area">
            <?php foreach ($questionImages as $questionIndex => $url): ?>
            <div class="img-wrapper">
                <img src="<?= e($url) ?>" class="study-image" alt="Question page" loading="lazy" onclick="openLightbox(<?= ($objectiveImage !== '' ? 1 : 0) + $questionIndex ?>)">
                <div class="img-actions">
                    <a href="<?= e($url) ?>" target="_blank" rel="noopener" class="action-btn"><img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" class="btn-icon"> Download</a>
                    <button class="action-btn" type="button" onclick="shareContent(<?= e(json_encode($url, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>)"> <img src="https://cdn-icons-png.flaticon.com/512/1358/1358023.png" class="btn-icon"> Share</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <h4 style="margin-bottom:10px;color:var(--blue);">OBJECTIVES</h4>
        <div id="obj-area" class="obj-container">
            <?php if (trim((string)($data['objectiveText'] ?? '')) !== ''): ?>
            <div class="objective-text"><?= e(str_replace('*', '', (string)$data['objectiveText'])) ?></div>
            <?php elseif ($objectiveImage !== ''): ?>
            <div class="img-wrapper">
                <img src="<?= e($objectiveImage) ?>" class="study-image" alt="Objective answers" loading="lazy" onclick="openLightbox(0)">
                <div class="img-actions">
                    <a href="<?= e($objectiveImage) ?>" target="_blank" rel="noopener" class="action-btn"><img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" class="btn-icon"> Download</a>
                    <button class="action-btn" type="button" onclick="shareContent(<?= e(json_encode($objectiveImage, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>)"><img src="https://cdn-icons-png.flaticon.com/512/1358/1358023.png" class="btn-icon"> Share</button>
                </div>
            </div>
            <?php else: ?><p style="color:#718096;font-style:italic;">No objective answers uploaded yet.</p><?php endif; ?>
        </div>

        <h4 style="margin:25px 0 10px;color:var(--blue);">THEORY ANSWERS</h4>
        <div id="answer-area">
            <?php for ($i = 1; $i <= 20; $i++):
                $answerText = trim((string)($data['ans' . $i] ?? ''));
                $answerImage = safe_image($data['ansImg' . $i] ?? '');
                if ($answerText === '' && $answerImage === '') continue;
                $answerImageIndex = $theoryImageIndexes[$i] ?? -1;
            ?>
            <div class="ans-section">
                <div class="ans-num">NUMBER <?= $i ?></div>
                <?php if ($answerText !== ''): ?><div><?= e(str_replace('*', '', $answerText)) ?></div><?php endif; ?>
                <?php if ($answerImage !== ''): ?>
                <div class="img-wrapper">
                    <img src="<?= e($answerImage) ?>" class="study-image" alt="Theory answer <?= $i ?>" loading="lazy" onclick="openLightbox(<?= (int)$answerImageIndex ?>)">
                    <div class="img-actions">
                        <a href="<?= e($answerImage) ?>" target="_blank" rel="noopener" class="action-btn"><img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" class="btn-icon"> Download</a>
                        <button class="action-btn" type="button" onclick="shareContent(<?= e(json_encode($answerImage, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>)"><img src="https://cdn-icons-png.flaticon.com/512/1358/1358023.png" class="btn-icon"> Share</button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script>
const allImageUrls = <?= json_encode($lightboxUrls, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
let currentImgIndex = 0;

async function shareContent(url) {
    if (navigator.share) {
        try { await navigator.share({ title: 'Flexi Exam Hub', text: 'Exam Content', url }); } catch (err) {}
    } else {
        alert('Sharing not supported on this browser.');
    }
}
function updateNavButtons() {
    document.getElementById('lb-prev-btn').disabled = currentImgIndex === 0;
    document.getElementById('lb-next-btn').disabled = currentImgIndex === allImageUrls.length - 1;
}
function openLightbox(index) {
    if (!allImageUrls.length || index < 0 || index >= allImageUrls.length) return;
    currentImgIndex = index;
    const url = allImageUrls[index];
    document.getElementById('lightbox-img').src = url;
    document.getElementById('lb-download').href = url;
    document.getElementById('lightbox').style.display = 'flex';
    updateNavButtons();
}
function closeOnBg(event) {
    if (event.target.id === 'lightbox') document.getElementById('lightbox').style.display = 'none';
}
document.getElementById('lb-prev-btn').onclick = () => { if (currentImgIndex > 0) openLightbox(currentImgIndex - 1); };
document.getElementById('lb-next-btn').onclick = () => { if (currentImgIndex < allImageUrls.length - 1) openLightbox(currentImgIndex + 1); };
document.getElementById('lb-share').onclick = () => { if (allImageUrls[currentImgIndex]) shareContent(allImageUrls[currentImgIndex]); };
window.openLightbox = openLightbox;
</script>
<script>
const flexiSubjectOptions = <?= json_encode($subjectOptions, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
document.getElementById("subject-purchase-form")?.addEventListener("submit", function(event) {
    event.preventDefault();
    const email = document.getElementById("user-email").value.trim();
    const subject = document.getElementById("buy-subject").value;
    const button = document.getElementById("pay-btn");
    if (!email || !subject || !flexiSubjectOptions.includes(subject)) return;
    if (typeof window.PaystackPop !== "function") { alert("Payment service is unavailable. Please refresh and try again."); return; }
    button.disabled = true;
    try {
        const popup = new PaystackPop();
        popup.newTransaction({
            key: "pk_live_bb0c7b83bfdbd0c9990e2c7f7fd73a86d4821a00",
            email: email,
            amount: 100000,
            currency: "NGN",
            metadata: { flexi_flow: "subject", flexi_item: subject, flexi_amount_kobo: 100000 },
            onSuccess: (transaction) => { window.location.href = "/prep.php?reference=" + encodeURIComponent(transaction.reference); },
            onCancel: () => { button.disabled = false; }
        });
    } catch (error) { button.disabled = false; alert("Unable to open Paystack checkout. Please try again."); }
});
</script>
</body>
</html>