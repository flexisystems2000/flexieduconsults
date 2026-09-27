<?php
/**
 * ============================================================
 * FLEXI EDUCATIONAL CONSULT
 * STUDY — JAMB PAST QUESTIONS / CBT SIMULATOR
 * ============================================================
 *
 * SOURCE:
 * Flexi JAMB CBT App repository
 * https://github.com/flexisystems2000/Flexi-JAMB-CBT-App-
 *
 * QUESTION BANK:
 * question_bank/*.json
 *
 * The JSON structure used by the CBT repository includes:
 *   question
 *   options
 *   answer
 *   year
 *
 * This page does NOT create a second question database.
 * It reads the actual Flexi CBT App question-bank files.
 * ============================================================
 */

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| BASIC CONFIGURATION
|--------------------------------------------------------------------------
*/

$repoOwner = 'flexisystems2000';
$repoName  = 'Flexi-JAMB-CBT-App-';
$repoBranch = 'main';

$rawBaseUrl =
    'https://raw.githubusercontent.com/' .
    $repoOwner . '/' .
    $repoName . '/' .
    $repoBranch . '/question_bank/';

$repoUrl =
    'https://github.com/' .
    $repoOwner . '/' .
    $repoName;


/*
|--------------------------------------------------------------------------
| SUBJECT MAP
|--------------------------------------------------------------------------
|
| These filenames are taken from the actual repository's past-question
| subject mapping.
|
*/

$subjectMap = [

    'use_of_english' => [
        'label' => 'English Language',
        'file'  => 'use_of_english.json'
    ],

    'accounting' => [
        'label' => 'Accounting',
        'file'  => 'accounting.json'
    ],

    'arabic' => [
        'label' => 'Arabic',
        'file'  => 'arabic.json'
    ],

    'biology' => [
        'label' => 'Biology',
        'file'  => 'biology.json'
    ],

    'chemistry' => [
        'label' => 'Chemistry',
        'file'  => 'chemistry.json'
    ],

    'christian_religious_studies' => [
        'label' => 'Christian Religious Studies (CRS)',
        'file'  => 'christian_religious_studies__crs_.json'
    ],

    'commerce' => [
        'label' => 'Commerce',
        'file'  => 'commerce.json'
    ],

    'computer_studies' => [
        'label' => 'Computer Studies',
        'file'  => 'computer_studies.json'
    ],

    'economics' => [
        'label' => 'Economics',
        'file'  => 'economics.json'
    ],

    'fine_art' => [
        'label' => 'Fine Arts',
        'file'  => 'fine_art.json'
    ],

    'government' => [
        'label' => 'Government',
        'file'  => 'government.json'
    ],

    'literature_in_english' => [
        'label' => 'Literature In English',
        'file'  => 'literature_in_english.json'
    ],

    'mathematics' => [
        'label' => 'Mathematics',
        'file'  => 'mathematics.json'
    ],

    'physics' => [
        'label' => 'Physics',
        'file'  => 'physics.json'
    ],

    'yoruba' => [
        'label' => 'Yoruba',
        'file'  => 'yoruba.json'
    ]
];


/*
|--------------------------------------------------------------------------
| HTML ESCAPER
|--------------------------------------------------------------------------
*/

function esc(string $value): string
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| JSON FOR JAVASCRIPT
|--------------------------------------------------------------------------
*/

$subjectMapForJs = [];

