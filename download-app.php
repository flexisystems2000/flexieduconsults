<?php
/*
|--------------------------------------------------------------------------
| FLEXI EDUCATIONAL CONSULT
| Official JAMB CBT Apps Download Page
|--------------------------------------------------------------------------
*/

$mainAppDownload =
    'https://github.com/flexisystems2000/Flexi-JAMB-CBT-App-/releases/download/v1.2.0/app-debug.apk';

$releasePage =
    'https://github.com/flexisystems2000/Flexi-JAMB-CBT-App-/releases/tag/v1.2.0';

/*
|--------------------------------------------------------------------------
| DOWNLOAD HANDLER
|--------------------------------------------------------------------------
*/

if (isset($_GET['download'])) {

    $download = strtolower(trim($_GET['download']));

    if ($download === 'main') {
        header('Location: ' . $mainAppDownload, true, 302);
        exit;
    }

    if ($download === 'lite') {
        http_response_code(404);
        exit('The Flexi JAMB CBT Lite App is not available yet.');
    }

    http_response_code(404);
    exit('Invalid download request.');
}

/*
|--------------------------------------------------------------------------
| SEO / PAGE INFORMATION
|--------------------------------------------------------------------------
*/

$pageUrl = 'https://www.flexieduconsult.com.ng/download-app.php';

$pageTitle =
    'Flexi JAMB CBT App & Lite | Download Flexi Educational Consult App';

$pageDescription =
    'Download the official Flexi JAMB CBT App by Flexi Educational Consult. '
    . 'Prepare for JAMB UTME with CBT practice, examination simulation and '
    . 'computer-based test preparation. Flexi JAMB CBT Lite is also coming soon.';

$pageKeywords =
    'Flexi Educational Consult JAMB CBT App, '
    . 'Flexi JAMB CBT App, '
    . 'Flexi JAMB CBT, '
    . 'Flexi JAMB CBT Lite, '
    . 'Flexi CBT App, '
    . 'Flexi JAMB App, '
    . 'JAMB CBT App Nigeria, '
    . 'JAMB CBT practice app, '
    . 'UTME CBT app, '
    . 'JAMB preparation app, '
    . 'Flexi Educational Consult';

$pageImage =
    'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';

$pageCanonical = $pageUrl;

$includeToastify = false;
$includeAdsense = false;

$currentPage = 'download-app.php';

/*
|--------------------------------------------------------------------------
| SHARED HEAD
|--------------------------------------------------------------------------
*/

require __DIR__ . '/includes/head.php';

/*
|--------------------------------------------------------------------------
| SHARED HEADER
|--------------------------------------------------------------------------
*/

require __DIR__ . '/includes/header.php';

?>

<!--
|--------------------------------------------------------------------------
| STRUCTURED DATA
|--------------------------------------------------------------------------
-->

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "Flexi JAMB CBT App",
    "alternateName": [
        "Flexi Educational Consult JAMB CBT App",
        "Flexi JAMB CBT",
        "Flexi CBT App",
        "Flexi JAMB App"
    ],
    "applicationCategory": "EducationalApplication",
    "operatingSystem": "Android",
    "softwareVersion": "1.2.0",
    "description": "Official Flexi JAMB CBT App by Flexi Educational Consult for JAMB UTME computer-based test practice and examination preparation.",
    "url": "<?php echo htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8'); ?>",
    "downloadUrl": "<?php echo htmlspecialchars($mainAppDownload, ENT_QUOTES, 'UTF-8'); ?>",
    "author": {
        "@type": "Organization",
        "name": "Flexi Educational Consult",
        "url": "https://www.flexieduconsult.com.ng/"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Flexi Educational Consult",
        "url": "https://www.flexieduconsult.com.ng/"
    },
    "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "NGN"
    }
}
</script>


<!--
|--------------------------------------------------------------------------
| PAGE CONTENT
|--------------------------------------------------------------------------
-->

