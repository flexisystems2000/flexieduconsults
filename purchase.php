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
<script src="https://js.paystack.co/v2/inline.js"></script>
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
    /* ========================================================
       FOOTER
       ======================================================== */

    .footer {
        background:
            linear-gradient(
                135deg,
                #011627 0%,
                #032038 100%
            );

        color: #e2e8f0;

        padding: 60px 20px 30px;

        margin-top: 50px;

        border-top: 4px solid var(--green);

        font-size: 14px;
    }


    .footer-grid {
        display: grid;

        grid-template-columns:
            1.8fr
            1.2fr
            1.3fr
            1fr;

        gap: 35px;

        max-width: 1200px;

        margin: 0 auto;
    }


    .footer h4 {
        color: var(--yellow);

        font-size: 0.9rem;

        text-transform: uppercase;

        letter-spacing: 1.2px;

        margin: 0 0 16px 0;

        position: relative;

        padding-bottom: 6px;
    }


    .footer h4::after {
        content: '';

        position: absolute;

        left: 0;

        bottom: 0;

        width: 24px;

        height: 2px;

        background: var(--yellow);

        opacity: 0.7;

        border-radius: 2px;
    }


    .footer-about {
        line-height: 1.7;

        color: #94a3b8;

        margin: 0;
    }


    .footer-links-list {
        list-style: none;

        padding: 0;

        margin: 0;
    }


    .footer-links-list li {
        margin-bottom: 10px;
    }


    .footer a {
        color: #cbd5e0;

        text-decoration: none;

        transition: all 0.25s ease;
    }


    .footer-links-list a:hover {
        color: var(--yellow);

        transform: translateX(4px);

        display: inline-block;
    }


    .whatsapp-channel-link {
        color: #25D366 !important;

        font-weight: 600;

        display: inline-block;
    }


    .whatsapp-channel-link:hover {
        opacity: 0.85;

        transform: translateX(4px);
    }


    .contact-group {
        margin-bottom: 16px;
    }


    .contact-group h5 {
        color: #ffffff;

        font-size: 0.82rem;

        text-transform: uppercase;

        letter-spacing: 0.8px;

        margin: 0 0 6px 0;

        opacity: 0.9;
    }


    .contact-group a {
        display: block;

        color: #94a3b8;

        font-size: 0.88rem;

        margin-bottom: 4px;
    }


    .contact-group a:hover {
        color: #ffffff;
    }


    .footer-bottom {
        max-width: 1200px;

        margin: 40px auto 0;

        padding-top: 20px;

        border-top:
            1px solid rgba(255,255,255,0.08);

        text-align: center;

        font-size: 13px;

        color: #64748b;
    }


    /* ========================================================
       SUPPORT BUTTON
       ======================================================== */

    #support-btn:hover {
        transform: scale(1.1);
    }


    /* ========================================================
       APP DOWNLOAD CARD
       ======================================================== */

    .app-download-card {
        display: block;
        width: 100%;
        margin: 0 0 24px;
        border-radius: var(--radius);
        overflow: hidden;
        background: #10151c;
        box-shadow: var(--shadow);
        border: 1px solid rgba(0,0,0,.08);
        line-height: 0;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }


    .app-download-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0,0,0,.12);
    }


    .app-download-card img {
        display: block;
        width: 100%;
        height: auto;
        aspect-ratio: 1536 / 445;
        object-fit: cover;
    }


    /* ========================================================
       DESKTOP HERO / CONTENT STRUCTURE
       ======================================================== */

    .desktop-hero-grid,
    .desktop-content-grid {
        display: block;
    }


    .svg-arrow,
    .pagination-icon {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }


    .pagination-icon {
        width: 18px;
        height: 18px;
    }


    /* ========================================================
       DESKTOP
       ======================================================== */

    @media (min-width: 768px) {

        header {
            height: 64px;
            padding: 0 34px;
        }


        .logo-img {
            height: 40px;
            width: 40px;
        }


        .brand-name {
            font-size: 17px;
        }


        .container {
            max-width: 1120px;
            margin: 30px auto 46px;
        }


        .system-ticker {
            margin-bottom: 22px;
        }


        .desktop-hero-grid {
            display: grid;
            grid-template-columns: minmax(300px, .86fr) minmax(0, 1.74fr);
            gap: 22px;
            align-items: stretch;
            margin-bottom: 22px;
        }


        .desktop-hero-grid .quote-slider-container,
        .desktop-hero-grid .slider-container {
            height: 100%;
            min-height: 300px;
            margin-bottom: 0;
        }


        .desktop-hero-grid .quote-slider-container {
            min-height: 300px;
        }


        .desktop-hero-grid .slider-container {
            height: 300px;
        }


        .quote-content-box {
            padding: 30px 32px;
        }


        .quote-content-box blockquote {
            font-size: 1.28rem;
            line-height: 1.55;
        }


        .desktop-content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.55fr) minmax(300px, .95fr);
            gap: 28px;
            align-items: start;
        }


        .desktop-content-grid > .section-heading {
            grid-column: 1;
        }


        .desktop-content-grid > .news-table,
        .desktop-content-grid > .pagination-bar {
            grid-column: 1;
        }


        .desktop-content-grid > .testimonials-section {
            grid-column: 2;
            grid-row: 1 / span 3;
            margin-top: 28px;
        }


        .section-heading,
        .testimonials-heading {
            font-size: 1.38rem;
        }


        .news-table td {
            padding: 16px 18px;
        }


        .td-img {
            width: 104px;
            height: 76px;
        }


        .td-title {
            font-size: 16px;
        }


        .testimonial-slider-container {
            height: 238px;
        }


        .testimonial-slide {
            padding: 28px;
        }


        .testimonial-text {
            font-size: 15px;
            line-height: 1.65;
        }


        .card {
            padding: 30px 34px;
            margin-top: 28px;
        }


        .app-download-card {
            margin-bottom: 30px;
        }


        .square-menu {
            top: 54px;
            width: 340px;
            border-radius: 14px;
        }


        .square-menu a {
            min-height: 50px;
            padding: 14px 17px;
            font-size: 14px;
        }

        .study-menu-toggle {
            min-height: 50px;
            padding: 14px 17px;
            font-size: 14px;
        }

        .study-submenu {
            margin-left: 38px;
        }

    }


    @media (min-width: 1180px) {

        .container {
            max-width: 1180px;
        }


        .desktop-hero-grid {
            grid-template-columns: minmax(340px, .82fr) minmax(0, 1.78fr);
            gap: 26px;
        }


        .desktop-hero-grid .quote-slider-container,
        .desktop-hero-grid .slider-container {
            min-height: 326px;
        }


        .desktop-hero-grid .slider-container {
            height: 326px;
        }


        .desktop-content-grid {
            grid-template-columns: minmax(0, 1.62fr) minmax(330px, .98fr);
            gap: 32px;
        }

    }


    @media (max-width: 480px) {

        .app-download-card {
            border-radius: 10px;
        }


        .system-ticker-label {
            padding: 0 10px;

            font-size: 10px;
        }


        .system-ticker-item {
            font-size: 13px;
        }


        .testimonial-slider-container {
            height: 165px;
        }


        .slider-container {
            height: 120px;
        }


        .container {
            margin: 16px auto;
        }


        .card {
            padding: 18px 16px;
        }


        .news-table td {
            padding: 12px;
        }


        .td-img {
            width: 72px;
            height: 54px;
        }


        .td-title {
            font-size: 14px;
        }
    }


    @media (max-width: 900px) {

        .footer-grid {
            grid-template-columns:
                1fr 1fr;

            gap: 30px;
        }
    }


    @media (max-width: 550px) {

        .footer-grid {
            grid-template-columns: 1fr;

            gap: 28px;
        }
    }


    
        .checkout-email { width:100%; box-sizing:border-box; padding:12px; border:1px solid #ddd; border-radius:9px; margin:0 0 12px; font:inherit; }
        .error-message { background:#fff3f3; color:#a12626; border:1px solid #f0caca; border-radius:10px; padding:12px; margin-bottom:16px; }
        .stock-note { font-size:12px; color:#a12626; margin-top:9px; }
        .success-note { color:var(--green); font-weight:bold; }

</style>
</head>
<body>
<header>
    <a href="index.php" class="back-btn">❮</a>
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
        <button type="button" onclick="window.location.href='index.php'" class="buy-btn" style="background:var(--green);margin-top:15px;">Return Home</button>
    </div>
    <?php else: ?>
    <div class="product-card">
        <img src="https://i.postimg.cc/tTvwhTSh/product3-2.jpg" class="product-img" alt="WAEC">
        <div class="product-name">WAEC Scratch Card</div>
        <div class="product-desc">Instant Result Checker & Registration PIN</div>
        <div class="product-price">₦<span id="price-waec"><?= e(naira($prices['WAEC'])) ?></span></div>
        <form onsubmit="startCardPayment(event, 'WAEC')">
            <button id="btn-WAEC" type="submit" class="buy-btn" <?= !$stock['WAEC'] ? 'disabled' : '' ?>><?= $stock['WAEC'] ? 'Purchase WAEC Card' : 'OUT OF STOCK' ?></button>
        </form>
    </div>

    <div class="product-card">
        <img src="https://i.postimg.cc/hvS8PbbN/product28-2.jpg" class="product-img" alt="NECO">
        <div class="product-name">NECO Token</div>
        <div class="product-desc">Official NECO Result Checker Token</div>
        <div class="product-price">₦<span id="price-neco"><?= e(naira($prices['NECO'])) ?></span></div>
        <form onsubmit="startCardPayment(event, 'NECO')">
            <button id="btn-NECO" type="submit" class="buy-btn" <?= !$stock['NECO'] ? 'disabled' : '' ?>><?= $stock['NECO'] ? 'Purchase NECO Token' : 'OUT OF STOCK' ?></button>
        </form>
    </div>
    <?php endif; ?>
</div>

<footer class="footer">

    <div class="footer-grid">


        <div class="footer-col">

            <p class="footer-about">

                We empower Nigerian students with admission
                updates, CBT preparation, tutorials,
                past questions in PDF, and premium
                educational support.

            </p>

        </div>


        <div class="footer-col">

            <h4>
                Quick Links
            </h4>


            <ul class="footer-links-list">

                <li>
                    <a href="/index.php">
                        Home
                    </a>
                </li>
                <li><a href="/about-us.php">About Us</a></li>


                <li>
                    <a
                        href="https://elearning.flexieduconsult.com.ng"
                        target="_blank"
                        rel="noopener">

                        WhatsApp Masterclass (E-Learning)

                    </a>
                </li>


                <li>
                    <a href="/syllabus.php">
                        Access the JAMB/WAEC syllabus
                    </a>
                </li>


                <li>
                    <a href="/brochure.php">
                        Access JAMB Brochure
                    </a>
                </li>


                <li>
                    <a href="/videos.php">
                        Video Lessons
                    </a>
                </li>


                <li>
                    <a href="/pdf.php">
                        Past Questions & PDFs
                    </a>
                </li>


                <li>
                    <a href="/cbt.php">
                        CBT Simulator
                    </a>
                </li>


                <li>
                    <a href="/groups.php">
                        Classroom Groups and chats
                    </a>
                </li>

                <li>
    <a href="/terms.php">
        Terms and Conditions
    </a>
</li>

<li>
    <a href="/policy.php">
        Privacy Policy
    </a>
</li>

                <li>
                    <a href="/location.php">
                        Tutorial Centres
                    </a>
                </li>

            </ul>

        </div>


        <div class="footer-col">

            <h4>
                Support & Community
            </h4>


            <div class="contact-group">

                <a
                    href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u"
                    target="_blank"
                    class="whatsapp-channel-link">

                    Join our WhatsApp Channel

                </a>

            </div>


            <div class="contact-group">

                <h5>
                    Contact Us
                </h5>


                <a href="tel:+2349034159839">
                    (+234) 903 415 9839
                </a>


                <a href="tel:+2347033855206">
                    (+234) 703 385 5206
                </a>

            </div>


            <div class="contact-group">

                <h5>
                    Email Us
                </h5>


                <a href="mailto:support@flexieduconsult.com.ng">
                    support@flexieduconsult.com.ng
                </a>


                <a href="mailto:info@flexieduconsult.com.ng">
                    info@flexieduconsult.com.ng
                </a>

            </div>

        </div>


        <div class="footer-col social-links">

            <h4>
                Follow Us
            </h4>


            <ul class="footer-links-list">

                <li>
                    <a
                        href="https://www.facebook.com/profile.php?id=61589793118693"
                        target="_blank">

                        Facebook @flexieduconsult

                    </a>
                </li>


                <li>
                    <a
                        href="https://instagram.com/flexieduconsult2000"
                        target="_blank">

                        Instagram @flexieduconsult2000

                    </a>
                </li>


                <li>
                    <a
                        href="https://www.tiktok.com/@flexieduconsult"
                        target="_blank">

                        TikTok @flexieduconsult

                    </a>
                </li>

            </ul>

        </div>


    </div>


    <div class="footer-bottom">

        &copy;
        <?= date('Y') ?>
        Flexi Educational Consult.
        All Rights Reserved.

    </div>

</footer>
<script>
const flexiCardPrices = <?= json_encode($prices, JSON_UNESCAPED_SLASHES) ?>;
async function startCardPayment(event, type) {
    event.preventDefault();
    const button = document.getElementById("btn-" + type);
    if (!button || button.disabled) return;
    if (typeof window.PaystackPop !== "function") { alert("Payment service is unavailable. Please refresh and try again."); return; }
    button.disabled = true;
    try {
        const popup = new PaystackPop();
        popup.newTransaction({
            key: "pk_live_bb0c7b83bfdbd0c9990e2c7f7fd73a86d4821a00",
            email: "billing@flexitutors.com",
            amount: Number(flexiCardPrices[type]) * 100,
            currency: "NGN",
            metadata: { flexi_flow: "stock", flexi_item: type, flexi_amount_kobo: Number(flexiCardPrices[type]) * 100 },
            onSuccess: (transaction) => { window.location.href = "/purchase.php?reference=" + encodeURIComponent(transaction.reference); },
            onCancel: () => { button.disabled = false; }
        });
    } catch (error) { button.disabled = false; alert("Unable to open Paystack checkout. Please try again."); }
}
</script>
    <script src="/assets/js/disable-copy-paste.js" defer></script>
</body>
</html>