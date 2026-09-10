<?php
/*
|--------------------------------------------------------------------------
| FLEXI EDUCATIONAL CONSULT
| OFFICIAL APP DOWNLOAD CENTRE
|--------------------------------------------------------------------------
|
| Main App:
| Flexi JAMB CBT App v1.2.0
|
| Lite App:
| Coming Soon
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| APP DOWNLOAD LINKS
|--------------------------------------------------------------------------
*/

$mainAppDownload =
    'https://github.com/flexisystems2000/Flexi-JAMB-CBT-App-/releases/download/v1.2.0/app-debug.apk';


/*
|--------------------------------------------------------------------------
| DOWNLOAD REDIRECT HANDLER
|--------------------------------------------------------------------------
|
| When a visitor clicks:
|
| download-app.php?download=main
|
| they will be redirected to the official GitHub Release APK.
|
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

        exit('The Flexi JAMB CBT Lite App is currently unavailable.');

    }

    http_response_code(404);

    exit('Invalid application selected.');

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="theme-color"
        content="#087f5b"
    >

    <meta
        name="description"
        content="Download official Flexi JAMB CBT applications."
    >

    <title>
        Download Apps | Flexi Educational Consult
    </title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f4f8f7;

            color: #172a24;

            min-height: 100vh;

        }


        /* =====================================================
           BLUE-GREEN HEADER
        ===================================================== */

        .header {

            background:
                linear-gradient(
                    135deg,
                    #064e8c 0%,
                    #087f8c 48%,
                    #008f5a 100%
                );

            color: white;

            padding:
                18px 6%;

            box-shadow:
                0 4px 18px
                rgba(0, 0, 0, 0.15);

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

            width: 50px;

            height: 50px;

            border-radius: 13px;

            background:
                rgba(255,255,255,0.16);

            border:
                1px solid
                rgba(255,255,255,0.3);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 23px;

            font-weight: 800;

        }


        .brand-text h1 {

            font-size: 19px;

            line-height: 1.2;

            margin-bottom: 4px;

        }


        .brand-text p {

            font-size: 12px;

            opacity: 0.88;

        }


        .header-label {

            padding:
                9px 16px;

            border-radius: 30px;

            background:
                rgba(255,255,255,0.14);

            border:
                1px solid
                rgba(255,255,255,0.25);

            font-size: 13px;

            font-weight: 700;

        }


        /* =====================================================
           MAIN CONTAINER
        ===================================================== */

        .container {

            width: 92%;

            max-width: 1150px;

            margin:
                45px auto 70px;

        }


        /* =====================================================
           HERO
        ===================================================== */

        .hero {

            text-align: center;

            margin-bottom: 40px;

        }


        .hero h2 {

            font-size: 34px;

            margin-bottom: 11px;

            background:
                linear-gradient(
                    90deg,
                    #064e8c,
                    #087f5b
                );

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;

        }


        .hero p {

            max-width: 680px;

            margin: auto;

            color: #64756e;

            font-size: 15px;

            line-height: 1.7;

        }


        /* =====================================================
           APP GRID
        ===================================================== */

        .apps-grid {

            display: grid;

            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );

            gap: 25px;

        }


        /* =====================================================
           APP CARD
        ===================================================== */

        .app-card {

            position: relative;

            overflow: hidden;

            background: white;

            border:
                1px solid #e1ebe7;

            border-radius: 20px;

            padding: 30px;

            box-shadow:
                0 8px 30px
                rgba(0,0,0,0.07);

            transition:
                transform .2s ease,
                box-shadow .2s ease;

        }


        .app-card:hover {

            transform:
                translateY(-4px);

            box-shadow:
                0 15px 40px
                rgba(0,0,0,0.11);

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
                    #064e8c,
                    #008f5a
                );

        }


        /* =====================================================
           APP TOP
        ===================================================== */

        .app-top {

            display: flex;

            align-items: center;

            gap: 16px;

            margin-bottom: 22px;

        }


        .app-icon {

            width: 66px;

            height: 66px;

            flex-shrink: 0;

            border-radius: 17px;

            background:
                linear-gradient(
                    135deg,
                    #064e8c,
                    #008f5a
                );

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 27px;

            font-weight: 800;

            box-shadow:
                0 7px 18px
                rgba(0,95,100,0.22);

        }


        .app-title h3 {

            font-size: 20px;

            color: #16352c;

            margin-bottom: 7px;

        }


        .badge {

            display: inline-block;

            padding:
                5px 10px;

            border-radius: 20px;

            background: #e8f5f0;

            color: #087f5b;

            font-size: 11px;

            font-weight: 800;

        }


        /* =====================================================
           DESCRIPTION
        ===================================================== */

        .app-description {

            color: #64756e;

            font-size: 14px;

            line-height: 1.75;

            margin-bottom: 22px;

        }


        /* =====================================================
           VERSION
        ===================================================== */

        .version {

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                13px 15px;

            background: #f5f9f8;

            border-radius: 11px;

            margin-bottom: 20px;

            font-size: 13px;

        }


        .version-label {

            color: #74827d;

        }


        .version-number {

            color: #064e8c;

            font-weight: 800;

        }


        /* =====================================================
           DOWNLOAD BUTTON
        ===================================================== */

        .download-btn {

            width: 100%;

            display: block;

            text-align: center;

            text-decoration: none;

            padding:
                14px 20px;

            border-radius: 11px;

            color: white;

            background:
                linear-gradient(
                    90deg,
                    #064e8c,
                    #087f5b
                );

            font-size: 14px;

            font-weight: 800;

            transition:
                opacity .2s ease,
                transform .2s ease;

        }


        .download-btn:hover {

            opacity: .92;

            transform:
                translateY(-1px);

        }


        .download-btn.disabled {

            background: #aab8b3;

            cursor: not-allowed;

            pointer-events: none;

        }


        /* =====================================================
           DOWNLOAD NOTE
        ===================================================== */

        .download-note {

            margin-top: 10px;

            text-align: center;

            color: #87958f;

            font-size: 11px;

        }


        /* =====================================================
           INFORMATION BOX
        ===================================================== */

        .info-box {

            margin-top: 35px;

            padding:
                20px 22px;

            background: #eaf5f1;

            border:
                1px solid #d4e9e1;

            border-radius: 15px;

            color: #31554a;

            font-size: 13px;

            line-height: 1.75;

        }


        .info-box strong {

            color: #064e8c;

        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {

            text-align: center;

            padding:
                25px 15px;

            border-top:
                1px solid #e2ebe8;

            color: #74827d;

            font-size: 12px;

            background: white;

        }


        .footer strong {

            color: #087f5b;

        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 700px) {

            .header-inner {

                align-items: flex-start;

            }


            .header-label {

                display: none;

            }


            .hero h2 {

                font-size: 27px;

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


<!-- =========================================================
     HEADER
========================================================= -->

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



<!-- =========================================================
     MAIN CONTENT
========================================================= -->

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


        <!-- =================================================
             MAIN APP
        ================================================== -->

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

                The full Flexi JAMB CBT application
                for students preparing for UTME. Get
                the complete CBT experience and access
                the main features of the Flexi JAMB
                preparation platform.

            </p>


            <div class="version">

                <span class="version-label">
                    Current Version
                </span>

                <span class="version-number">
                    v1.2.0
                </span>

            </div>


            <a
                href="?download=main"
                class="download-btn"
            >
                Download Main App
            </a>


            <div class="download-note">

                Android APK • Official Release

            </div>

        </article>



        <!-- =================================================
             LITE APP
        ================================================== -->

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

                A lightweight version of the Flexi JAMB
                CBT application, designed to provide a
                simpler and faster experience for students
                with limited device resources.

            </p>


            <div class="version">

                <span class="version-label">
                    Current Version
                </span>

                <span class="version-number">
                    Coming Soon
                </span>

            </div>


            <a
                href="#"
                class="download-btn disabled"
            >
                Lite App Coming Soon
            </a>


            <div class="download-note">

                The Lite version will be available here.

            </div>

        </article>


    </section>



    <!-- =====================================================
         INFORMATION
    ====================================================== -->

    <div class="info-box">

        <strong>Important:</strong>

        Always download the Flexi JAMB CBT application
        from the official Flexi Educational Consult website
        or its official GitHub release.

        Make sure your Android device has enough storage
        before installing the application.

    </div>


</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    © <?php echo date('Y'); ?>

    <strong>
        Flexi Educational Consult
    </strong>

    . All Rights Reserved.

</footer>


</body>

</html>