<main class="flexi-app-page">

    <!-- HERO -->

    <section class="flexi-app-hero">

        <div class="flexi-app-hero-container">

            <div class="flexi-app-label">
                OFFICIAL FLEXI EDUCATIONAL CONSULT APPS
            </div>

            <h1>
                Flexi <span>JAMB CBT App</span>
            </h1>

            <p>
                Prepare smarter for JAMB UTME with the official
                Flexi JAMB CBT App from Flexi Educational Consult.
                Practice computer-based tests and get familiar
                with the CBT examination experience.
            </p>

        </div>

    </section>


    <!-- APPS -->

    <section class="flexi-apps-section">

        <div class="flexi-app-section-title">

            <h2>
                Download Flexi CBT Apps
            </h2>

            <p>
                Choose the Flexi JAMB CBT application that suits you.
            </p>

        </div>


        <div class="flexi-apps-grid">


            <!-- MAIN APP -->

            <article class="flexi-app-card">

                <div class="flexi-app-badge">
                    MAIN APP
                </div>

                <h2>
                    Flexi JAMB CBT App
                </h2>

                <div class="flexi-app-version">
                    Latest Version:
                    <strong>v1.2.0</strong>
                </div>

                <p>
                    The official Flexi Educational Consult JAMB CBT
                    practice application for candidates preparing
                    for UTME and computer-based examinations.
                </p>

                <ul class="flexi-app-features">

                    <li>
                        JAMB CBT practice environment
                    </li>

                    <li>
                        Computer-based test simulation
                    </li>

                    <li>
                        Designed for UTME preparation
                    </li>

                    <li>
                        Android application
                    </li>

                </ul>

                <a
                    href="?download=main"
                    class="flexi-app-download-btn"
                >
                    Download Flexi JAMB CBT App
                </a>

            </article>


            <!-- LITE APP -->

            <article class="flexi-app-card">

                <div class="flexi-app-badge">
                    LITE APP
                </div>

                <h2>
                    Flexi JAMB CBT Lite
                </h2>

                <div class="flexi-app-version">
                    Status:
                    <strong>Coming Soon</strong>
                </div>

                <p>
                    Flexi JAMB CBT Lite is the lightweight version
                    of the Flexi CBT experience, designed for
                    candidates who want a simpler and lighter
                    JAMB preparation application.
                </p>

                <ul class="flexi-app-features">

                    <li>
                        Lightweight CBT experience
                    </li>

                    <li>
                        Designed for easy access
                    </li>

                    <li>
                        JAMB UTME preparation
                    </li>

                    <li>
                        Android version coming soon
                    </li>

                </ul>

                <span class="flexi-app-disabled-btn">
                    Coming Soon
                </span>

            </article>


        </div>

    </section>


    <!-- INFORMATION -->

    <section class="flexi-app-info">

        <strong>Important:</strong>

        The Flexi JAMB CBT App is an Android application
        developed for candidates preparing for JAMB UTME
        and computer-based examinations.

        Download the application only from the official
        Flexi Educational Consult website or its official
        release source.

    </section>


    <!-- SEO CONTENT -->

    <section class="flexi-app-content-section">

        <div class="flexi-app-content-box">

            <h2>
                About the Flexi JAMB CBT App
            </h2>

            <p>
                The <strong>Flexi JAMB CBT App</strong> is an
                educational computer-based test application
                developed by <strong>Flexi Educational Consult</strong>
                to help students prepare for JAMB UTME examinations.
            </p>

            <p>
                Candidates can use the Flexi JAMB CBT App to become
                familiar with computer-based testing and improve
                their confidence before sitting for their actual
                JAMB examination.
            </p>


            <h3>
                Flexi Educational Consult JAMB CBT App
            </h3>

            <p>
                Searching for the
                <strong>Flexi Educational Consult JAMB CBT App</strong>?
                You are on the official application page of
                Flexi Educational Consult. The latest available
                main application is
                <strong>Flexi JAMB CBT App v1.2.0</strong>.
            </p>


            <h3>
                Flexi JAMB CBT Lite
            </h3>

            <p>
                <strong>Flexi JAMB CBT Lite</strong> is the planned
                lightweight version of the Flexi JAMB CBT application.
                It is being prepared for candidates who prefer a
                simpler and lighter CBT application.
            </p>

            <p>
                The Lite application will be made available on this
                official page when it is released.
            </p>


            <h3>
                JAMB CBT Preparation
            </h3>

            <p>
                Preparing for JAMB requires more than simply reading
                past questions. Candidates also need to understand
                how computer-based examinations work. A CBT practice
                application can help students become more comfortable
                with answering questions on a digital examination
                interface.
            </p>

            <p>
                The Flexi JAMB CBT App is part of the digital learning
                and examination-preparation resources provided by
                Flexi Educational Consult.
            </p>

        </div>

    </section>

</main>


<style>

/*
|--------------------------------------------------------------------------
| FLEXI APP PAGE
| Page-specific styles only
|--------------------------------------------------------------------------
*/

.flexi-app-page {
    background: #f4f8f7;
    color: #17201d;
    line-height: 1.7;
}


/* HERO */

.flexi-app-hero {
    background: #ffffff;
    padding: 65px 20px 55px;
    text-align: center;
}

.flexi-app-hero-container {
    max-width: 900px;
    margin: 0 auto;
}

.flexi-app-label {
    display: inline-block;
    background: #e5f7ef;
    color: #087f5b;
    padding: 7px 15px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 18px;
}

