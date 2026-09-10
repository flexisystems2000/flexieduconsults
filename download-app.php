<?php

/*
|--------------------------------------------------------------------------
| FLEXI EDUCATIONAL CONSULT
| App Download Page
|--------------------------------------------------------------------------
*/

$apps = [
    'main' => [
        'name' => 'Flexi JAMB CBT App',
        'version' => 'v1.2.0',
        'description' => 'The full Flexi JAMB CBT application with the complete CBT experience and features.',
        'file' => __DIR__ . '/apps/main/Flexi-JAMB-CBT-App.apk',
        'download_name' => 'Flexi-JAMB-CBT-App.apk',
        'type' => 'Main App'
    ],

    'lite' => [
        'name' => 'Flexi JAMB CBT Lite',
        'version' => 'Coming Soon',
        'description' => 'A lightweight version of the Flexi JAMB CBT App designed for users who need a simpler and faster experience.',
        'file' => __DIR__ . '/apps/lite/Flexi-JAMB-CBT-Lite.apk',
        'download_name' => 'Flexi-JAMB-CBT-Lite.apk',
        'type' => 'Lite App'
    ]
];


/*
|--------------------------------------------------------------------------
| DOWNLOAD HANDLER
|--------------------------------------------------------------------------
*/

if (isset($_GET['download'])) {

    $app = strtolower(trim($_GET['download']));

    if (!isset($apps[$app])) {
        http_response_code(404);
        exit('Invalid application.');
    }

    $file = $apps[$app]['file'];
    $downloadName = $apps[$app]['download_name'];

    if (!file_exists($file) || !is_file($file)) {
        http_response_code(404);
        exit('Sorry, this application is currently unavailable.');
    }

    if (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.android.package-archive');
    header('Content-Disposition: attachment; filename="' . $downloadName . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    readfile($file);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<meta name="theme-color"
      content="#087f5b">

<meta name="description"
      content="Download Flexi JAMB CBT App and Flexi JAMB CBT Lite App.">

<title>Download Apps | Flexi Educational Consult</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f8f7;
    color: #172a24;
    min-height: 100vh;
}


/* ================================
   BLUE-GREEN HEADER
================================ */

.header {
    background: linear-gradient(
        135deg,
        #064e8c 0%,
        #087f8c 48%,
        #008f5a 100%
    );

    color: white;
    padding: 18px 6%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.header-inner {
    max-width: 1150px;
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

.brand-logo {
    width: 48px;
    height: 48px;

    background: rgba(255,255,255,0.15);

    border: 1px solid rgba(255,255,255,0.35);

    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 22px;
    font-weight: bold;
}

.brand-text h1 {
    font-size: 19px;
    margin-bottom: 3px;
}

.brand-text p {
    font-size: 12px;
    opacity: 0.88;
}

.header-label {
    background: rgba(255,255,255,0.14);

    border: 1px solid rgba(255,255,255,0.25);

    padding: 9px 15px;

    border-radius: 30px;

    font-size: 13px;
    font-weight: bold;
}


/* ================================
   MAIN
================================ */

.container {
    width: 92%;
    max-width: 1150px;

    margin: 45px auto 70px;
}

.hero {
    text-align: center;
    margin-bottom: 40px;
}

.hero h2 {
    font-size: 32px;
    margin-bottom: 10px;

    background: linear-gradient(
        90deg,
        #064e8c,
        #087f5b
    );

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero p {
    max-width: 650px;
    margin: auto;

    color: #61716b;

    line-height: 1.7;

    font-size: 15px;
}


/* ================================
   APP GRID
================================ */

.apps-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 25px;
}

.app-card {
    background: white;

    border-radius: 20px;

    padding: 30px;

    border: 1px solid #e2ebe8;

    box-shadow:
        0 8px 30px rgba(0,0,0,0.07);

    position: relative;

    overflow: hidden;

    transition: transform .2s ease,
                box-shadow .2s ease;
}

.app-card:hover {
    transform: translateY(-4px);

    box-shadow:
        0 15px 40px rgba(0,0,0,0.11);
}

.app-card::before {
    content: "";

    position: absolute;

    top: 0;
    left: 0;
    right: 0;

    height: 5px;

    background: linear-gradient(
        90deg,
        #064e8c,
        #008f5a
    );
}

.app-top {
    display: flex;
    align-items: center;

    gap: 16px;

    margin-bottom: 22px;
}

.app-icon {
    width: 65px;
    height: 65px;

    flex-shrink: 0;

    border-radius: 17px;

    background: linear-gradient(
        135deg,
        #064e8c,
        #008f5a
    );

    color: white;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 27px;
    font-weight: bold;

    box-shadow:
        0 7px 18px rgba(0,95,100,0.22);
}

.app-title h3 {
    font-size: 20px;

    color: #16352c;

    margin-bottom: 6px;
}

.badge {
    display: inline-block;

    padding: 5px 9px;

    border-radius: 20px;

    background: #e8f5f0;

    color: #087f5b;

    font-size: 11px;

    font-weight: bold;
}

.app-description {
    color: #64756e;

    font-size: 14px;

    line-height: 1.7;

    margin-bottom: 22px;
}

.version {
    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 13px 15px;

    background: #f5f9f8;

    border-radius: 11px;

    margin-bottom: 20px;

    font-size: 13px;
}

.version span:first-child {
    color: #74827d;
}

.version strong {
    color: #064e8c;
}


/* ================================
   DOWNLOAD BUTTON
================================ */

.download-btn {
    width: 100%;

    display: block;

    text-align: center;

    text-decoration: none;

    padding: 14px 20px;

    border-radius: 11px;

    color: white;

    background: linear-gradient(
        90deg,
        #064e8c,
        #087f5b
    );

    font-size: 14px;

    font-weight: bold;

    border: none;

    cursor: pointer;

    transition: opacity .2s ease,
                transform .2s ease;
}

.download-btn:hover {
    opacity: .92;

    transform: translateY(-1px);
}

.download-btn.disabled {
    background: #aab8b3;

    cursor: not-allowed;

    pointer-events: none;
}


/* ================================
   INFORMATION
================================ */

.info-box {
    margin-top: 35px;

    padding: 20px 22px;

    background: #eaf5f1;

    border: 1px solid #d4e9e1;

    border-radius: 15px;

    color: #31554a;

    font-size: 13px;

    line-height: 1.7;
}

.info-box strong {
    color: #064e8c;
}


/* ================================
   FOOTER
================================ */

.footer {
    text-align: center;

    padding: 25px 15px;

    border-top: 1px solid #e2ebe8;

    color: #74827d;

    font-size: 12px;

    background: white;
}

.footer strong {
    color: #087f5b;
}


/* ================================
   MOBILE
================================ */

@media (max-width: 700px) {

    .header-inner {
        align-items: flex-start;
    }

    .header-label {
        display: none;
    }

    .hero h2 {
        font-size: 26px;
    }

    .apps-grid {
        grid-template-columns: 1fr;
    }

    .container {
        margin-top: 32px;
    }

    .app-card {
        padding: 24px;
    }

}

</style>

</head>

<body>


<!-- ================================
     HEADER
================================ -->

<header class="header">

    <div class="header-inner">

        <div class="brand">

            <div class="brand-logo">
                F
            </div>

            <div class="brand-text">

                <h1>
                    Flexi Educational Consult
                </h1>

                <p>
                    Learn Smart. Succeed Fast.
                </p>

            </div>

        </div>

        <div class="header-label">
            Apps Centre
        </div>

    </div>

</header>


<!-- ================================
     MAIN CONTENT
================================ -->

<main class="container">

    <section class="hero">

        <h2>
            Flexi Apps
        </h2>

        <p>
            Download the official Flexi JAMB CBT applications
            and prepare for your UTME examination wherever you are.
        </p>

    </section>


    <section class="apps-grid">


        <!-- ============================
             MAIN APP
        ============================= -->

        <article class="app-card">

            <div class="app-top">

                <div class="app-icon">
                    F
                </div>

                <div class="app-title">

                    <h3>
                        Flexi JAMB CBT App
                    </h3>

                    <span class="badge">
                        MAIN APP
                    </span>

                </div>

            </div>


            <p class="app-description">

                The complete Flexi JAMB CBT application
                for students preparing for UTME. Get the
                full CBT experience and access the main
                features of the Flexi JAMB preparation platform.

            </p>


            <div class="version">

                <span>
                    Current Version
                </span>

                <strong>
                    v1.2.0
                </strong>

            </div>


            <a
                href="?download=main"
                class="download-btn"
            >
                Download Main App
            </a>

        </article>



        <!-- ============================
             LITE APP
        ============================= -->

        <article class="app-card">

            <div class="app-top">

                <div class="app-icon">
                    L
                </div>

                <div class="app-title">

                    <h3>
                        Flexi JAMB CBT Lite
                    </h3>

                    <span class="badge">
                        LITE APP
                    </span>

                </div>

            </div>


            <p class="app-description">

                A lightweight version of the Flexi JAMB CBT
                application, designed to provide a simpler
                and faster experience for students.

            </p>


            <div class="version">

                <span>
                    Current Version
                </span>

                <strong>
                    Coming Soon
                </strong>

            </div>


            <a
                href="#"
                class="download-btn disabled"
            >
                Lite App Coming Soon
            </a>

        </article>


    </section>


    <div class="info-box">

        <strong>Important:</strong>
        Only download Flexi JAMB CBT applications from
        the official Flexi Educational Consult website.
        Before installing an APK, make sure your Android
        device allows installation from the appropriate
        source.

    </div>

</main>


<!-- ================================
     FOOTER
================================ -->

<footer class="footer">

    © <?php echo date('Y'); ?>
    <strong>Flexi Educational Consult</strong>.
    All Rights Reserved.

</footer>


</body>
</html>
