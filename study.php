<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// STUDY — PAST QUESTIONS
//
// DATA SOURCE:
// Flexi JAMB CBT Practice App GitHub repository
//
// Repository:
// https://github.com/flexisystems2000/Flexi-JAMB-CBT-App-
//
// Question source:
// question_bank/*.json
//
// FLOW:
// 1. Student selects subject.
// 2. Subject JSON is read from the Flexi repository.
// 3. Available years are extracted.
// 4. Student selects year.
// 5. Questions for that year are filtered.
// 6. Questions are displayed inside a CBT simulator.
//
// IMPORTANT:
// - No scholarship system.
// - This mirrors ONLY the Past Questions system.
// - No question data is invented here.
// - SVG icons are used instead of emoji/Boxicons.
// ============================================================


// ============================================================
// BASIC CONFIGURATION
// ============================================================

$githubRawBase =
    'https://raw.githubusercontent.com/flexisystems2000/Flexi-JAMB-CBT-App-/main/question_bank/';


// ============================================================
// SUBJECT MAP
// These filenames match the Flexi JAMB CBT App repository.
// ============================================================

$subjects = [

    'english' => [
        'name' => 'English Language',
        'file' => 'use_of_english.json'
    ],

    'accounting' => [
        'name' => 'Accounting',
        'file' => 'accounting.json'
    ],

    'arabic' => [
        'name' => 'Arabic',
        'file' => 'arabic.json'
    ],

    'biology' => [
        'name' => 'Biology',
        'file' => 'biology.json'
    ],

    'chemistry' => [
        'name' => 'Chemistry',
        'file' => 'chemistry.json'
    ],

    'crs' => [
        'name' => 'Christian Religious Studies (CRS)',
        'file' => 'christian_religious_studies__crs_.json'
    ],

    'commerce' => [
        'name' => 'Commerce',
        'file' => 'commerce.json'
    ],

    'computer' => [
        'name' => 'Computer Studies',
        'file' => 'computer_studies.json'
    ],

    'economics' => [
        'name' => 'Economics',
        'file' => 'economics.json'
    ],

    'fine_art' => [
        'name' => 'Fine Art',
        'file' => 'fine_art.json'
    ],

    'government' => [
        'name' => 'Government',
        'file' => 'government.json'
    ],

    'literature' => [
        'name' => 'Literature in English',
        'file' => 'literature_in_english.json'
    ],

    'mathematics' => [
        'name' => 'Mathematics',
        'file' => 'mathematics.json'
    ],

    'physics' => [
        'name' => 'Physics',
        'file' => 'physics.json'
    ],

    'yoruba' => [
        'name' => 'Yoruba',
        'file' => 'yoruba.json'
    ]
];


// ============================================================
// HELPER: ESCAPE HTML
// ============================================================

function esc($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


// ============================================================
// HELPER: FETCH JSON
// ============================================================

function fetchRemoteJson($url)
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' =>
                "Accept: application/json\r\n" .
                "User-Agent: Flexi-Educational-Consult\r\n",
            'timeout' => 15,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents(
        $url,
        false,
        $context
    );

    if ($response === false) {
        return [];
    }

    $decoded = json_decode(
        $response,
        true
    );

    return is_array($decoded)
        ? $decoded
        : [];
}


// ============================================================
// HELPER: NORMALIZE QUESTION ARRAY
// ============================================================

function normalizeQuestions($data)
{
    if (isset($data['questions']) && is_array($data['questions'])) {
        return $data['questions'];
    }

    if (isset($data['data']) && is_array($data['data'])) {
        return $data['data'];
    }

    if (isset($data['items']) && is_array($data['items'])) {
        return $data['items'];
    }

    return is_array($data)
        ? $data
        : [];
}


// ============================================================
// SELECTED SUBJECT
// ============================================================

$selectedSubject =
    isset($_GET['subject'])
        ? strtolower(trim($_GET['subject']))
        : '';

$selectedYear =
    isset($_GET['year'])
        ? trim($_GET['year'])
        : '';


// ============================================================
// SUBJECT DATA
// ============================================================

$subjectQuestions = [];

if (
    $selectedSubject !== '' &&
    isset($subjects[$selectedSubject])
) {

    $subjectFile =
        $subjects[$selectedSubject]['file'];

    $subjectUrl =
        $githubRawBase .
        rawurlencode($subjectFile);

    $remoteData =
        fetchRemoteJson($subjectUrl);

    $subjectQuestions =
        normalizeQuestions($remoteData);
}


// ============================================================
// AVAILABLE YEARS
// ============================================================

$availableYears = [];

foreach ($subjectQuestions as $question) {

    if (!is_array($question)) {
        continue;
    }

    $year =
        trim((string)($question['year'] ?? ''));

    if ($year !== '') {
        $availableYears[$year] = true;
    }
}

$availableYears =
    array_keys($availableYears);

usort(
    $availableYears,
    function ($a, $b) {
        return (int)$b <=> (int)$a;
    }
);


// ============================================================
// FILTER QUESTIONS BY SELECTED YEAR
// ============================================================

$practiceQuestions = [];

if (
    $selectedYear !== '' &&
    !empty($subjectQuestions)
) {

    foreach ($subjectQuestions as $question) {

        if (!is_array($question)) {
            continue;
        }

        $questionYear =
            trim((string)($question['year'] ?? ''));

        if ($questionYear === $selectedYear) {

            if (
                !isset($question['question']) ||
                !isset($question['options'])
            ) {
                continue;
            }

            $practiceQuestions[] = [
                'question' =>
                    (string)$question['question'],

                'options' =>
                    is_array($question['options'])
                        ? array_values($question['options'])
                        : [],

                'answer' =>
                    (string)($question['answer'] ?? ''),

                'year' =>
                    $questionYear,

                'topic' =>
                    (string)($question['topic'] ?? '')
            ];
        }
    }
}