foreach ($subjectMap as $key => $item) {
    $subjectMapForJs[$key] = [
        'label' => $item['label'],
        'file'  => $item['file']
    ];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >

    <meta
        name="theme-color"
        content="#0b5ed7"
    >

    <title>
        Flexi Educational Consult | Study Past Questions
    </title>


    <!-- =====================================================
         MATHJAX
         Borrowed from the Flexi JAMB CBT App repository
         ===================================================== -->

    <script>
        window.MathJax = {
            tex: {
                inlineMath: [
                    ['\\(', '\\)'],
                    ['\\( ', ' \\)']
                ],
                displayMath: [
                    ['\\[', '\\]'],
                    ['\\[ ', ' \\]']
                ],
                processEscapes: true,
                processEnvironments: true
            },

            options: {
                skipHtmlTags: [
                    'script',
                    'noscript',
                    'style',
                    'textarea',
                    'pre',
                    'code'
                ]
            },

            svg: {
                fontCache: 'global'
            },

            startup: {
                typeset: false
            }
        };
    </script>

    <script
        id="MathJax-script"
        async
        src="https://raw.githubusercontent.com/flexisystems2000/Flexi-JAMB-CBT-App-/main/tex-mml-chtml.js"
    ></script>


    <style>

        /* =====================================================
           ROOT
           ===================================================== */

        :root {

            --flexi-blue: #0b5ed7;
            --flexi-blue-dark: #0649aa;

            --flexi-green: #008f4c;
            --flexi-green-dark: #006b39;

            --bg: #f4f7fb;
            --card: #ffffff;

            --text: #172033;
            --muted: #687386;

            --border: #e5eaf1;

            --shadow:
                0 8px 25px rgba(18, 38, 63, 0.08);

            --danger: #d92d20;
            --success: #16844a;

            --radius: 18px;
        }


        /* =====================================================
           RESET
           ===================================================== */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {

            font-family:
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                sans-serif;

            background: var(--bg);

            color: var(--text);

            min-height: 100vh;

            line-height: 1.5;
        }

        button,
        select {
            font: inherit;
        }

        button {
            cursor: pointer;
        }


        /* =====================================================
           HEADER
           ===================================================== */

        .flexi-header {

            position: sticky;

            top: 0;

            z-index: 5000;

            background:
                linear-gradient(
                    115deg,
                    #0757c9 0%,
                    #0874d9 48%,
                    #008c4b 100%
                );

            color: #ffffff;

            box-shadow:
                0 5px 18px rgba(0, 0, 0, 0.12);
        }

        .header-inner {

            width: min(
                1180px,
                calc(100% - 30px)
            );

            margin: auto;

            min-height: 72px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;
        }

        .brand {

            display: flex;

            align-items: center;

            gap: 11px;

            text-decoration: none;

            color: #ffffff;

            min-width: 0;
        }

        .brand-logo {

            width: 42px;
            height: 42px;

            border-radius: 11px;

            object-fit: contain;

            background: rgba(255,255,255,.16);

            padding: 4px;
        }

        .brand-text {

            min-width: 0;
        }

        .brand-title {

            font-size: 1rem;

            font-weight: 800;

            letter-spacing: .1px;

            white-space: nowrap;
        }

        .brand-subtitle {

            font-size: .7rem;

            opacity: .85;

            margin-top: 1px;

            white-space: nowrap;
        }


        /* =====================================================
           HEADER NAV
           ===================================================== */

        .desktop-nav {

            display: flex;

            align-items: center;

            gap: 5px;
        }

        .desktop-nav a {

            color: rgba(255,255,255,.96);

            text-decoration: none;

            font-size: .86rem;

            font-weight: 650;

            padding: 9px 11px;

            border-radius: 9px;

            transition:
                background .2s ease,
                transform .2s ease;
        }

        .desktop-nav a:hover {

            background:
                rgba(255,255,255,.13);

            transform: translateY(-1px);
        }


        /* =====================================================
           SVG ICON
           ===================================================== */

        .svg-icon {

            width: 20px;
            height: 20px;

            display: inline-block;

            fill: none;

            stroke: currentColor;

            stroke-width: 2;

            stroke-linecap: round;

            stroke-linejoin: round;
        }


        /* =====================================================
           MOBILE HEADER
           ===================================================== */

        .mobile-menu-btn {

            width: 42px;
            height: 42px;

            border: 0;

            border-radius: 11px;

            background:
                rgba(255,255,255,.12);

            color: #ffffff;

            display: none;

            align-items: center;

            justify-content: center;
        }


        /* =====================================================
           MAIN
           ===================================================== */

        .page {

            width: min(
                1180px,
                calc(100% - 30px)
            );

            margin: auto;

            padding:
                25px
                0
                55px;
        }


        /* =====================================================
           PAGE INTRO
           ===================================================== */

        .page-intro {

            margin-bottom: 22px;
        }

        .back-link {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            text-decoration: none;

            color: var(--flexi-blue);

            font-size: .85rem;

            font-weight: 700;

            margin-bottom: 13px;
        }

        .page-title {

            font-size:
                clamp(
                    1.45rem,
                    3vw,
                    2rem
                );

            font-weight: 850;

            color: #14213d;
        }

        .page-description {

            color: var(--muted);

            margin-top: 5px;

            font-size: .93rem;
        }


        /* =====================================================
           STUDY PANEL
           ===================================================== */

        .study-panel {

            background: var(--card);

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            box-shadow:
                var(--shadow);

            padding: 22px;
        }

        .panel-heading {

            display: flex;

            align-items: center;

            gap: 11px;

            margin-bottom: 20px;
        }

        .panel-heading-icon {

            width: 43px;
            height: 43px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #ffffff;

            background:
                linear-gradient(
                    135deg,
                    var(--flexi-blue),
                    var(--flexi-green)
                );
        }

        .panel-heading h2 {

            font-size: 1.05rem;

            font-weight: 800;
        }

        .panel-heading p {

            font-size: .78rem;

            color: var(--muted);

            margin-top: 2px;
        }


        /* =====================================================
           FILTER GRID
           ===================================================== */

        .filter-grid {

            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 17px;
        }

        .form-group label {

            display: block;

            font-size: .82rem;

            font-weight: 750;

            margin-bottom: 7px;

            color: #344054;
        }

        .select-wrap {

            position: relative;
        }

        .select-wrap .svg-icon {

            position: absolute;

            right: 14px;

            top: 50%;

            transform:
                translateY(-50%);

            width: 17px;
            height: 17px;

            pointer-events: none;

            color: #667085;
        }

        .form-control {

            width: 100%;

            min-height: 48px;

            padding:
                11px
                42px
                11px
                14px;

            border:
                1px solid #d8dee8;

            border-radius: 12px;

            background: #ffffff;

            color: var(--text);

            outline: none;

            appearance: none;

            font-size: .9rem;

            transition:
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .form-control:focus {

            border-color:
                var(--flexi-blue);

            box-shadow:
                0 0 0 3px
                rgba(11,94,215,.1);
        }


        /* =====================================================
           START BUTTON
           ===================================================== */

        .start-area {

            margin-top: 20px;

            display: flex;

            justify-content: flex-end;
        }

        .start-btn {

            border: 0;

            color: #ffffff;

            background:
                linear-gradient(
                    135deg,
                    var(--flexi-blue),
                    var(--flexi-green)
                );

            border-radius: 12px;

            min-height: 48px;

            padding:
                0
                25px;

            font-weight: 800;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 9px;

            box-shadow:
                0 7px 18px
                rgba(11,94,215,.18);

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .start-btn:hover {

            transform: translateY(-1px);

            box-shadow:
                0 10px 22px
                rgba(11,94,215,.23);
        }


        /* =====================================================
           STATUS
           ===================================================== */

        .status-box {

            margin-top: 15px;

            padding: 12px 14px;

            border-radius: 11px;

            font-size: .84rem;

            display: none;
        }

        .status-box.show {
            display: block;
        }

        .status-loading {

            color: #0757c9;

            background:
                #edf5ff;
        }

        .status-error {

            color: #a61b14;

            background:
                #fff0ee;
        }

        .status-success {

            color: #126b3d;

            background:
                #ebf8f1;
        }


        /* =====================================================
           SIMULATOR
           ===================================================== */

        #simulatorSection {

            display: none;

            margin-top: 25px;
        }

        #simulatorSection.active {
            display: block;
        }

        .simulator-top {

            background:
                linear-gradient(
                    120deg,
                    #0757c9,
                    #008c4b
                );

            color: #ffffff;

            border-radius:
                18px;

            padding: 17px;

            box-shadow:
                var(--shadow);
        }

        .simulator-top-row {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 15px;
        }

        .simulator-title {

            font-size: 1rem;

            font-weight: 850;
        }

        .simulator-meta {

            font-size: .76rem;

            opacity: .88;

            margin-top: 3px;
        }

        .question-counter {

            font-weight: 800;

            font-size: .82rem;

            white-space: nowrap;
        }

        .progress-track {

            height: 6px;

            border-radius: 10px;

            background:
                rgba(255,255,255,.2);

            margin-top: 15px;

            overflow: hidden;
        }

        .progress-bar {

            height: 100%;

            width: 0%;

            background: #ffffff;

            border-radius: inherit;

            transition: width .25s ease;
        }


        /* =====================================================
           QUESTION CARD
           ===================================================== */

        .question-card {

            background:
                var(--card);

            border:
                1px solid var(--border);

            border-radius:
                18px;

            box-shadow:
                var(--shadow);

            margin-top: 15px;

            padding: 23px;
        }

        .question-label {

            color:
                var(--flexi-green);

            font-size: .8rem;

            font-weight: 850;

            margin-bottom: 10px;
        }

        .question-text {

            font-size:
                clamp(
                    1rem,
                    2vw,
                    1.13rem
                );

            font-weight: 650;

            line-height: 1.65;

            color: var(--text);

            overflow-wrap: anywhere;
        }

        .options {

            display: flex;

            flex-direction: column;

            gap: 11px;

            margin-top: 21px;
        }

        .option-btn {

            width: 100%;

            text-align: left;

            border:
                1.5px solid #dfe4eb;

            background: #ffffff;

            color: var(--text);

            border-radius: 12px;

            padding:
                13px
                15px;

            display: flex;

            align-items: flex-start;

            gap: 11px;

            font-size: .91rem;

            line-height: 1.5;

            transition:
                border .18s ease,
                background .18s ease,
                transform .18s ease;
        }

        .option-btn:hover {

            border-color:
                var(--flexi-blue);

            transform:
                translateY(-1px);
        }

        .option-letter {

            flex:
                0 0 31px;

            width: 31px;
            height: 31px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                #eef3f9;

            color:
                #344054;

            font-weight: 850;

            font-size: .8rem;
        }

        .option-content {

            flex: 1;
        }

        .option-btn.selected {

            border-color:
                var(--flexi-blue);

            background:
                #edf5ff;
        }

        .option-btn.correct {

            border-color:
                #16844a;

            background:
                #eaf8f0;

            color:
                #125d37;
        }

        .option-btn.wrong {

            border-color:
                #d92d20;

            background:
                #fff0ee;

            color:
                #a61b14;
        }

        .answer-badge {

            display: inline-flex;

            margin-left: auto;

            padding:
                3px 7px;

            border-radius: 6px;

            font-size: .66rem;

            font-weight: 850;

            text-transform: uppercase;
        }

        .answer-badge.correct {

            background: #16844a;

            color: #ffffff;
        }

        .answer-badge.wrong {

            background: #d92d20;

            color: #ffffff;
        }


        /* =====================================================
           EXPLANATION
           ===================================================== */

        .answer-info {

            display: none;

            margin-top: 17px;

            border-radius: 12px;

            padding: 13px 14px;

            background:
                #f4f8fc;

            border:
                1px solid #dfe7f1;

            font-size: .83rem;
        }

        .answer-info.visible {
            display: block;
        }

        .answer-info strong {
            color:
                var(--flexi-green);
        }


        /* =====================================================
           NAVIGATION
           ===================================================== */

        .simulator-controls {

            display: flex;

            gap: 10px;

            margin-top: 15px;
        }

        .control-btn {

            flex: 1;

            min-height: 46px;

            border-radius: 12px;

            border: 1px solid #dbe2eb;

            background: #ffffff;

            color: #344054;

            font-weight: 800;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;
        }

        .control-btn.primary {

            border: 0;

            color: #ffffff;

            background:
                linear-gradient(
                    135deg,
                    var(--flexi-blue),
                    var(--flexi-green)
                );
        }

        .control-btn:disabled {

            opacity: .45;

            cursor: not-allowed;
        }


        /* =====================================================
           RESULT CARD
           ===================================================== */

        .result-card {

            display: none;

            margin-top: 15px;

            background: #ffffff;

            border:
                1px solid var(--border);

            border-radius: 18px;

            padding: 23px;

            text-align: center;

            box-shadow:
                var(--shadow);
        }

        .result-card.active {
            display: block;
        }

        .result-score {

            font-size: 2.2rem;

            font-weight: 900;

            color:
                var(--flexi-green);
        }

        .result-title {

            font-size: 1.1rem;

            font-weight: 850;

            margin-top: 3px;
        }

        .result-detail {

            color: var(--muted);

            font-size: .84rem;

            margin-top: 4px;
        }


        /* =====================================================
           CALCULATOR
           ===================================================== */

        #calculatorOverlay {

            position: fixed;

            inset: 0;

            background:
                rgba(0,0,0,.72);

            backdrop-filter:
                blur(4px);

            display: none;

            align-items: center;

            justify-content: center;

            z-index: 10000;

            padding: 18px;
        }

        #calculatorOverlay.active {
            display: flex;
        }

        .calculator {

            width:
                min(
                    350px,
                    100%
                );

            background:
                #080a09;

            border:
                1px solid #1f2923;

            border-radius: 23px;

            overflow: hidden;

            box-shadow:
                0 25px 60px
                rgba(0,0,0,.4);
        }

        .calculator-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                13px 16px;

            background:
                #0d110f;

            color: #ffffff;
        }

        .calculator-header strong {
            font-size: .9rem;
        }

        .calc-close {

            width: 31px;
            height: 31px;

            border: 0;

            border-radius: 8px;

            background:
                #18201b;

            color: #ffffff;
        }

        .calc-display {

            min-height: 85px;

            padding:
                20px 17px;

            display: flex;

            align-items: flex-end;

            justify-content: flex-end;

            color:
                #ffffff;

            font-size: 2rem;

            font-weight: 700;

            overflow-wrap: anywhere;

            text-align: right;
        }

        .calc-grid {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 7px;

            padding: 9px;
        }

        .calc-key {

            min-height: 55px;

            border: 0;

            border-radius: 12px;

            background:
                #171d19;

            color: #ffffff;

            font-size: 1rem;

            font-weight: 750;
        }

        .calc-key:hover {
            background: #202a23;
        }

        .calc-key.operator {

            background:
                #0e4d2d;

            color:
                #8ff0bb;
        }

        .calc-key.equal {

            background:
                #008f4c;

            color:
                #ffffff;
        }

        .calc-key.clear {

            color:
                #ff8179;
        }


        /* =====================================================
           APP PROMOTION
           ===================================================== */

        .app-promotion {

            margin-top: 30px;

            border-radius: 18px;

            overflow: hidden;

            background: #ffffff;

            border:
                1px solid var(--border);

            box-shadow:
                var(--shadow);
        }

        .app-promotion a {

            display: block;

            text-decoration: none;
        }

        .app-promotion img {

            display: block;

            width: 100%;

            height: auto;

            max-height: 310px;

            object-fit: cover;
        }


        /* =====================================================
           FOOTER
           ===================================================== */

        .flexi-footer {

            background:
                linear-gradient(
                    120deg,
                    #063f99,
                    #0757c9,
                    #007b42
                );

            color: #ffffff;

            margin-top: 0;
        }

        .footer-inner {

            width: min(
                1180px,
                calc(100% - 30px)
            );

            margin: auto;

            padding:
                28px
                0;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;
        }

        .footer-brand {

            font-weight: 850;

            font-size: .92rem;
        }

        .footer-copy {

            font-size: .74rem;

            opacity: .8;

            margin-top: 3px;
        }

        .footer-links {

            display: flex;

            flex-wrap: wrap;

            gap: 15px;
        }

        .footer-links a {

            color: #ffffff;

            text-decoration: none;

            font-size: .77rem;

            opacity: .9;
        }


        /* =====================================================
           RESPONSIVE
           ===================================================== */

        @media (max-width: 760px) {

            .desktop-nav {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .header-inner {
                min-height: 64px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .start-area {
                justify-content: stretch;
            }

            .start-btn {
                width: 100%;
            }

            .footer-inner {
                flex-direction: column;

                align-items: flex-start;
            }
        }


        @media (min-width: 761px) {

            .page {
                padding-top: 35px;
            }

            .study-panel {
                padding: 27px;
            }

            .question-card {
                padding: 30px;
            }
        }


        @media (max-width: 520px) {

            .page {
                width:
                    calc(100% - 22px);

                padding-top: 18px;
            }

            .header-inner {
                width:
                    calc(100% - 22px);
            }

            .brand-title {
                font-size: .88rem;
            }

            .brand-subtitle {
                display: none;
            }

            .study-panel {
                padding: 17px;
            }

            .question-card {
                padding: 18px;
            }

            .simulator-controls {
                flex-direction: column;
            }

            .simulator-top-row {
                align-items: flex-start;
            }

            .app-promotion img {
                max-height: none;
            }
        }

    </style>

</head>


<body>


<!-- =========================================================
     HEADER
     ========================================================= -->

<header class="flexi-header">

    <div class="header-inner">

        <a
            href="index.php"
            class="brand"
        >

            <img
                src="assets/logo.png"
                class="brand-logo"
                alt="Flexi Educational Consult"
                onerror="this.style.display='none'"
            >

            <div class="brand-text">

                <div class="brand-title">
                    FLEXI EDUCATIONAL CONSULT
                </div>

                <div class="brand-subtitle">
                    JAMB • CBT • Educational Resources
                </div>

            </div>

        </a>


        <nav
            class="desktop-nav"
            aria-label="Main navigation"
        >

            <a href="index.php">
                Home
            </a>

            <a href="study.php">
                Study
            </a>

            <a href="news.php">
                News
            </a>

        </nav>


        <button
            class="mobile-menu-btn"
            id="mobileMenuBtn"
            type="button"
            aria-label="Open menu"
        >

            <svg
                class="svg-icon"
                viewBox="0 0 24 24"
            >
                <path d="M4 6h16"></path>
                <path d="M4 12h16"></path>
                <path d="M4 18h16"></path>
            </svg>

        </button>

    </div>

</header>


<!-- =========================================================
     MAIN PAGE
     ========================================================= -->

<main class="page">


    <section class="page-intro">

        <a
            href="index.php"
            class="back-link"
        >

            <svg
                class="svg-icon"
                viewBox="0 0 24 24"
            >
                <path d="M19 12H5"></path>
                <path d="m12 19-7-7 7-7"></path>
            </svg>

            Back to Home

        </a>


        <h1 class="page-title">
            Study Past Questions
        </h1>

        <p class="page-description">
            Select a subject and examination year to practise
            questions from the Flexi JAMB CBT question bank.
        </p>

    </section>



    <!-- =====================================================
         SELECTION PANEL
         ===================================================== -->

    <section class="study-panel">

        <div class="panel-heading">

            <div class="panel-heading-icon">

                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                >
                    <path d="M4 5h16v14H4z"></path>
                    <path d="M8 9h8"></path>
                    <path d="M8 13h5"></path>
                </svg>

            </div>

            <div>

                <h2>
                    Past Questions Practice
                </h2>

                <p>
                    Choose your subject and year
                </p>

            </div>

        </div>


        <div class="filter-grid">


            <!-- SUBJECT -->

            <div class="form-group">

                <label for="subjectSelect">
                    Subject
                </label>

                <div class="select-wrap">

                    <select
                        id="subjectSelect"
                        class="form-control"
                    >

                        <option value="">
                            Select Subject
                        </option>

                        <?php foreach ($subjectMap as $key => $subject): ?>

                            <option
                                value="<?= esc($key) ?>"
                            >
                                <?= esc($subject['label']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>


                    <svg
                        class="svg-icon"
                        viewBox="0 0 24 24"
                    >
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>

                </div>

            </div>



            <!-- EXAMINATION -->

            <div class="form-group">

                <label for="examType">

                    Examination Type

                </label>

                <div class="select-wrap">

                    <select
                        id="examType"
                        class="form-control"
                    >

                        <option value="JAMB">
                            JAMB
                        </option>

                    </select>

                    <svg
                        class="svg-icon"
                        viewBox="0 0 24 24"
                    >
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>

                </div>

            </div>



            <!-- YEAR -->

            <div class="form-group">

                <label for="yearSelect">

                    Examination Year

                </label>

                <div class="select-wrap">

                    <select
                        id="yearSelect"
                        class="form-control"
                        disabled
                    >

                        <option value="">
                            Select subject first
                        </option>

                    </select>

                    <svg
                        class="svg-icon"
                        viewBox="0 0 24 24"
                    >
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>

                </div>

            </div>



            <!-- QUESTION TYPE -->

            <div class="form-group">

                <label for="questionType">

                    Question Type

                </label>

                <div class="select-wrap">

                    <select
                        id="questionType"
                        class="form-control"
                    >

                        <option value="Objectives">
                            Objectives
                        </option>

                    </select>

                    <svg
                        class="svg-icon"
                        viewBox="0 0 24 24"
                    >
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>

                </div>

            </div>


        </div>


        <div class="start-area">

            <button
                type="button"
                id="startBtn"
                class="start-btn"
            >

                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                >
                    <path d="M8 5v14l11-7z"></path>
                </svg>

                Start Practice

            </button>

        </div>


        <div
            id="statusBox"
            class="status-box"
        ></div>

    </section>



    <!-- =====================================================
         CBT SIMULATOR
         ===================================================== -->

    <section
        id="simulatorSection"
    >


        <div class="simulator-top">

            <div class="simulator-top-row">

                <div>

                    <div
                        class="simulator-title"
                        id="simulatorSubject"
                    >
                        JAMB Practice
                    </div>

                    <div
                        class="simulator-meta"
                        id="simulatorMeta"
                    >
                        Flexi CBT Past Questions
                    </div>

                </div>


                <div
                    class="question-counter"
                    id="questionCounter"
                >
                    Question 1 of 1
                </div>

            </div>


            <div class="progress-track">

                <div
                    class="progress-bar"
                    id="progressBar"
                ></div>

            </div>

        </div>



        <div class="question-card">

            <div
                class="question-label"
                id="questionLabel"
            >
                QUESTION 1
            </div>


            <div
                class="question-text"
                id="questionText"
            >
                Loading question...
            </div>


            <div
                class="options"
                id="optionsContainer"
            ></div>


            <div
                class="answer-info"
                id="answerInfo"
            ></div>

        </div>



        <div class="simulator-controls">

            <button
                type="button"
                class="control-btn"
                id="previousBtn"
            >

                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                >
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>

                Previous

            </button>


            <button
                type="button"
                class="control-btn"
                id="calculatorBtn"
            >

                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                >
                    <rect
                        x="4"
                        y="3"
                        width="16"
                        height="18"
                        rx="2"
                    ></rect>

                    <path d="M8 7h8"></path>
                    <path d="M8 11h2"></path>
                    <path d="M14 11h2"></path>
                    <path d="M8 15h2"></path>
                    <path d="M14 15h2"></path>
                </svg>

                Calculator

            </button>


            <button
                type="button"
                class="control-btn primary"
                id="nextBtn"
            >

                Next

                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                >
                    <path d="m9 18 6-6-6-6"></path>
                </svg>

            </button>

        </div>



        <!-- RESULT -->

        <div
            class="result-card"
            id="resultCard"
        >

            <div
                class="result-score"
                id="resultScore"
            >
                0%
            </div>

            <div class="result-title">
                Practice Completed
            </div>

            <div
                class="result-detail"
                id="resultDetail"
            >
                0 correct out of 0
            </div>

            <div
                style="margin-top:17px;"
            >

                <button
                    type="button"
                    class="start-btn"
                    id="restartBtn"
                >

                    <svg
                        class="svg-icon"
                        viewBox="0 0 24 24"
                    >
                        <path d="M20 11a8.1 8.1 0 0 0-15.5-2"></path>
                        <path d="M4 5v4h4"></path>
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2"></path>
                        <path d="M20 19v-4h-4"></path>
                    </svg>

                    Practise Again

                </button>

            </div>

        </div>


    </section>



    <!-- =====================================================
         FLEXI APP ADVERTISEMENT
         ===================================================== -->

    <section class="app-promotion">

        <a
            href="#"
            id="appPromotionLink"
            aria-label="Download Flexi App"
        >

            <img
                src="assets/flexi-app.png"
                alt="Download the Flexi JAMB CBT App"
                loading="lazy"
                onerror="
                    this.closest('.app-promotion').style.display='none';
                "
            >

        </a>

    </section>


</main>



<!-- =========================================================
     CALCULATOR
     ========================================================= -->

<div
    id="calculatorOverlay"
    aria-hidden="true"
>

    <div class="calculator">

        <div class="calculator-header">

            <strong>
                Flexi Calculator
            </strong>

            <button
                type="button"
                class="calc-close"
                id="calculatorClose"
                aria-label="Close calculator"
            >

                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                    style="width:17px;height:17px;"
                >
                    <path d="M6 6l12 12"></path>
                    <path d="M18 6 6 18"></path>
                </svg>

            </button>

        </div>


        <div
            class="calc-display"
            id="calcDisplay"
        >
            0
        </div>


        <div class="calc-grid">

            <button
                class="calc-key clear"
                data-calc="AC"
            >
                AC
            </button>

            <button
                class="calc-key"
                data-calc="DEL"
            >
                DEL
            </button>

            <button
                class="calc-key operator"
                data-calc="/"
            >
                ÷
            </button>

            <button
                class="calc-key operator"
                data-calc="*"
            >
                ×
            </button>


            <button
                class="calc-key"
                data-calc="7"
            >
                7
            </button>

            <button
                class="calc-key"
                data-calc="8"
            >
                8
            </button>

            <button
                class="calc-key"
                data-calc="9"
            >
                9
            </button>

            <button
                class="calc-key operator"
                data-calc="-"
            >
                −
            </button>


            <button
                class="calc-key"
                data-calc="4"
            >
                4
            </button>

            <button
                class="calc-key"
                data-calc="5"
            >
                5
            </button>

            <button
                class="calc-key"
                data-calc="6"
            >
                6
            </button>

            <button
                class="calc-key operator"
                data-calc="+"
            >
                +
            </button>


            <button
                class="calc-key"
                data-calc="1"
            >
                1
            </button>

            <button
                class="calc-key"
                data-calc="2"
            >
                2
            </button>

            <button
                class="calc-key"
                data-calc="3"
            >
                3
            </button>

            <button
                class="calc-key equal"
                data-calc="="
                style="grid-row: span 2;"
            >
                =
            </button>


            <button
                class="calc-key"
                data-calc="0"
            >
                0
            </button>

            <button
                class="calc-key"
                data-calc="."
            >
                .
            </button>

        </div>

    </div>

</div>



<!-- =========================================================
     FOOTER
     ========================================================= -->

<footer class="flexi-footer">

    <div class="footer-inner">

        <div>

            <div class="footer-brand">
                FLEXI EDUCATIONAL CONSULT
            </div>

            <div class="footer-copy">
                JAMB preparation, CBT practice and educational resources.
            </div>

        </div>


        <div class="footer-links">

            <a href="index.php">
                Home
            </a>

            <a href="study.php">
                Study
            </a>

            <a href="news.php">
                News
            </a>

        </div>

    </div>

</footer>



<script>

    /* =========================================================
       CONFIGURATION
       ========================================================= */

    const SUBJECT_MAP =
        <?= json_encode(
            $subjectMapForJs,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE
        ) ?>;


    const RAW_BASE_URL =
        <?= json_encode($rawBaseUrl) ?>;


    const REPOSITORY_URL =
        <?= json_encode($repoUrl) ?>;


    /* =========================================================
       STATE
       ========================================================= */

    let currentQuestions = [];

    let currentIndex = 0;

    let selectedAnswers = {};

    let completedAnswers = {};

    let activeSubject = '';

    let activeYear = '';

    let calculationValue = '';


    /* =========================================================
       ELEMENTS
       ========================================================= */

    const subjectSelect =
        document.getElementById(
            'subjectSelect'
        );

    const yearSelect =
        document.getElementById(
            'yearSelect'
        );

    const startBtn =
        document.getElementById(
            'startBtn'
        );

    const statusBox =
        document.getElementById(
            'statusBox'
        );

    const simulatorSection =
        document.getElementById(
            'simulatorSection'
        );

    const simulatorSubject =
        document.getElementById(
            'simulatorSubject'
        );

    const simulatorMeta =
        document.getElementById(
            'simulatorMeta'
        );

    const questionCounter =
        document.getElementById(
            'questionCounter'
        );

    const progressBar =
        document.getElementById(
            'progressBar'
        );

    const questionLabel =
        document.getElementById(
            'questionLabel'
        );

    const questionText =
        document.getElementById(
            'questionText'
        );

    const optionsContainer =
        document.getElementById(
            'optionsContainer'
        );

    const answerInfo =
        document.getElementById(
            'answerInfo'
        );

    const previousBtn =
        document.getElementById(
            'previousBtn'
        );

    const nextBtn =
        document.getElementById(
            'nextBtn'
        );

    const resultCard =
        document.getElementById(
            'resultCard'
        );

    const resultScore =
        document.getElementById(
            'resultScore'
        );

    const resultDetail =
        document.getElementById(
            'resultDetail'
        );


    /* =========================================================
       STATUS
       ========================================================= */

    function setStatus(
        message,
        type = 'loading'
    ) {

        statusBox.textContent =
            message;

        statusBox.className =
            'status-box show status-' +
            type;
    }


    function clearStatus() {

        statusBox.textContent = '';

        statusBox.className =
            'status-box';
    }


    /* =========================================================
       FETCH ACTUAL FLEXI QUESTION BANK
       ========================================================= */

    async function fetchQuestionBank(
        subjectKey
    ) {

        const subject =
            SUBJECT_MAP[subjectKey];

        if (!subject) {

            throw new Error(
                'The selected subject is not available.'
            );
        }


        const url =
            RAW_BASE_URL +
            encodeURIComponent(
                subject.file
            );


        const response =
            await fetch(
                url,
                {
                    cache: 'no-store'
                }
            );


        if (!response.ok) {

            throw new Error(
                'The Flexi CBT question bank could not be loaded. HTTP ' +
                response.status
            );
        }


        const data =
            await response.json();


        /*
         * The repository currently stores the question bank
         * as an array. The extra checks make this tolerant of
         * a future object wrapper without changing the source.
         */

        if (Array.isArray(data)) {

            return data;

        }


        if (
            data &&
            Array.isArray(data.questions)
        ) {

            return data.questions;

        }


        if (
            data &&
            Array.isArray(data.data)
        ) {

            return data.data;

        }


        if (
            data &&
            Array.isArray(data.items)
        ) {

            return data.items;

        }


        throw new Error(
            'The selected question bank has an unsupported format.'
        );
    }


    /* =========================================================
       NORMALISE QUESTION
       ========================================================= */

    function normaliseQuestion(
        item
    ) {

        const question =
            String(
                item?.question ??
                item?.text ??
                ''
            ).trim();


        let options =
            item?.options;


        if (!Array.isArray(options)) {

            options = [];

        }


        options =
            options.map(
                option =>
                    String(option)
            );


        let answer =
            item?.answer ??
            item?.correctAnswer ??
            item?.correct ??
            '';


        answer =
            String(answer)
                .trim()
                .toUpperCase();


        const year =
            String(
                item?.year ??
                ''
            ).trim();


        return {
            question,
            options,
            answer,
            year,
            raw: item
        };
    }


    /* =========================================================
       EXTRACT YEARS
       ========================================================= */

    function getAvailableYears(
        questions
    ) {

        const years =
            new Set();


        questions.forEach(
            item => {

                const question =
                    normaliseQuestion(
                        item
                    );


                if (
                    question.year &&
                    /^\d{4}$/.test(
                        question.year
                    )
                ) {

                    years.add(
                        question.year
                    );

                }

            }
        );


        return Array.from(
            years
        ).sort(
            (a, b) =>
                Number(b) -
                Number(a)
        );
    }


    /* =========================================================
       SUBJECT CHANGED
       ========================================================= */

    subjectSelect.addEventListener(
        'change',
        async function () {

            const subjectKey =
                this.value;


            yearSelect.innerHTML =
                '<option value="">Loading years...</option>';

            yearSelect.disabled =
                true;


            clearStatus();


            if (!subjectKey) {

                yearSelect.innerHTML =
                    '<option value="">Select subject first</option>';

                return;
            }


            try {

                setStatus(
                    'Loading the available years from the Flexi JAMB CBT question bank...',
                    'loading'
                );


                const questions =
                    await fetchQuestionBank(
                        subjectKey
                    );


                const years =
                    getAvailableYears(
                        questions
                    );


                yearSelect.innerHTML =
                    '';


                const allOption =
                    document.createElement(
                        'option'
                    );

                allOption.value =
                    'ALL';

                allOption.textContent =
                    'All Available Years';

                yearSelect.appendChild(
                    allOption
                );


                years.forEach(
                    year => {

                        const option =
                            document.createElement(
                                'option'
                            );

                        option.value =
                            year;

                        option.textContent =
                            year;

                        yearSelect.appendChild(
                            option
                        );

                    }
                );


                yearSelect.disabled =
                    false;


                setStatus(
                    years.length +
                    ' year' +
                    (years.length === 1 ? '' : 's') +
                    ' available for ' +
                    SUBJECT_MAP[subjectKey].label +
                    '.',
                    'success'
                );

            }
            catch (error) {

                console.error(
                    error
                );


                yearSelect.innerHTML =
                    '<option value="">Unable to load years</option>';

                yearSelect.disabled =
                    true;


                setStatus(
                    error.message,
                    'error'
                );

            }

        }
    );


    /* =========================================================
       START PRACTICE
       ========================================================= */

    startBtn.addEventListener(
        'click',
        async function () {

            const subjectKey =
                subjectSelect.value;


            const selectedYear =
                yearSelect.value;


            if (!subjectKey) {

                setStatus(
                    'Please select a subject first.',
                    'error'
                );

                return;
            }


            if (!selectedYear) {

                setStatus(
                    'Please select an examination year.',
                    'error'
                );

                return;
            }


            try {

                startBtn.disabled =
                    true;

                startBtn.innerHTML =
                    'Loading Questions...';


                setStatus(
                    'Fetching the selected questions from the Flexi JAMB CBT App repository...',
                    'loading'
                );


                const rawQuestions =
                    await fetchQuestionBank(
                        subjectKey
                    );


                let questions =
                    rawQuestions.map(
                        normaliseQuestion
                    ).filter(
                        item =>
                            item.question &&
                            item.options.length > 0
                    );


                if (
                    selectedYear !== 'ALL'
                ) {

                    questions =
                        questions.filter(
                            item =>
                                item.year ===
                                selectedYear
                        );

                }


                if (
                    questions.length === 0
                ) {

                    throw new Error(
                        'No questions were found for the selected subject and year.'
                    );

                }


                /*
                 * Preserve the repository questions.
                 * We only shuffle the order presented in the
                 * simulator; the source data is not changed.
                 */

                questions =
                    shuffleArray(
                        questions
                    );


                currentQuestions =
                    questions;

                currentIndex = 0;

                selectedAnswers = {};

                completedAnswers = {};

                activeSubject =
                    subjectKey;

                activeYear =
                    selectedYear;


                simulatorSubject.textContent =
                    SUBJECT_MAP[
                        subjectKey
                    ].label;


                simulatorMeta.textContent =
                    selectedYear === 'ALL'
                        ? 'All available years • Flexi CBT Past Questions'
                        : selectedYear +
                          ' JAMB • Flexi CBT Past Questions';


                simulatorSection.classList.add(
                    'active'
                );


                resultCard.classList.remove(
                    'active'
                );


                renderQuestion();


                clearStatus();


                setTimeout(
                    () => {

                        simulatorSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                    },
                    80
                );

            }
            catch (error) {

                console.error(
                    error
                );


                setStatus(
                    error.message ||
                    'Unable to load the questions.',
                    'error'
                );

            }
            finally {

                startBtn.disabled =
                    false;

                startBtn.innerHTML = `
                    <svg
                        class="svg-icon"
                        viewBox="0 0 24 24"
                    >
                        <path d="M8 5v14l11-7z"></path>
                    </svg>
                    Start Practice
                `;

            }

        }
    );


    /* =========================================================
       SHUFFLE
       ========================================================= */

    function shuffleArray(
        array
    ) {

        const copy =
            [...array];


        for (
            let i =
                copy.length - 1;
            i > 0;
            i--
        ) {

            const j =
                Math.floor(
                    Math.random() *
                    (i + 1)
                );


            [
                copy[i],
                copy[j]
            ] =
            [
                copy[j],
                copy[i]
            ];

        }


        return copy;
    }


    /* =========================================================
       FORMAT MATHEMATICS
       ========================================================= */

    function formatMathText(
        value
    ) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


        let text =
            String(value);


        /*
         * If the repository already contains
         * MathJax delimiters, preserve them.
         */

        if (
            text.includes('\\(') ||
            text.includes('\\[') ||
            text.includes('$$')
        ) {

            return text;

        }


        /*
         * HTML escape before adding MathJax.
         */

        text =
            text
                .replace(
                    /&/g,
                    '&amp;'
                )
                .replace(
                    /</g,
                    '&lt;'
                )
                .replace(
                    />/g,
                    '&gt;'
                );


        /*
         * Common mathematical symbols.
         */

        text =
            text
                .replace(
                    /π/g,
                    '\\pi'
                )
                .replace(
                    /θ/g,
                    '\\theta'
                )
                .replace(
                    /α/g,
                    '\\alpha'
                )
                .replace(
                    /β/g,
                    '\\beta'
                )
                .replace(
                    /γ/g,
                    '\\gamma'
                )
                .replace(
                    /δ/g,
                    '\\delta'
                )
                .replace(
                    /Δ/g,
                    '\\Delta'
                )
                .replace(
                    /∞/g,
                    '\\infty'
                )
                .replace(
                    /≤/g,
                    '\\leq'
                )
                .replace(
                    /≥/g,
                    '\\geq'
                )
                .replace(
                    /±/g,
                    '\\pm'
                )
                .replace(
                    /×/g,
                    '\\times'
                )
                .replace(
                    /÷/g,
                    '\\div'
                );


        /*
         * Square root notation.
         */

        text =
            text.replace(
                /√\(([^)]+)\)/g,
                '\\sqrt{$1}'
            );


        text =
            text.replace(
                /√([A-Za-z0-9]+)/g,
                '\\sqrt{$1}'
            );


        /*
         * Superscripts such as x^2.
         */

        text =
            text.replace(
                /([A-Za-z0-9)])\^(-?\d+)/g,
                '$1^{$2}'
            );


        /*
         * Render obvious mathematical fragments.
         */

        const mathPattern =
            /(?:\\(?:sqrt|pi|theta|alpha|beta|gamma|delta|Delta|infty|leq|geq|pm|times|div)|[A-Za-z]+\^\{[^}]+\}|[A-Za-z0-9]+\^\{[^}]+\}|\\sqrt\{[^}]+\})/g;


        text =
            text.replace(
                mathPattern,
                match => {

                    if (
                        match.startsWith('\\(') ||
                        match.startsWith('\\[')
                    ) {

                        return match;

                    }

                    return '\\(' +
                        match +
                        '\\)';

                }
            );


        return text;
    }


    /* =========================================================
       RENDER QUESTION
       ========================================================= */

    function renderQuestion() {

        if (
            !currentQuestions.length
        ) {

            return;
        }


        const question =
            currentQuestions[
                currentIndex
            ];


        const total =
            currentQuestions.length;


        const number =
            currentIndex + 1;


        questionLabel.textContent =
            'QUESTION ' +
            number;


        questionCounter.textContent =
            'Question ' +
            number +
            ' of ' +
            total;


        progressBar.style.width =
            (
                number /
                total *
                100
            ) +
            '%';


        questionText.innerHTML =
            formatMathText(
                question.question
            );


        optionsContainer.innerHTML =
            '';


        answerInfo.classList.remove(
            'visible'
        );

        answerInfo.innerHTML =
            '';


        const options =
            question.options;


        options.forEach(
            (
                option,
                optionIndex
            ) => {

                const button =
                    document.createElement(
                        'button'
                    );


                button.type =
                    'button';

                button.className =
                    'option-btn';


                const letter =
                    String.fromCharCode(
                        65 +
                        optionIndex
                    );


                const letterDiv =
                    document.createElement(
                        'span'
                    );

                letterDiv.className =
                    'option-letter';

                letterDiv.textContent =
                    letter;


                const content =
                    document.createElement(
                        'span'
                    );

                content.className =
                    'option-content';

                content.innerHTML =
                    formatMathText(
                        option
                    );


                button.appendChild(
                    letterDiv
                );

                button.appendChild(
                    content
                );


                const stored =
                    selectedAnswers[
                        currentIndex
                    ];


                if (
                    stored === letter
                ) {

                    button.classList.add(
                        'selected'
                    );

                }


                if (
                    completedAnswers[
                        currentIndex
                    ]
                ) {

                    applyAnswerState(
                        button,
                        letter,
                        question.answer
                    );

                }


                button.addEventListener(
                    'click',
                    () => {

                        selectAnswer(
                            letter
                        );

                    }
                );


                optionsContainer.appendChild(
                    button
                );

            }
        );


        previousBtn.disabled =
            currentIndex === 0;


        if (
            currentIndex ===
            currentQuestions.length - 1
        ) {

            nextBtn.innerHTML = `
                Finish
                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                >
                    <path d="m5 12 5 5L19 7"></path>
                </svg>
            `;

        }
        else {

            nextBtn.innerHTML = `
                Next
                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                >
                    <path d="m9 18 6-6-6-6"></path>
                </svg>
            `;

        }


        typesetMath();

    }


    /* =========================================================
       SELECT ANSWER
       ========================================================= */

    function selectAnswer(
        selectedLetter
    ) {

        const question =
            currentQuestions[
                currentIndex
            ];


        if (
            completedAnswers[
                currentIndex
            ]
        ) {

            return;

        }


        selectedAnswers[
            currentIndex
        ] =
            selectedLetter;


        completedAnswers[
            currentIndex
        ] =
            true;


        const buttons =
            optionsContainer.querySelectorAll(
                '.option-btn'
            );


        buttons.forEach(
            button => {

                const letter =
                    button
                        .querySelector(
                            '.option-letter'
                        )
                        .textContent;


                applyAnswerState(
                    button,
                    letter,
                    question.answer,
                    selectedLetter
                );

            }
        );


        const isCorrect =
            normaliseAnswer(
                selectedLetter
            ) ===
            normaliseAnswer(
                question.answer
            );


        answerInfo.classList.add(
            'visible'
        );


        if (isCorrect) {

            answerInfo.innerHTML =
                '<strong>Correct.</strong> Your answer matches the answer recorded in the Flexi CBT question bank.';

        }
        else {

            answerInfo.innerHTML =
                '<strong>Correct answer:</strong> ' +
                esc(
                    question.answer
                );

        }


        typesetMath();

    }


    /* =========================================================
       NORMALISE ANSWER
       ========================================================= */

    function normaliseAnswer(
        answer
    ) {

        return String(
            answer || ''
        )
            .trim()
            .toUpperCase()
            .replace(
                /[\.\)\s]+$/g,
                ''
            )
            .charAt(0);
    }


    /* =========================================================
       ANSWER STATE
       ========================================================= */

    function applyAnswerState(
        button,
        letter,
        correctAnswer,
        selectedLetter = null
    ) {

        const correct =
            normaliseAnswer(
                correctAnswer
            );


        const selected =
            selectedLetter ||
            selectedAnswers[
                currentIndex
            ];


        if (
            letter === correct
        ) {

            button.classList.add(
                'correct'
            );


            const badge =
                document.createElement(
                    'span'
                );

            badge.className =
                'answer-badge correct';

            badge.textContent =
                'Correct';

            button.appendChild(
                badge
            );

        }


        if (
            selected &&
            letter ===
            normaliseAnswer(
                selected
            ) &&
            selected !==
            correctAnswer
        ) {

            button.classList.add(
                'wrong'
            );


            const badge =
                document.createElement(
                    'span'
                );

            badge.className =
                'answer-badge wrong';

            badge.textContent =
                'Your answer';

            button.appendChild(
                badge
            );

        }

    }


    /* =========================================================
       NEXT
       ========================================================= */

    nextBtn.addEventListener(
        'click',
        function () {

            if (
                currentIndex <
                currentQuestions.length - 1
            ) {

                currentIndex++;

                renderQuestion();

                return;
            }


            finishPractice();

        }
    );


    /* =========================================================
       PREVIOUS
       ========================================================= */

    previousBtn.addEventListener(
        'click',
        function () {

            if (
                currentIndex > 0
            ) {

                currentIndex--;

                renderQuestion();

            }

        }
    );


    /* =========================================================
       FINISH
       ========================================================= */

    function finishPractice() {

        let answered =
            0;

        let correct =
            0;


        currentQuestions.forEach(
            (
                question,
                index
            ) => {

                const selected =
                    selectedAnswers[
                        index
                    ];


                if (
                    selected
                ) {

                    answered++;


                    if (
                        normaliseAnswer(
                            selected
                        ) ===
                        normaliseAnswer(
                            question.answer
                        )
                    ) {

                        correct++;

                    }

                }

            }
        );


        const total =
            currentQuestions.length;


        const percentage =
            total
                ? Math.round(
                    correct /
                    total *
                    100
                )
                : 0;


        resultScore.textContent =
            percentage +
            '%';


        resultDetail.textContent =
            correct +
            ' correct out of ' +
            total +
            ' questions • ' +
            answered +
            ' answered';


        resultCard.classList.add(
            'active'
        );


        resultCard.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

    }


    /* =========================================================
       RESTART
       ========================================================= */

    document
        .getElementById(
            'restartBtn'
        )
        .addEventListener(
            'click',
            function () {

                if (
                    activeSubject
                ) {

                    startBtn.click();

                }

            }
        );


    /* =========================================================
       MATHJAX TYPESSETTING
       ========================================================= */

    function typesetMath() {

        if (
            window.MathJax &&
            typeof
                window.MathJax.typesetPromise ===
                'function'
        ) {

            window.MathJax.typesetPromise(
                [
                    questionText,
                    optionsContainer,
                    answerInfo
                ]
            ).catch(
                error =>
                    console.warn(
                        'MathJax typesetting:',
                        error
                    )
            );

        }

    }


    /* =========================================================
       CALCULATOR
       ========================================================= */

    const calculatorOverlay =
        document.getElementById(
            'calculatorOverlay'
        );

    const calculatorBtn =
        document.getElementById(
            'calculatorBtn'
        );

    const calculatorClose =
        document.getElementById(
            'calculatorClose'
        );

    const calcDisplay =
        document.getElementById(
            'calcDisplay'
        );


    function openCalculator() {

        calculatorOverlay.classList.add(
            'active'
        );

        calculatorOverlay.setAttribute(
            'aria-hidden',
            'false'
        );

    }


    function closeCalculator() {

        calculatorOverlay.classList.remove(
            'active'
        );

        calculatorOverlay.setAttribute(
            'aria-hidden',
            'true'
        );

    }


    calculatorBtn.addEventListener(
        'click',
        openCalculator
    );


    calculatorClose.addEventListener(
        'click',
        closeCalculator
    );


    calculatorOverlay.addEventListener(
        'click',
        function (event) {

            if (
                event.target ===
                calculatorOverlay
            ) {

                closeCalculator();

            }

        }
    );


    document
        .querySelectorAll(
            '[data-calc]'
        )
        .forEach(
            button => {

                button.addEventListener(
                    'click',
                    function () {

                        const value =
                            this.dataset.calc;


                        if (
                            value ===
                            'AC'
                        ) {

                            calculationValue =
                                '';

                        }
                        else if (
                            value ===
                            'DEL'
                        ) {

                            calculationValue =
                                calculationValue.slice(
                                    0,
                                    -1
                                );

                        }
                        else if (
                            value ===
                            '='
                        ) {

                            calculateResult();

                            return;

                        }
                        else {

                            calculationValue +=
                                value;

                        }


                        updateCalculatorDisplay();

                    }
                );

            }
        );


    function updateCalculatorDisplay() {

        calcDisplay.textContent =
            calculationValue ||
            '0';

    }


    function calculateResult() {

        if (
            !calculationValue
        ) {

            return;

        }


        /*
         * Calculator is intentionally restricted
         * to simple arithmetic characters.
         */

        if (
            !/^[0-9+\-*/().\s]+$/.test(
                calculationValue
            )
        ) {

            calculationValue =
                '';

            updateCalculatorDisplay();

            return;

        }


        try {

            const result =
                Function(
                    '"use strict"; return (' +
                    calculationValue +
                    ')'
                )();


            if (
                Number.isFinite(
                    result
                )
            ) {

                calculationValue =
                    String(result);

            }
            else {

                calculationValue =
                    '';

            }

        }
        catch {

            calculationValue =
                '';

        }


        updateCalculatorDisplay();

    }


    /* =========================================================
       MOBILE MENU
       ========================================================= */

    document
        .getElementById(
            'mobileMenuBtn'
        )
        .addEventListener(
            'click',
            function () {

                /*
                 * The main website's menu can remain in control
                 * of the full navigation. This page deliberately
                 * does not replace the existing menu system.
                 */

                window.location.href =
                    'index.php';

            }
        );


    /* =========================================================
       APP PROMOTION
       ========================================================= */

    document
        .getElementById(
            'appPromotionLink'
        )
        .addEventListener(
            'click',
            function (event) {

                /*
                 * Keep the card as an image-loaded advertisement.
                 * If the site's existing app URL is supplied later,
                 * only this href needs to be changed.
                 */

                const appUrl =
                    'https://github.com/flexisystems2000/Flexi-JAMB-CBT-App-';

                if (
                    appUrl &&
                    appUrl !== '#'
                ) {

                    event.preventDefault();

                    window.open(
                        appUrl,
                        '_blank',
                        'noopener,noreferrer'
                    );

                }

            }
        );


    /* =========================================================
       KEYBOARD SHORTCUTS
       ========================================================= */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                simulatorSection.classList.contains(
                    'active'
                )
            ) {

                if (
                    event.key ===
                    'ArrowRight'
                ) {

                    nextBtn.click();

                }


                if (
                    event.key ===
                    'ArrowLeft'
                ) {

                    previousBtn.click();

                }

            }

        }
    );


    /* =========================================================
       ESCAPE CALCULATOR
       ========================================================= */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key ===
                'Escape'
            ) {

                closeCalculator();

            }

        }
    );

</script>

</body>
</html>
