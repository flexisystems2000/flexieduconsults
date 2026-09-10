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

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="robots"
          content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <meta name="description"
          content="<?php echo htmlspecialchars($pageDescription); ?>">

    <meta name="keywords"
          content="Flexi Educational Consult JAMB CBT App,
                   Flexi JAMB CBT App,
                   Flexi JAMB CBT,
                   Flexi JAMB CBT Lite,
                   Flexi CBT App,
                   Flexi JAMB App,
                   JAMB CBT App Nigeria,
                   JAMB CBT practice app,
                   UTME CBT app,
                   JAMB preparation app,
                   Flexi Educational Consult">

    <link rel="canonical"
          href="<?php echo $pageUrl; ?>">

    <!-- Open Graph -->
    <meta property="og:type"
          content="website">

    <meta property="og:title"
          content="Flexi JAMB CBT App & Lite | Flexi Educational Consult">

    <meta property="og:description"
          content="<?php echo htmlspecialchars($pageDescription); ?>">

    <meta property="og:url"
          content="<?php echo $pageUrl; ?>">

    <meta property="og:site_name"
          content="Flexi Educational Consult">

    <!-- Twitter -->
    <meta name="twitter:card"
          content="summary">

    <meta name="twitter:title"
          content="Flexi JAMB CBT App & Lite">

    <meta name="twitter:description"
          content="<?php echo htmlspecialchars($pageDescription); ?>">

    <title><?php echo htmlspecialchars($pageTitle); ?></title>


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
        "url": "<?php echo $pageUrl; ?>",
        "downloadUrl": "<?php echo $mainAppDownload; ?>",
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


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background: #f4f8f7;
            color: #17201d;
            line-height: 1.7;
        }

        /* HEADER */

        header {
            background:
                linear-gradient(
                    135deg,
                    #075e54,
                    #087f5b,
                    #0aa06e
                );

            color: white;
            padding: 18px 20px;
            box-shadow:
                0 4px 18px rgba(0,0,0,0.15);
        }

        .header-inner {
            max-width: 1100px;
            margin: auto;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;

            background: rgba(255,255,255,0.18);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;
        }

        .brand h1 {
            font-size: 19px;
            line-height: 1.2;
        }

        .brand span {
            display: block;
            font-size: 12px;
            opacity: 0.9;
            margin-top: 3px;
        }

        .home-link {
            color: white;
            text-decoration: none;

            border: 1px solid rgba(255,255,255,0.4);
            padding: 8px 14px;
            border-radius: 8px;

            font-size: 14px;
        }

        .home-link:hover {
            background: rgba(255,255,255,0.12);
        }


        /* HERO */

        .hero {
            background: white;
            padding: 65px 20px 55px;
            text-align: center;
        }

        .hero-container {
            max-width: 900px;
            margin: auto;
        }

        .app-label {
            display: inline-block;

            background: #e5f7ef;
            color: #087f5b;

            padding: 7px 15px;
            border-radius: 30px;

            font-size: 13px;
            font-weight: 700;

            margin-bottom: 18px;
        }

        .hero h2 {
            font-size: clamp(32px, 6vw, 52px);
            line-height: 1.12;

            color: #075e54;

            margin-bottom: 18px;
        }

        .hero h2 span {
            color: #087f5b;
        }

        .hero p {
            max-width: 720px;
            margin: auto;

            color: #53605c;
            font-size: 17px;
        }


        /* APP CARDS */

        .apps-section {
            max-width: 1100px;
            margin: auto;

            padding: 45px 20px 30px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title h2 {
            color: #075e54;
            font-size: 29px;
            margin-bottom: 7px;
        }

        .section-title p {
            color: #65716d;
        }

        .apps-grid {
            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(290px, 1fr));

            gap: 25px;
        }

        .app-card {
            background: white;

            border-radius: 18px;

            padding: 30px;

            box-shadow:
                0 8px 30px rgba(0,0,0,0.07);

            border: 1px solid #e2ebe7;

            position: relative;
            overflow: hidden;
        }

        .app-card::before {
            content: "";

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 5px;

            background:
                linear-gradient(
                    90deg,
                    #075e54,
                    #0aa06e
                );
        }

        .app-badge {
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

        .app-card h3 {
            font-size: 25px;
            color: #17332b;

            margin-bottom: 7px;
        }

        .version {
            font-size: 13px;
            color: #68756f;

            margin-bottom: 17px;
        }

        .app-card p {
            color: #58645f;

            font-size: 15px;

            margin-bottom: 22px;
        }

        .features {
            list-style: none;
            margin-bottom: 25px;
        }

        .features li {
            margin-bottom: 9px;
            font-size: 14px;
            color: #405049;
        }

        .features li::before {
            content: "✓";
            color: #087f5b;
            font-weight: bold;
            margin-right: 8px;
        }

        .download-btn {
            display: block;

            width: 100%;

            background:
                linear-gradient(
                    135deg,
                    #075e54,
                    #087f5b
                );

            color: white;

            text-decoration: none;

            text-align: center;

            padding: 14px 18px;

            border-radius: 10px;

            font-weight: 700;

            transition:
                transform .2s,
                box-shadow .2s;
        }

        .download-btn:hover {
            transform: translateY(-2px);

            box-shadow:
                0 7px 18px rgba(7,94,84,0.25);
        }

        .disabled-btn {
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


        /* SEO CONTENT */

        .content-section {
            max-width: 950px;
            margin: auto;

            padding: 35px 20px 60px;
        }

        .content-box {
            background: white;

            padding: 35px;

            border-radius: 16px;

            border: 1px solid #e2ebe7;

            box-shadow:
                0 5px 20px rgba(0,0,0,0.04);
        }

        .content-box h2 {
            color: #075e54;
            margin-bottom: 15px;
            font-size: 26px;
        }

        .content-box h3 {
            color: #087f5b;
            margin-top: 25px;
            margin-bottom: 8px;
        }

        .content-box p {
            color: #505d57;
            margin-bottom: 13px;
        }


        /* INFO */

        .info {
            max-width: 900px;

            margin: 0 auto 60px;

            padding: 22px 25px;

            background: #e9f8f1;

            border-left: 5px solid #087f5b;

            border-radius: 8px;
        }

        .info strong {
            color: #075e54;
        }


        /* FOOTER */

        footer {
            background: #063f39;

            color: rgba(255,255,255,0.8);

            text-align: center;

            padding: 30px 20px;

            font-size: 13px;
        }

        footer strong {
            color: white;
        }

        footer a {
            color: white;
            text-decoration: none;
        }


        /* MOBILE */

        @media (max-width: 600px) {

            .header-inner {
                align-items: flex-start;
            }

            .home-link {
                font-size: 12px;
                padding: 7px 10px;
            }

            .hero {
                padding: 45px 18px;
            }

            .hero p {
                font-size: 15px;
            }

            .apps-section {
                padding-left: 15px;
                padding-right: 15px;
            }

            .app-card {
                padding: 25px 22px;
            }

            .content-box {
                padding: 25px 20px;
            }

        }

    </style>

</head>


<body>


<!-- HEADER -->

<header>

    <div class="header-inner">

        <div class="brand">

            <div class="brand-icon">
                📚
            </div>

            <div>
                <h1>Flexi Educational Consult</h1>

                <span>
                    JAMB • WAEC • CBT Preparation
                </span>
            </div>

        </div>

        <a
            href="https://www.flexieduconsult.com.ng/"
            class="home-link"
        >
            Visit Website
        </a>

    </div>

</header>



<!-- HERO -->

<section class="hero">

    <div class="hero-container">

        <div class="app-label">
            OFFICIAL FLEXI EDUCATIONAL CONSULT APPS
        </div>

        <h2>
            Flexi <span>JAMB CBT App</span>
        </h2>

        <p>
            Prepare smarter for JAMB UTME with the official
            Flexi JAMB CBT App from Flexi Educational Consult.
            Practice computer-based tests and get familiar
            with the CBT examination experience.
        </p>

    </div>

</section>



<!-- APPS -->

<section class="apps-section">

    <div class="section-title">

        <h2>
            Download Flexi CBT Apps
        </h2>

        <p>
            Choose the Flexi JAMB CBT application that suits you.
        </p>

    </div>


    <div class="apps-grid">


        <!-- MAIN APP -->

        <article class="app-card">

            <div class="app-badge">
                MAIN APP
            </div>

            <h3>
                Flexi JAMB CBT App
            </h3>

            <div class="version">
                Latest Version: <strong>v1.2.0</strong>
            </div>

            <p>
                The official Flexi Educational Consult JAMB CBT
                practice application for candidates preparing
                for UTME and computer-based examinations.
            </p>

            <ul class="features">

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
                class="download-btn"
            >
                Download Flexi JAMB CBT App
            </a>

        </article>



        <!-- LITE APP -->

        <article class="app-card">

            <div class="app-badge">
                LITE APP
            </div>

            <h3>
                Flexi JAMB CBT Lite
            </h3>

            <div class="version">
                Status: <strong>Coming Soon</strong>
            </div>

            <p>
                Flexi JAMB CBT Lite is the lightweight version
                of the Flexi CBT experience, designed for
                candidates who want a simpler and lighter
                JAMB preparation application.
            </p>

            <ul class="features">

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

            <span class="disabled-btn">
                Coming Soon
            </span>

        </article>


    </div>

</section>



<!-- INFORMATION -->

<div class="info">

    <strong>Important:</strong>

    The Flexi JAMB CBT App is an Android application
    developed for candidates preparing for JAMB UTME
    and computer-based examinations.

    Download the application only from the official
    Flexi Educational Consult website or its official
    release source.

</div>



<!-- SEO CONTENT -->

<section class="content-section">

    <div class="content-box">

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
            main application is <strong>Flexi JAMB CBT App
            v1.2.0</strong>.
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



<!-- FOOTER -->

<footer>

    <p>
        © <?php echo date('Y'); ?>
        <strong>Flexi Educational Consult</strong>.
        All Rights Reserved.
    </p>

    <p style="margin-top:8px;">

        <a href="https://www.flexieduconsult.com.ng/">
            www.flexieduconsult.com.ng
        </a>

    </p>

</footer>


</body>
</html>