.flexi-app-hero h1 {
    font-size: clamp(32px, 6vw, 52px);
    line-height: 1.12;
    color: #075e54;
    margin: 0 0 18px;
}

.flexi-app-hero h1 span {
    color: #087f5b;
}

.flexi-app-hero p {
    max-width: 720px;
    margin: 0 auto;
    color: #53605c;
    font-size: 17px;
}


/* APPS */

.flexi-apps-section {
    max-width: 1100px;
    margin: 0 auto;
    padding: 45px 20px 30px;
}

.flexi-app-section-title {
    text-align: center;
    margin-bottom: 30px;
}

.flexi-app-section-title h2 {
    color: #075e54;
    font-size: 29px;
    margin: 0 0 7px;
}

.flexi-app-section-title p {
    color: #65716d;
    margin: 0;
}

.flexi-apps-grid {
    display: grid;
    grid-template-columns: repeat(
        auto-fit,
        minmax(290px, 1fr)
    );
    gap: 25px;
}

.flexi-app-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
    border: 1px solid #e2ebe7;
    position: relative;
    overflow: hidden;
}

.flexi-app-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(
        90deg,
        #075e54,
        #0aa06e
    );
}

.flexi-app-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.7px;
    color: #087f5b;
    background: #e6f7ef;
    padding: 5px 10px;
    border-radius: 20px;
    margin-bottom: 15px;
}

.flexi-app-card h2 {
    font-size: 25px;
    color: #17332b;
    margin: 0 0 7px;
}

.flexi-app-version {
    font-size: 13px;
    color: #68756f;
    margin-bottom: 17px;
}

.flexi-app-card p {
    color: #58645f;
    font-size: 15px;
    margin: 0 0 22px;
}

.flexi-app-features {
    list-style: none;
    margin: 0 0 25px;
    padding: 0;
}

.flexi-app-features li {
    margin-bottom: 9px;
    font-size: 14px;
    color: #405049;
}

.flexi-app-features li::before {
    content: "✓";
    color: #087f5b;
    font-weight: bold;
    margin-right: 8px;
}

.flexi-app-download-btn {
    display: block;
    width: 100%;
    background: linear-gradient(
        135deg,
        #075e54,
        #087f5b
    );
    color: #ffffff;
    text-decoration: none;
    text-align: center;
    padding: 14px 18px;
    border-radius: 10px;
    font-weight: 700;
    transition:
        transform 0.2s,
        box-shadow 0.2s;
}

.flexi-app-download-btn:hover {
    transform: translateY(-2px);
    box-shadow:
        0 7px 18px rgba(7, 94, 84, 0.25);
}

.flexi-app-disabled-btn {
    display: block;
    width: 100%;
    text-align: center;
    padding: 14px 18px;
    border-radius: 10px;
    background: #e9eeec;
    color: #7b8581;
    font-weight: 700;
    cursor: not-allowed;
}


/* INFORMATION */

.flexi-app-info {
    max-width: 900px;
    margin: 0 auto 60px;
    padding: 22px 25px;
    background: #e9f8f1;
    border-left: 5px solid #087f5b;
    border-radius: 8px;
}

.flexi-app-info strong {
    color: #075e54;
}


/* SEO CONTENT */

.flexi-app-content-section {
    max-width: 950px;
    margin: 0 auto;
    padding: 35px 20px 60px;
}

.flexi-app-content-box {
    background: #ffffff;
    padding: 35px;
    border-radius: 16px;
    border: 1px solid #e2ebe7;
    box-shadow:
        0 5px 20px rgba(0, 0, 0, 0.04);
}

.flexi-app-content-box h2 {
    color: #075e54;
    margin: 0 0 15px;
    font-size: 26px;
}

.flexi-app-content-box h3 {
    color: #087f5b;
    margin: 25px 0 8px;
}

.flexi-app-content-box p {
    color: #505d57;
    margin: 0 0 13px;
}


/* MOBILE */

@media (max-width: 600px) {

    .flexi-app-hero {
        padding: 45px 18px;
    }

    .flexi-app-hero p {
        font-size: 15px;
    }

    .flexi-apps-section {
        padding-left: 15px;
        padding-right: 15px;
    }

    .flexi-app-card {
        padding: 25px 22px;
    }

    .flexi-app-content-box {
        padding: 25px 20px;
    }

    .flexi-app-info {
        margin-left: 15px;
        margin-right: 15px;
    }

}

</style>


<?php

/*
|--------------------------------------------------------------------------
| SHARED FOOTER
|--------------------------------------------------------------------------
*/

require __DIR__ . '/includes/footer.php';

?>