// ============================================================
// RETURN QUESTIONS TO JAVASCRIPT SAFELY
// ============================================================

$questionsJson =
    json_encode(
        $practiceQuestions,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    );

if ($questionsJson === false) {
    $questionsJson = '[]';
}


// ============================================================
// CURRENT SUBJECT NAME
// ============================================================

$currentSubjectName = '';

if (isset($subjects[$selectedSubject])) {
    $currentSubjectName =
        $subjects[$selectedSubject]['name'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width,
             initial-scale=1.0,
             viewport-fit=cover"
>

<title>
    <?= esc(
        $currentSubjectName !== ''
            ? $currentSubjectName . ' Past Questions - Flexi Educational Consult'
            : 'Study Past Questions - Flexi Educational Consult'
    ) ?>
</title>

<meta
    name="theme-color"
    content="#008000"
>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    -webkit-tap-highlight-color: transparent;
}

:root {

    --green: #008000;
    --green-dark: #006400;
    --green-light: #eaf8ee;

    --blue: #0757a0;
    --blue-dark: #043d73;

    --text: #1f2937;
    --muted: #6b7280;

    --bg: #f4f7f9;
    --card: #ffffff;

    --border: #e5e7eb;

    --shadow:
        0 8px 25px rgba(0,0,0,.07);

    --radius: 18px;
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

    background:
        var(--bg);

    color:
        var(--text);

    min-height:
        100vh;
}


/* ============================================================
   PAGE WRAPPER
   ============================================================ */

.page {

    width:
        min(100% - 30px, 1200px);

    margin:
        0 auto;

    padding:
        20px 0 50px;
}


/* ============================================================
   HEADER
   ============================================================ */

.topbar {

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    margin-bottom:
        20px;
}

.back-btn {

    width:
        46px;

    height:
        46px;

    border-radius:
        50%;

    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    text-decoration:
        none;

    color:
        var(--text);

    background:
        var(--card);

    border:
        1px solid var(--border);

    box-shadow:
        var(--shadow);

    transition:
        .2s ease;
}

.back-btn:hover {
    transform:
        translateY(-2px);
}

.svg-icon {

    width:
        21px;

    height:
        21px;

    stroke:
        currentColor;

    fill:
        none;

    stroke-width:
        2;

    stroke-linecap:
        round;

    stroke-linejoin:
        round;
}


/* ============================================================
   PAGE INTRO
   ============================================================ */

.intro {

    background:
        linear-gradient(
            135deg,
            #008000,
            #0757a0
        );

    color:
        white;

    border-radius:
        22px;

    padding:
        24px;

    margin-bottom:
        22px;

    box-shadow:
        0 12px 30px rgba(0,90,100,.15);
}

.intro h1 {

    font-size:
        clamp(1.45rem, 3vw, 2rem);

    margin-bottom:
        7px;
}

.intro p {

    font-size:
        .94rem;

    opacity:
        .92;

    line-height:
        1.55;
}


/* ============================================================
   SELECTION CARD
   ============================================================ */

.selection-card {

    background:
        var(--card);

    border:
        1px solid var(--border);

    border-radius:
        var(--radius);

    padding:
        22px;

    box-shadow:
        var(--shadow);

    margin-bottom:
        25px;
}

.selection-title {

    display:
        flex;

    align-items:
        center;

    gap:
        10px;

    margin-bottom:
        18px;
}

.selection-title-icon {

    width:
        40px;

    height:
        40px;

    border-radius:
        12px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    background:
        var(--green-light);

    color:
        var(--green);
}

.selection-title h2 {

    font-size:
        1.1rem;

    font-weight:
        800;
}

.form-grid {

    display:
        grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap:
        16px;
}

.form-group label {

    display:
        block;

    font-size:
        .88rem;

    font-weight:
        700;

    margin-bottom:
        7px;
}

.select-wrap {
    position:
        relative;
}

.form-control {

    width:
        100%;

    min-height:
        50px;

    border:
        1.5px solid var(--border);

    border-radius:
        13px;

    background:
        #fff;

    color:
        var(--text);

    padding:
        0 45px 0 15px;

    font-size:
        .95rem;

    outline:
        none;

    appearance:
        none;

    transition:
        .2s ease;
}

.form-control:focus {

    border-color:
        var(--green);

    box-shadow:
        0 0 0 3px rgba(0,128,0,.08);
}

.select-arrow {

    position:
        absolute;

    right:
        15px;

    top:
        50%;

    transform:
        translateY(-50%);

    pointer-events:
        none;

    color:
        var(--muted);
}

.start-btn {

    width:
        100%;

    min-height:
        52px;

    border:
        none;

    border-radius:
        14px;

    margin-top:
        17px;

    background:
        linear-gradient(
            135deg,
            #008000,
            #006400
        );

    color:
        white;

    font-size:
        .98rem;

    font-weight:
        800;

    cursor:
        pointer;

    transition:
        .2s ease;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    gap:
        9px;
}

.start-btn:hover {
    transform:
        translateY(-1px);
}

.start-btn:active {
    transform:
        scale(.99);
}


/* ============================================================
   ERROR / EMPTY MESSAGE
   ============================================================ */

.notice {

    padding:
        15px 17px;

    border-radius:
        13px;

    background:
        #fff7ed;

    color:
        #9a3412;

    border:
        1px solid #fed7aa;

    margin-bottom:
        20px;

    line-height:
        1.5;

    font-size:
        .92rem;
}


/* ============================================================
   CBT AREA
   ============================================================ */

.cbt-shell {

    display:
        none;
}

.cbt-shell.active {
    display:
        block;
}


/* ============================================================
   CBT HEADER
   ============================================================ */

.cbt-header {

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    gap:
        15px;

    background:
        var(--card);

    border:
        1px solid var(--border);

    border-radius:
        var(--radius);

    padding:
        15px 18px;

    box-shadow:
        var(--shadow);

    margin-bottom:
        18px;
}

.cbt-subject {

    min-width:
        0;
}

.cbt-subject small {

    display:
        block;

    color:
        var(--muted);

    font-size:
        .76rem;

    margin-bottom:
        3px;
}

.cbt-subject strong {

    display:
        block;

    font-size:
        1rem;

    white-space:
        nowrap;

    overflow:
        hidden;

    text-overflow:
        ellipsis;
}

.progress-area {

    width:
        min(230px, 40%);

    flex-shrink:
        0;
}

.progress-text {

    display:
        flex;

    justify-content:
        space-between;

    font-size:
        .75rem;

    color:
        var(--muted);

    margin-bottom:
        5px;
}

.progress-bar {

    height:
        7px;

    border-radius:
        99px;

    background:
        #e5e7eb;

    overflow:
        hidden;
}

.progress-fill {

    height:
        100%;

    width:
        0%;

    background:
        linear-gradient(
            90deg,
            #008000,
            #0757a0
        );

    border-radius:
        inherit;

    transition:
        width .25s ease;
}


/* ============================================================
   QUESTION LAYOUT
   ============================================================ */

.cbt-grid {

    display:
        grid;

    grid-template-columns:
        minmax(0, 1fr) 270px;

    gap:
        20px;

    align-items:
        start;
}

.question-card {

    background:
        var(--card);

    border:
        1px solid var(--border);

    border-radius:
        var(--radius);

    padding:
        25px;

    box-shadow:
        var(--shadow);
}

.question-meta {

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    gap:
        10px;

    margin-bottom:
        18px;
}

.question-number {

    color:
        var(--green);

    font-weight:
        800;

    font-size:
        .9rem;
}

.year-badge {

    background:
        #eef6ff;

    color:
        var(--blue);

    border-radius:
        20px;

    padding:
        5px 10px;

    font-size:
        .72rem;

    font-weight:
        800;
}

.question-text {

    font-size:
        clamp(1rem, 2vw, 1.13rem);

    line-height:
        1.65;

    font-weight:
        600;

    margin-bottom:
        23px;
}

.options-list {

    display:
        flex;

    flex-direction:
        column;

    gap:
        11px;
}

.option-btn {

    width:
        100%;

    display:
        flex;

    align-items:
        flex-start;

    gap:
        12px;

    padding:
        14px;

    border:
        1.5px solid var(--border);

    border-radius:
        13px;

    background:
        #fff;

    color:
        var(--text);

    text-align:
        left;

    cursor:
        pointer;

    font-size:
        .94rem;

    line-height:
        1.5;

    transition:
        .2s ease;
}

.option-btn:hover:not(:disabled) {

    border-color:
        #75b875;

    background:
        #f8fff9;
}

.option-letter {

    width:
        30px;

    height:
        30px;

    flex:
        0 0 30px;

    border-radius:
        50%;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    background:
        #f1f5f9;

    color:
        #475569;

    font-size:
        .8rem;

    font-weight:
        800;
}

.option-content {
    flex:
        1;
}

.option-btn.selected {

    border-color:
        var(--blue);

    background:
        #eef6ff;
}

.option-btn.selected .option-letter {

    background:
        var(--blue);

    color:
        white;
}

.option-btn.correct {

    border-color:
        #16a34a;

    background:
        #dcfce7;

    color:
        #166534;
}

.option-btn.correct .option-letter {

    background:
        #16a34a;

    color:
        white;
}

.option-btn.wrong {

    border-color:
        #dc2626;

    background:
        #fee2e2;

    color:
        #991b1b;
}

.option-btn.wrong .option-letter {

    background:
        #dc2626;

    color:
        white;
}


/* ============================================================
   NAVIGATION
   ============================================================ */

.question-navigation {

    display:
        flex;

    justify-content:
        space-between;

    gap:
        12px;

    margin-top:
        20px;
}

.nav-btn {

    min-height:
        48px;

    border:
        none;

    border-radius:
        13px;

    padding:
        0 20px;

    background:
        var(--green);

    color:
        white;

    font-weight:
        800;

    cursor:
        pointer;

    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    gap:
        8px;

    transition:
        .2s ease;
}

.nav-btn.secondary {

    background:
        #eef2f5;

    color:
        #334155;

    border:
        1px solid var(--border);
}

.nav-btn:disabled {

    opacity:
        .45;

    cursor:
        not-allowed;
}


/* ============================================================
   QUESTION PALETTE
   ============================================================ */

.palette-card {

    background:
        var(--card);

    border:
        1px solid var(--border);

    border-radius:
        var(--radius);

    padding:
        18px;

    box-shadow:
        var(--shadow);

    position:
        sticky;

    top:
        18px;
}

.palette-card h3 {

    font-size:
        .95rem;

    margin-bottom:
        5px;
}

.palette-info {

    font-size:
        .76rem;

    color:
        var(--muted);

    line-height:
        1.45;

    margin-bottom:
        15px;
}

.palette {

    display:
        grid;

    grid-template-columns:
        repeat(5, 1fr);

    gap:
        7px;

    max-height:
        400px;

    overflow:
        auto;

    padding-right:
        2px;
}

.palette-btn {

    min-height:
        35px;

    border:
        1px solid var(--border);

    border-radius:
        8px;

    background:
        #f8fafc;

    color:
        #475569;

    cursor:
        pointer;

    font-size:
        .75rem;

    font-weight:
        700;
}

.palette-btn.current {

    background:
        var(--blue);

    border-color:
        var(--blue);

    color:
        white;
}

.palette-btn.answered {

    background:
        #dcfce7;

    border-color:
        #86efac;

    color:
        #166534;
}


/* ============================================================
   RESULT CARD
   ============================================================ */

.result-card {

    display:
        none;

    background:
        var(--card);

    border:
        1px solid var(--border);

    border-radius:
        var(--radius);

    box-shadow:
        var(--shadow);

    padding:
        30px;

    text-align:
        center;

    margin-top:
        20px;
}

.result-card.active {
    display:
        block;
}

.result-icon {

    width:
        60px;

    height:
        60px;

    margin:
        0 auto 15px;

    border-radius:
        50%;

    background:
        var(--green-light);

    color:
        var(--green);

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;
}

.result-card h2 {

    font-size:
        1.35rem;

    margin-bottom:
        8px;
}

.result-score {

    font-size:
        2.3rem;

    font-weight:
        900;

    color:
        var(--green);

    margin:
        10px 0;
}

.result-card p {

    color:
        var(--muted);

    margin-bottom:
        20px;
}

.restart-btn {

    border:
        none;

    border-radius:
        13px;

    padding:
        13px 22px;

    background:
        var(--green);

    color:
        white;

    font-weight:
        800;

    cursor:
        pointer;
}


/* ============================================================
   CALCULATOR
   ============================================================ */

.calc-overlay {

    position:
        fixed;

    inset:
        0;

    z-index:
        10000;

    display:
        none;

    align-items:
        center;

    justify-content:
        center;

    background:
        rgba(0,0,0,.72);

    padding:
        20px;
}

.calc-overlay.active {
    display:
        flex;
}

.calc-card {

    width:
        min(100%, 350px);

    background:
        #111827;

    border-radius:
        22px;

    overflow:
        hidden;

    box-shadow:
        0 25px 70px rgba(0,0,0,.4);
}

.calc-display {

    min-height:
        90px;

    padding:
        20px;

    display:
        flex;

    align-items:
        flex-end;

    justify-content:
        flex-end;

    color:
        white;

    font-size:
        2rem;

    overflow:
        hidden;

    word-break:
        break-all;
}

.calc-grid {

    display:
        grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap:
        1px;

    background:
        #374151;
}

.calc-grid button {

    min-height:
        60px;

    border:
        none;

    background:
        #1f2937;

    color:
        white;

    font-size:
        1.05rem;

    cursor:
        pointer;
}

.calc-grid button:hover {
    background:
        #374151;
}

.calc-grid .operator {
    background:
        #0757a0;
}

.calc-close {

    width:
        100%;

    min-height:
        48px;

    border:
        none;

    background:
        #111827;

    color:
        #9ca3af;

    cursor:
        pointer;

    font-weight:
        700;
}


/* ============================================================
   MOBILE
   ============================================================ */

@media (max-width: 800px) {

    .cbt-grid {
        grid-template-columns:
            1fr;
    }

    .palette-card {

        position:
            static;

        order:
            2;
    }

    .palette {

        max-height:
            180px;
    }
}


@media (max-width: 620px) {

    .page {

        width:
            min(100% - 20px, 600px);

        padding-top:
            12px;
    }

    .form-grid {

        grid-template-columns:
            1fr;
    }

    .intro {
        padding:
            20px;
    }

    .question-card {
        padding:
            18px;
    }

    .cbt-header {

        align-items:
            flex-start;

        flex-direction:
            column;
    }

    .progress-area {
        width:
            100%;
    }

    .question-navigation {

        position:
            sticky;

        bottom:
            8px;

        z-index:
            20;

        background:
            rgba(255,255,255,.95);

        backdrop-filter:
            blur(8px);

        padding:
            8px;

        border-radius:
            14px;

        box-shadow:
            0 4px 20px rgba(0,0,0,.08);
    }

    .nav-btn {
        flex:
            1;
    }
}

</style>

</head>

<body>


<div class="page">


    <!-- ======================================================
         HEADER
         ====================================================== -->

    <div class="topbar">

        <a
            href="study.php"
            class="back-btn"
            aria-label="Back"
        >

            <svg
                class="svg-icon"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path d="M19 12H5"></path>
                <path d="M12 19l-7-7 7-7"></path>
            </svg>

        </a>

    </div>


    <!-- ======================================================
         INTRO
         ====================================================== -->

    <section class="intro">

        <h1>
            Study Past Questions
        </h1>

        <p>
            Practice JAMB past questions from the
            Flexi JAMB CBT Practice App question bank.
            Select a subject and examination year to begin.
        </p>

    </section>


    <!-- ======================================================
         SELECTION
         ====================================================== -->

    <section class="selection-card">

        <div class="selection-title">

            <div class="selection-title-icon">

                <svg
                    class="svg-icon"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <rect
                        x="3"
                        y="4"
                        width="18"
                        height="16"
                        rx="2"
                    ></rect>

                    <path d="M7 8h10"></path>
                    <path d="M7 12h6"></path>
                    <path d="M7 16h4"></path>
                </svg>

            </div>

            <div>
                <h2>
                    Choose Your Practice
                </h2>
            </div>

        </div>


        <div class="form-grid">


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
                            Select a subject
                        </option>

                        <?php foreach ($subjects as $key => $subject): ?>

                            <option
                                value="<?= esc($key) ?>"
                                <?= $selectedSubject === $key ? 'selected' : '' ?>
                            >
                                <?= esc($subject['name']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <div class="select-arrow">

                        <svg
                            class="svg-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>

                    </div>

                </div>

            </div>


            <!-- EXAM TYPE -->

            <div class="form-group">

                <label for="examType">
                    Examination
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

                    <div class="select-arrow">

                        <svg
                            class="svg-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>

                    </div>

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
                        <?= empty($availableYears) ? 'disabled' : '' ?>
                    >

                        <?php if (!empty($availableYears)): ?>

                            <option value="">
                                Select a year
                            </option>

                            <?php foreach ($availableYears as $year): ?>

                                <option
                                    value="<?= esc($year) ?>"
                                    <?= $selectedYear === $year ? 'selected' : '' ?>
                                >
                                    <?= esc($year) ?>
                                </option>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <option value="">
                                Select subject first
                            </option>

                        <?php endif; ?>

                    </select>

                    <div class="select-arrow">

                        <svg
                            class="svg-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>

                    </div>

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

                    <div class="select-arrow">

                        <svg
                            class="svg-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>

                    </div>

                </div>

            </div>


        </div>


        <button
            type="button"
            class="start-btn"
            id="startBtn"
        >

            <svg
                class="svg-icon"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path d="M8 5v14l11-7z"></path>
            </svg>

            Start Practice

        </button>

    </section>


    <!-- ======================================================
         SERVER NOTICE
         ====================================================== -->

    <?php if (
        $selectedYear !== '' &&
        empty($practiceQuestions)
    ): ?>

        <div class="notice">

            No questions were found for
            <strong>
                <?= esc($currentSubjectName) ?>
            </strong>
            in
            <strong>
                <?= esc($selectedYear) ?>
            </strong>.

        </div>

    <?php endif; ?>


    <!-- ======================================================
         CBT SHELL
         ====================================================== -->

    <section
        class="cbt-shell <?= !empty($practiceQuestions) ? 'active' : '' ?>"
        id="cbtShell"
    >


        <!-- CBT HEADER -->

        <div class="cbt-header">

            <div class="cbt-subject">

                <small>
                    CURRENT PRACTICE
                </small>

                <strong id="cbtSubjectName">
                    <?= esc($currentSubjectName) ?>
                </strong>

            </div>


            <div class="progress-area">

                <div class="progress-text">

                    <span id="progressQuestion">
                        Question 1
                    </span>

                    <span id="progressPercent">
                        0%
                    </span>

                </div>

                <div class="progress-bar">

                    <div
                        class="progress-fill"
                        id="progressFill"
                    ></div>

                </div>

            </div>

        </div>


        <!-- CBT GRID -->

        <div class="cbt-grid">


            <!-- QUESTION -->

            <div>

                <div
                    class="question-card"
                    id="questionCard"
                >

                    <div class="question-meta">

                        <div
                            class="question-number"
                            id="questionNumber"
                        >
                            Question 1
                        </div>

                        <div
                            class="year-badge"
                            id="yearBadge"
                        >
                            <?= esc($selectedYear) ?>
                        </div>

                    </div>


                    <div
                        class="question-text"
                        id="questionText"
                    >
                    </div>


                    <div
                        class="options-list"
                        id="optionsContainer"
                    >
                    </div>


                    <div class="question-navigation">

                        <button
                            type="button"
                            class="nav-btn secondary"
                            id="previousBtn"
                        >

                            <svg
                                class="svg-icon"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path d="M15 18l-6-6 6-6"></path>
                            </svg>

                            Previous

                        </button>


                        <button
                            type="button"
                            class="nav-btn"
                            id="nextBtn"
                        >

                            Next

                            <svg
                                class="svg-icon"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path d="M9 18l6-6-6-6"></path>
                            </svg>

                        </button>

                    </div>

                </div>


                <!-- RESULT -->

                <div
                    class="result-card"
                    id="resultCard"
                >

                    <div class="result-icon">

                        <svg
                            class="svg-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path d="M5 12l4 4L19 6"></path>
                        </svg>

                    </div>

                    <h2>
                        Practice Complete
                    </h2>

                    <div
                        class="result-score"
                        id="resultScore"
                    >
                        0%
                    </div>

                    <p id="resultSummary">
                        You have completed this practice.
                    </p>

                    <button
                        type="button"
                        class="restart-btn"
                        id="restartBtn"
                    >
                        Practice Again
                    </button>

                </div>

            </div>


            <!-- QUESTION PALETTE -->

            <aside class="palette-card">

                <h3>
                    Questions
                </h3>

                <p class="palette-info">
                    Green indicates questions you have answered.
                    Blue indicates the current question.
                </p>

                <div
                    class="palette"
                    id="questionPalette"
                >
                </div>

            </aside>


        </div>

    </section>


</div>


<!-- ==========================================================
     CALCULATOR
     ========================================================== -->

<div
    class="calc-overlay"
    id="calculatorOverlay"
>

    <div class="calc-card">

        <div
            class="calc-display"
            id="calculatorDisplay"
        >
            0
        </div>

        <div class="calc-grid">

            <button data-calc="AC">
                AC
            </button>

            <button data-calc="DEL">
                DEL
            </button>

            <button data-calc="(">
                (
            </button>

            <button
                data-calc=")"
                class="operator"
            >
                )
            </button>


            <button data-calc="7">
                7
            </button>

            <button data-calc="8">
                8
            </button>

            <button data-calc="9">
                9
            </button>

            <button
                data-calc="/"
                class="operator"
            >
                ÷
            </button>


            <button data-calc="4">
                4
            </button>

            <button data-calc="5">
                5
            </button>

            <button data-calc="6">
                6
            </button>

            <button
                data-calc="*"
                class="operator"
            >
                ×
            </button>


            <button data-calc="1">
                1
            </button>

            <button data-calc="2">
                2
            </button>

            <button data-calc="3">
                3
            </button>

            <button
                data-calc="-"
                class="operator"
            >
                −
            </button>


            <button data-calc="0">
                0
            </button>

            <button data-calc=".">
                .
            </button>

            <button data-calc="=">
                =
            </button>

            <button
                data-calc="+"
                class="operator"
            >
                +
            </button>

        </div>


        <button
            type="button"
            class="calc-close"
            id="calculatorClose"
        >
            CLOSE CALCULATOR
        </button>

    </div>

</div>


<script>

// ============================================================
// SERVER DATA
// ============================================================

const SUBJECTS =
    <?= json_encode(
        $subjects,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    ) ?>;

const SERVER_QUESTIONS =
    <?= $questionsJson ?>;

const SELECTED_SUBJECT =
    <?= json_encode($selectedSubject) ?>;

const SELECTED_YEAR =
    <?= json_encode($selectedYear) ?>;


// ============================================================
// ELEMENTS
// ============================================================

const subjectSelect =
    document.getElementById('subjectSelect');

const yearSelect =
    document.getElementById('yearSelect');

const startBtn =
    document.getElementById('startBtn');

const cbtShell =
    document.getElementById('cbtShell');

const questionNumber =
    document.getElementById('questionNumber');

const yearBadge =
    document.getElementById('yearBadge');

const questionText =
    document.getElementById('questionText');

const optionsContainer =
    document.getElementById('optionsContainer');

const previousBtn =
    document.getElementById('previousBtn');

const nextBtn =
    document.getElementById('nextBtn');

const questionPalette =
    document.getElementById('questionPalette');

const progressQuestion =
    document.getElementById('progressQuestion');

const progressPercent =
    document.getElementById('progressPercent');

const progressFill =
    document.getElementById('progressFill');

const resultCard =
    document.getElementById('resultCard');

const resultScore =
    document.getElementById('resultScore');

const resultSummary =
    document.getElementById('resultSummary');

const restartBtn =
    document.getElementById('restartBtn');


// ============================================================
// LOAD YEARS FROM GITHUB REPOSITORY
// ============================================================

async function loadSubjectYears(subjectKey)
{
    if (!subjectKey) {

        yearSelect.innerHTML =
            '<option value="">Select subject first</option>';

        yearSelect.disabled =
            true;

        return;
    }


    const subject =
        SUBJECTS[subjectKey];

    if (!subject) {
        return;
    }


    yearSelect.disabled =
        true;

    yearSelect.innerHTML =
        '<option value="">Loading years...</option>';


    try {

        const url =
            'https://raw.githubusercontent.com/' +
            'flexisystems2000/Flexi-JAMB-CBT-App-/main/question_bank/' +
            encodeURIComponent(subject.file);


        const response =
            await fetch(url, {
                cache: 'no-store'
            });


        if (!response.ok) {
            throw new Error(
                'Unable to load question bank.'
            );
        }


        const data =
            await response.json();


        const questions =
            Array.isArray(data)
                ? data
                : (
                    data.questions ||
                    data.data ||
                    data.items ||
                    []
                );


        const years =
            [...new Set(
                questions
                    .map(q => String(q.year || '').trim())
                    .filter(Boolean)
            )]
            .sort(
                (a, b) =>
                    Number(b) - Number(a)
            );


        yearSelect.innerHTML =
            '<option value="">Select a year</option>';


        years.forEach(year => {

            const option =
                document.createElement('option');

            option.value =
                year;

            option.textContent =
                year;

            yearSelect.appendChild(
                option
            );

        });


        yearSelect.disabled =
            years.length === 0;


    } catch (error) {

        console.error(error);

        yearSelect.innerHTML =
            '<option value="">Unable to load years</option>';

        yearSelect.disabled =
            true;
    }
}


// ============================================================
// SUBJECT CHANGE
// ============================================================

subjectSelect.addEventListener(
    'change',
    function ()
    {
        const subject =
            this.value;

        const params =
            new URLSearchParams();

        if (subject) {
            params.set(
                'subject',
                subject
            );
        }

        window.history.replaceState(
            {},
            '',
            'study.php' +
            (
                params.toString()
                    ? '?' + params.toString()
                    : ''
            )
        );


        loadSubjectYears(subject);
    }
);


// ============================================================
// START PRACTICE
// ============================================================

startBtn.addEventListener(
    'click',
    function ()
    {
        const subject =
            subjectSelect.value;

        const year =
            yearSelect.value;


        if (!subject) {

            alert(
                'Please select a subject.'
            );

            return;
        }


        if (!year) {

            alert(
                'Please select an examination year.'
            );

            return;
        }


        window.location.href =
            'study.php?subject=' +
            encodeURIComponent(subject) +
            '&year=' +
            encodeURIComponent(year);
    }
);


// ============================================================
// CBT STATE
// ============================================================

let questions =
    Array.isArray(SERVER_QUESTIONS)
        ? SERVER_QUESTIONS
        : [];

let currentIndex =
    0;

let userAnswers =
    {};

let score =
    0;

let answeredQuestions =
    new Set();


// ============================================================
// SHUFFLE
// Mirrors the Flexi CBT practice logic.
// ============================================================

function shuffleArray(array)
{
    const copy =
        [...array];

    for (
        let i = copy.length - 1;
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


// Randomize questions only once
if (questions.length > 1) {

    questions =
        shuffleArray(
            questions
        );
}


// ============================================================
// MATH FORMATTING
// ============================================================

function escapeHtml(text)
{
    const div =
        document.createElement('div');

    div.textContent =
        String(text ?? '');

    return div.innerHTML;
}


function formatMathText(text)
{
    if (!text) {
        return '';
    }


    let value =
        String(text);


    // Existing MathJax syntax should remain untouched.
    if (
        /\\\(|\\\[|\$\$/.test(value)
    ) {

        return escapeHtml(
            value
        )
        .replace(
            /&amp;/g,
            '&'
        );

    }


    value =
        escapeHtml(
            value
        );


    value =
        value.replace(
            /√(\d+)/g,
            '\\\\sqrt{$1}'
        );


    value =
        value.replace(
            /√([A-Za-z])/g,
            '\\\\sqrt{$1}'
        );


    value =
        value.replace(
            /π/g,
            '\\\\pi'
        );


    value =
        value.replace(
            /θ/g,
            '\\\\theta'
        );


    value =
        value.replace(
            /α/g,
            '\\\\alpha'
        );


    value =
        value.replace(
            /β/g,
            '\\\\beta'
        );


    value =
        value.replace(
            /γ/g,
            '\\\\gamma'
        );


    value =
        value.replace(
            /Δ/g,
            '\\\\Delta'
        );


    value =
        value.replace(
            /∞/g,
            '\\\\infty'
        );


    value =
        value.replace(
            /≤/g,
            '\\\\leq'
        );


    value =
        value.replace(
            /≥/g,
            '\\\\geq'
        );


    value =
        value.replace(
            /×/g,
            '\\\\times'
        );


    value =
        value.replace(
            /÷/g,
            '\\\\div'
        );


    return value;
}


// ============================================================
// TYPESCRIPT / MATHJAX-LIKE RENDERING
// ============================================================

function typesetMath()
{
    if (
        window.MathJax &&
        typeof window.MathJax.typesetPromise ===
        'function'
    ) {

        window.MathJax
            .typesetPromise([
                questionText,
                optionsContainer
            ])
            .catch(
                error =>
                    console.warn(
                        'Math rendering:',
                        error
                    )
            );
    }
}


// ============================================================
// RENDER QUESTION
// ============================================================

function renderQuestion()
{
    if (
        !questions.length
    ) {
        return;
    }


    const question =
        questions[currentIndex];


    const total =
        questions.length;


    questionNumber.textContent =
        'Question ' +
        (currentIndex + 1) +
        ' of ' +
        total;


    yearBadge.textContent =
        question.year ||
        SELECTED_YEAR ||
        'JAMB';


    questionText.innerHTML =
        formatMathText(
            question.question
        );


    optionsContainer.innerHTML =
        '';


    const options =
        Array.isArray(question.options)
            ? question.options
            : [];


    options.forEach(
        (option, index) =>
        {

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
                    65 + index
                );


            const letterBox =
                document.createElement(
                    'span'
                );

            letterBox.className =
                'option-letter';

            letterBox.textContent =
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
                letterBox
            );

            button.appendChild(
                content
            );


            const saved =
                userAnswers[currentIndex];


            if (
                saved &&
                saved.index === index
            ) {

                button.classList.add(
                    'selected'
                );

                if (
                    saved.correct
                ) {

                    button.classList.add(
                        'correct'
                    );

                } else {

                    button.classList.add(
                        'wrong'
                    );
                }
            }


            button.addEventListener(
                'click',
                function ()
                {
                    selectAnswer(
                        index,
                        option
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


    nextBtn.textContent =
        currentIndex ===
        total - 1
            ? 'Finish'
            : 'Next';


    const percentage =
        Math.round(
            (
                (currentIndex + 1) /
                total
            ) * 100
        );


    progressQuestion.textContent =
        'Question ' +
        (currentIndex + 1) +
        ' of ' +
        total;


    progressPercent.textContent =
        percentage +
        '%';


    progressFill.style.width =
        percentage +
        '%';


    renderPalette();


    typesetMath();
}


// ============================================================
// SELECT ANSWER
// ============================================================

function selectAnswer(
    optionIndex,
    optionText
)
{

    const question =
        questions[currentIndex];


    const correctAnswer =
        String(
            question.answer ||
            ''
        )
        .trim()
        .toUpperCase();


    const selectedLetter =
        String.fromCharCode(
            65 + optionIndex
        );


    const isCorrect =
        selectedLetter ===
        correctAnswer;


    // Do not allow the question
    // to add points repeatedly.

    const previous =
        userAnswers[currentIndex];


    if (
        !previous &&
        isCorrect
    ) {

        score++;
    }


    userAnswers[currentIndex] = {

        index:
            optionIndex,

        answer:
            optionText,

        correct:
            isCorrect
    };


    answeredQuestions.add(
        currentIndex
    );


    renderQuestion();
}


// ============================================================
// QUESTION PALETTE
// ============================================================

function renderPalette()
{
    questionPalette.innerHTML =
        '';


    questions.forEach(
        function (_, index)
        {

            const button =
                document.createElement(
                    'button'
                );


            button.type =
                'button';


            button.className =
                'palette-btn';


            button.textContent =
                index + 1;


            if (
                index ===
                currentIndex
            ) {

                button.classList.add(
                    'current'
                );
            }


            if (
                userAnswers[index]
            ) {

                button.classList.add(
                    'answered'
                );
            }


            button.addEventListener(
                'click',
                function ()
                {
                    currentIndex =
                        index;

                    resultCard.classList.remove(
                        'active'
                    );

                    renderQuestion();
                }
            );


            questionPalette.appendChild(
                button
            );

        }
    );
}


// ============================================================
// PREVIOUS
// ============================================================

previousBtn.addEventListener(
    'click',
    function ()
    {

        if (
            currentIndex > 0
        ) {

            currentIndex--;

            renderQuestion();
        }

    }
);


// ============================================================
// NEXT
// ============================================================

nextBtn.addEventListener(
    'click',
    function ()
    {

        if (
            currentIndex <
            questions.length - 1
        ) {

            currentIndex++;

            renderQuestion();

            return;
        }


        finishPractice();

    }
);


// ============================================================
// FINISH PRACTICE
// ============================================================

function finishPractice()
{
    const total =
        questions.length;


    const percentage =
        total
            ? Math.round(
                (score / total) *
                100
            )
            : 0;


    resultScore.textContent =
        percentage +
        '%';


    resultSummary.textContent =
        'You scored ' +
        score +
        ' out of ' +
        total +
        ' questions.';


    resultCard.classList.add(
        'active'
    );


    resultCard.scrollIntoView({
        behavior:
            'smooth',
        block:
            'center'
    });
}


// ============================================================
// RESTART
// ============================================================

restartBtn.addEventListener(
    'click',
    function ()
    {

        userAnswers =
            {};

        answeredQuestions =
            new Set();

        score =
            0;

        currentIndex =
            0;


        resultCard.classList.remove(
            'active'
        );


        if (
            questions.length > 1
        ) {

            questions =
                shuffleArray(
                    questions
                );
        }


        renderQuestion();

    }
);


// ============================================================
// CALCULATOR
// ============================================================

const calculatorOverlay =
    document.getElementById(
        'calculatorOverlay'
    );

const calculatorDisplay =
    document.getElementById(
        'calculatorDisplay'
    );

const calculatorClose =
    document.getElementById(
        'calculatorClose'
    );


let calculatorValue =
    '';


function updateCalculator()
{
    calculatorDisplay.textContent =
        calculatorValue ||
        '0';
}


document
    .querySelectorAll(
        '[data-calc]'
    )
    .forEach(
        button =>
        {

            button.addEventListener(
                'click',
                function ()
                {

                    const value =
                        this.dataset.calc;


                    if (
                        value ===
                        'AC'
                    ) {

                        calculatorValue =
                            '';

                        updateCalculator();

                        return;
                    }


                    if (
                        value ===
                        'DEL'
                    ) {

                        calculatorValue =
                            calculatorValue.slice(
                                0,
                                -1
                            );

                        updateCalculator();

                        return;
                    }


                    if (
                        value ===
                        '='
                    ) {

                        calculateResult();

                        return;
                    }


                    calculatorValue +=
                        value;

                    updateCalculator();

                }
            );

        }
    );


function calculateResult()
{
    try {

        // Restrict calculator
        // evaluation to mathematical
        // characters only.

        if (
            !/^[0-9+\-*/().\s]+$/.test(
                calculatorValue
            )
        ) {

            calculatorValue =
                'Error';

            updateCalculator();

            return;
        }


        const result =
            Function(
                '"use strict"; return (' +
                calculatorValue +
                ')'
            )();


        if (
            Number.isFinite(result)
        ) {

            calculatorValue =
                String(result);

        } else {

            calculatorValue =
                'Error';
        }

    } catch (error) {

        calculatorValue =
            'Error';

    }


    updateCalculator();
}


// ============================================================
// OPEN CALCULATOR
// Only useful for Mathematics,
// but available inside CBT mode.
// ============================================================

function addCalculatorButton()
{
    if (
        document.getElementById(
            'calculatorOpen'
        )
    ) {
        return;
    }


    const button =
        document.createElement(
            'button'
        );


    button.type =
        'button';

    button.id =
        'calculatorOpen';

    button.title =
        'Calculator';

    button.style.cssText = `
        position:fixed;
        right:20px;
        bottom:20px;
        width:52px;
        height:52px;
        border:none;
        border-radius:50%;
        background:#0757a0;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        box-shadow:0 8px 25px rgba(0,0,0,.2);
        z-index:500;
    `;


    button.innerHTML = `
        <svg
            width="22"
            height="22"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <rect
                x="4"
                y="2"
                width="16"
                height="20"
                rx="2"
            ></rect>

            <line
                x1="8"
                y1="6"
                x2="16"
                y2="6"
            ></line>

            <line
                x1="8"
                y1="10"
                x2="8"
                y2="10"
            ></line>

            <line
                x1="12"
                y1="10"
                x2="12"
                y2="10"
            ></line>

            <line
                x1="16"
                y1="10"
                x2="16"
                y2="10"
            ></line>

            <line
                x1="8"
                y1="14"
                x2="8"
                y2="14"
            ></line>

            <line
                x1="12"
                y1="14"
                x2="12"
                y2="14"
            ></line>

            <line
                x1="16"
                y1="14"
                x2="16"
                y2="14"
            ></line>
        </svg>
    `;


    button.addEventListener(
        'click',
        function ()
        {
            calculatorOverlay.classList.add(
                'active'
            );
        }
    );


    document.body.appendChild(
        button
    );
}


calculatorClose.addEventListener(
    'click',
    function ()
    {
        calculatorOverlay.classList.remove(
            'active'
        );
    }
);


calculatorOverlay.addEventListener(
    'click',
    function (event)
    {

        if (
            event.target ===
            calculatorOverlay
        ) {

            calculatorOverlay.classList.remove(
                'active'
            );
        }

    }
);


// ============================================================
// INITIALIZATION
// ============================================================

document.addEventListener(
    'DOMContentLoaded',
    function ()
    {

        if (
            SELECTED_SUBJECT &&
            !SELECTED_YEAR
        ) {

            loadSubjectYears(
                SELECTED_SUBJECT
            );
        }


        if (
            questions.length
        ) {

            renderQuestion();

            addCalculatorButton();

            cbtShell.scrollIntoView({
                behavior:
                    'smooth',
                block:
                    'start'
            });

        }

    }
);

</script>


<!-- ==========================================================
     OPTIONAL MATHJAX
     Loaded only when needed by questions.
     ========================================================== -->

<script>
window.MathJax = {
    tex: {
        inlineMath: [
            ['\\(', '\\)']
        ],
        displayMath: [
            ['\\[', '\\]']
        ],
        processEscapes: true
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
        fontCache:
            'global'
    }
};
</script>

<script
    async
    src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"
></script>

</body>
</html>
