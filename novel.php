<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// LITERATURE / NOVELS
//
// 10 prescribed Literature texts
//
// NORMAL BOOK FLOW:
//   literature/*.json
//   -> Summary
//   -> Practice Past Questions
//
// THE LEKKI HEADMASTER SPECIAL FLOW:
//   Summary:
//      literature/the_lekki_headmaster.json
//
//   Practice Past Questions:
//      Flexi-JAMB-CBT-App-/question_bank/the_lekki_headmaster.json
//
//   Cover:
//      literature/assets/images (1) (11).jpeg
// ============================================================

declare(strict_types=1);


// ============================================================
// HELPER: ESCAPE HTML
// ============================================================
function esc($value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


// ============================================================
// HELPER: LOAD JSON FILE
// ============================================================
function loadJsonFile(string $file): ?array
{
    if (!is_file($file)) {
        return null;
    }

    $contents = @file_get_contents($file);

    if ($contents === false || trim($contents) === '') {
        return null;
    }

    $data = json_decode($contents, true);

    if (!is_array($data)) {
        return null;
    }

    return $data;
}


// ============================================================
// HELPER: GET BOOK INFORMATION FROM DIFFERENT JSON STRUCTURES
//
// Some existing Literature JSON files use:
//
//   play_info.title
//
// while others use:
//
//   play.title
//   play
//
// This function keeps the original JSON structures untouched.
// ============================================================
function getBookInfo(array $data): array
{
    $info = [];

    if (isset($data['play_info']) && is_array($data['play_info'])) {
        $info = $data['play_info'];
    } elseif (isset($data['play']) && is_array($data['play'])) {
        $info = $data['play'];
    }

    $title = '';

    if (isset($info['title'])) {
        $title = (string)$info['title'];
    } elseif (isset($data['title'])) {
        $title = (string)$data['title'];
    } elseif (isset($data['play']) && is_string($data['play'])) {
        $title = $data['play'];
    }

    $author = '';

    if (isset($info['author'])) {
        $author = (string)$info['author'];
    } elseif (isset($data['author'])) {
        $author = (string)$data['author'];
    }

    $category = '';

    if (isset($info['category'])) {
        $category = (string)$info['category'];
    } elseif (isset($data['category'])) {
        $category = (string)$data['category'];
    }

    $summary = [];

    if (
        isset($info['summary']) &&
        is_array($info['summary'])
    ) {
        $summary = $info['summary'];
    } elseif (
        isset($data['summary']) &&
        is_array($data['summary'])
    ) {
        $summary = $data['summary'];
    }

    return [
        'title' => trim($title),
        'author' => trim($author),
        'category' => trim($category),
        'summary' => $summary
    ];
}


// ============================================================
// HELPER: GET QUESTIONS
// ============================================================
function getQuestions(array $data): array
{
    if (
        isset($data['cbt_questions']) &&
        is_array($data['cbt_questions'])
    ) {
        return $data['cbt_questions'];
    }

    if (
        isset($data['questions']) &&
        is_array($data['questions'])
    ) {
        return $data['questions'];
    }

    // Some question-bank files may themselves be a JSON array.
    if (array_is_list($data)) {
        return $data;
    }

    return [];
}


// ============================================================
// BOOK DEFINITIONS
//
// The JSON files are the source of truth for title,
// author, category, summary and questions.
//
// Covers are mapped to the actual files in:
// literature/assets/
// ============================================================
$books = [

    [
        'slug' => 'a_man_for_all_seasons',
        'json' => 'a_man_for_all_seasons.json',
        'cover' => 'images (1) (7).jpeg'
    ],

    [
        'slug' => 'an_inspector_calls',
        'json' => 'an_inspector_calls.json',
        'cover' => '900681.jpg'
    ],

    [
        'slug' => 'anthony_and_cleopatra',
        'json' => 'anthony_and_cleopatra.json',
        'cover' => 'images (1) (8).jpeg'
    ],

    [
        'slug' => 'marriage_of_anansewa',
        'json' => 'marriage_of_anansewa.json',
        'cover' => 'images (1) (9).jpeg'
    ],

    [
        'slug' => 'once_upon_an_elephant',
        'json' => 'once_upon_an_elephant.json',
        'cover' => 'YqbrGgId8v6Igs9csPyBI0EyOHmozgFA80Uj7Xge.jpg'
    ],

    [
        'slug' => 'path_of_lucas',
        'json' => 'path_of_lucas.json',
        'cover' => 'images.webp'
    ],

    [
        'slug' => 'redemption_road',
        'json' => 'redemption_road.json',
        'cover' => 'zWSQxM9AcVr695nVa7wXiUQoxvEcILN2i77fXWZb.jpg'
    ],

    [
        'slug' => 'so_the_path_does_not_die',
        'json' => 'so_the_path_does_not_die.json',
        'cover' => 'images (1) (6).jpeg'
    ],

    [
        'slug' => 'to_kill_a_mockingbird',
        'json' => 'to_kill_a_mockingbird.json',
        'cover' => 'images (1) (10).jpeg'
    ],

    // ========================================================
    // THE LEKKI HEADMASTER
    // ========================================================
    [
        'slug' => 'the_lekki_headmaster',
        'json' => 'the_lekki_headmaster.json',
        'cover' => 'images (1) (11).jpeg',
        'special_practice' => true
    ]
];


// ============================================================
// FIND REQUESTED BOOK
// ============================================================
$requestedSlug = isset($_GET['book'])
    ? trim((string)$_GET['book'])
    : '';

$requestedView = isset($_GET['view'])
    ? trim((string)$_GET['view'])
    : '';

$currentBook = null;

if ($requestedSlug !== '') {

    foreach ($books as $book) {

        if ($book['slug'] === $requestedSlug) {
            $currentBook = $book;
            break;
        }
    }
}


// ============================================================
// PAGE MODE
// ============================================================
$isDetailPage = ($currentBook !== null);

if ($isDetailPage && !in_array(
    $requestedView,
    ['summary', 'practice'],
    true
)) {
    $requestedView = 'summary';
}


// ============================================================
// LOAD CURRENT BOOK DATA
// ============================================================
$currentData = null;
$currentInfo = null;
$currentQuestions = [];

if ($isDetailPage) {

    $jsonPath =
        __DIR__ .
        DIRECTORY_SEPARATOR .
        'literature' .
        DIRECTORY_SEPARATOR .
        $currentBook['json'];

    $currentData = loadJsonFile($jsonPath);

    if ($currentData !== null) {

        $currentInfo = getBookInfo($currentData);

        $currentQuestions = getQuestions($currentData);
    }

    // --------------------------------------------------------
    // THE LEKKI HEADMASTER SPECIAL PRACTICE SOURCE
    // --------------------------------------------------------
    if (
        !empty($currentBook['special_practice']) &&
        $requestedView === 'practice'
    ) {

        $lekkiUrl =
            'https://raw.githubusercontent.com/' .
            'flexisystems2000/' .
            'Flexi-JAMB-CBT-App-/' .
            'refs/heads/main/' .
            'question_bank/' .
            'the_lekki_headmaster.json';

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

        $remoteQuestions = @file_get_contents(
            $lekkiUrl,
            false,
            $context
        );

        if ($remoteQuestions !== false) {

            $decodedQuestions =
                json_decode(
                    $remoteQuestions,
                    true
                );

            if (is_array($decodedQuestions)) {

                if (array_is_list($decodedQuestions)) {
                    $currentQuestions =
                        $decodedQuestions;
                } elseif (
                    isset($decodedQuestions['cbt_questions']) &&
                    is_array($decodedQuestions['cbt_questions'])
                ) {
                    $currentQuestions =
                        $decodedQuestions['cbt_questions'];
                }
            }
        }
    }
}


// ============================================================
// BUILD COVER URL
// ============================================================
function coverUrl(array $book): string
{
    return
        'literature/assets/' .
        rawurlencode($book['cover']);
}


// ============================================================
// BUILD BOOK URL
// ============================================================
function bookUrl(
    string $slug,
    string $view = 'summary'
): string
{
    return
        'novel.php?book=' .
        rawurlencode($slug) .
        '&view=' .
        rawurlencode($view);
}


// ============================================================
// PAGE TITLE
// ============================================================
$pageTitle = 'Literature | Flexi Educational Consult';

if ($isDetailPage && $currentInfo !== null) {

    $detailTitle =
        $currentInfo['title'] !== ''
            ? $currentInfo['title']
            : 'Literature';

    $pageTitle =
        esc($detailTitle) .
        ' | Flexi Educational Consult';
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

    <title><?= $pageTitle ?></title>

    <meta
        name="description"
        content="Study prescribed Literature texts, read summaries and practise past questions with Flexi Educational Consult."
    >

    <meta
        name="theme-color"
        content="#003366"
    >

    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family:
                Inter,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
            background: #f5f7fa;
            color: #172033;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .site-header {
            background: #003366;
            color: #ffffff;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow:
                0 2px 12px rgba(0, 0, 0, 0.12);
        }

        .header-inner {
            width: min(1180px, 94%);
            margin: 0 auto;
            min-height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: #2E8B57;
            display: grid;
            place-items: center;
            font-weight: 800;
            color: #ffffff;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 16px;
            font-weight: 800;
            white-space: nowrap;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav a {
            padding: 9px 11px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }

        .nav a:hover,
        .nav a.active {
            background: rgba(255, 255, 255, 0.12);
        }


        /* =====================================================
           PAGE CONTAINER
        ===================================================== */

        .page {
            width: min(1180px, 94%);
            margin: 0 auto;
            padding: 28px 0 60px;
        }


        /* =====================================================
           BREADCRUMB
        ===================================================== */

        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
            margin-bottom: 18px;
            color: #64748b;
            font-size: 13px;
        }

        .breadcrumb a {
            color: #003366;
            font-weight: 700;
        }


        /* =====================================================
           PAGE HERO
        ===================================================== */

        .page-hero {
            background: #ffffff;
            border: 1px solid #e4e9ef;
            border-radius: 14px;
            padding: 26px;
            margin-bottom: 25px;
        }

        .page-hero h1 {
            margin: 0 0 8px;
            color: #003366;
            font-size: clamp(25px, 4vw, 36px);
            line-height: 1.2;
        }

        .page-hero p {
            margin: 0;
            max-width: 760px;
            color: #5d6878;
            font-size: 15px;
        }


        /* =====================================================
           NOVEL GRID
        ===================================================== */

        .novel-grid {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .novel-card {
            background: #ffffff;
            border: 1px solid #e2e7ed;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition:
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .novel-card:hover {
            transform: translateY(-2px);
            box-shadow:
                0 10px 30px rgba(0, 51, 102, 0.09);
        }

        .cover-wrap {
            background: #e9edf2;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cover-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .novel-content {
            padding: 17px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .book-number {
            color: #2E8B57;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .novel-title {
            margin: 0 0 6px;
            color: #003366;
            font-size: 18px;
            line-height: 1.3;
        }

        .novel-author {
            color: #657183;
            font-size: 13px;
            margin-bottom: 11px;
        }

        .novel-description {
            color: #596575;
            font-size: 13px;
            line-height: 1.55;
            margin: 0 0 17px;

            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }


        /* =====================================================
           TWO PILL BUTTONS
        ===================================================== */

        .novel-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: auto;
        }

        .pill-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 8px 10px;
            border-radius: 999px;
            border: 1px solid #003366;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
            transition: 0.18s ease;
        }

        .pill-btn.summary {
            background: #003366;
            color: #ffffff;
        }

        .pill-btn.summary:hover {
            background: #00284f;
        }

        .pill-btn.practice {
            background: #ffffff;
            color: #003366;
        }

        .pill-btn.practice:hover {
            background: #eef4f8;
        }


        /* =====================================================
           DETAIL PAGE
        ===================================================== */

        .detail-card {
            background: #ffffff;
            border: 1px solid #e2e7ed;
            border-radius: 15px;
            overflow: hidden;
        }

        .detail-top {
            padding: 22px;
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 24px;
            border-bottom: 1px solid #e8ecf0;
        }

        .detail-cover {
            width: 180px;
            height: 230px;
            border-radius: 10px;
            overflow: hidden;
            background: #e9edf2;
        }

        .detail-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-info h1 {
            margin: 0 0 7px;
            color: #003366;
            font-size: clamp(25px, 4vw, 35px);
            line-height: 1.2;
        }

        .detail-author {
            color: #596575;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .detail-meta {
            display: inline-flex;
            padding: 5px 10px;
            border-radius: 999px;
            background: #edf7f1;
            color: #237246;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 18px;
        }


        /* =====================================================
           DETAIL PILLS
        ===================================================== */

        .detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
        }

        .detail-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 9px 17px;
            border-radius: 999px;
            border: 1px solid #003366;
            font-size: 13px;
            font-weight: 800;
        }

        .detail-pill.primary {
            background: #003366;
            color: #ffffff;
        }

        .detail-pill.secondary {
            color: #003366;
            background: #ffffff;
        }


        /* =====================================================
           SUMMARY CONTENT
        ===================================================== */

        .reading-area {
            padding: 24px;
        }

        .section-heading {
            margin: 0 0 10px;
            color: #003366;
            font-size: 22px;
        }

        .reading-area h3 {
            color: #003366;
            margin: 24px 0 8px;
            font-size: 18px;
        }

        .reading-area p {
            margin: 0 0 14px;
            color: #4e5968;
            font-size: 15px;
        }


        /* =====================================================
           PRACTICE QUESTIONS
        ===================================================== */

        .practice-intro {
            background: #f1f6fa;
            border: 1px solid #dce8f0;
            border-radius: 11px;
            padding: 14px 16px;
            margin-bottom: 20px;
            color: #475569;
            font-size: 14px;
        }

        .question-card {
            border: 1px solid #e0e6ec;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 15px;
            background: #ffffff;
        }

        .question-number {
            color: #2E8B57;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .question-text {
            color: #1f2937;
            font-weight: 700;
            font-size: 15px;
            line-height: 1.55;
            margin-bottom: 13px;
        }

        .options {
            display: grid;
            gap: 7px;
        }

        .option {
            border: 1px solid #e1e6eb;
            border-radius: 8px;
            padding: 9px 11px;
            color: #4b5563;
            font-size: 14px;
            background: #fafbfc;
        }

        .question-year {
            display: inline-block;
            margin-top: 12px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eef2f6;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
        }

        .answer-box {
            margin-top: 12px;
            padding: 9px 11px;
            border-radius: 8px;
            background: #edf7f1;
            color: #237246;
            font-size: 13px;
            font-weight: 800;
        }


        /* =====================================================
           EMPTY / ERROR
        ===================================================== */

        .notice {
            padding: 18px;
            border-radius: 11px;
            background: #fff8e6;
            border: 1px solid #f0dfad;
            color: #765b16;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .site-footer {
            background: #003366;
            color: #dbe7f2;
            margin-top: 20px;
        }

        .footer-inner {
            width: min(1180px, 94%);
            margin: 0 auto;
            padding: 25px 0;
            text-align: center;
        }

        .footer-inner strong {
            color: #ffffff;
        }

        .footer-inner p {
            margin: 5px 0;
            font-size: 13px;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 900px) {

            .novel-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .nav a {
                padding-left: 7px;
                padding-right: 7px;
            }
        }

        @media (max-width: 650px) {

            .header-inner {
                min-height: 60px;
            }

            .brand-text {
                font-size: 14px;
            }

            .nav {
                display: none;
            }

            .page {
                padding-top: 18px;
            }

            .page-hero {
                padding: 20px;
            }

            .novel-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .cover-wrap {
                aspect-ratio: 16 / 9;
            }

            .detail-top {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .detail-cover {
                width: 150px;
                height: 195px;
            }

            .reading-area {
                padding: 18px;
            }

            .novel-actions {
                gap: 7px;
            }

            .pill-btn {
                font-size: 11px;
            }
        }

    </style>

</head>

<body>


<!-- ==========================================================
     HEADER
=========================================================== -->

<header class="site-header">

    <div class="header-inner">

        <a
            class="brand"
            href="index.php"
        >

            <div class="brand-mark">
                F
            </div>

            <div class="brand-text">
                Flexi Educational Consult
            </div>

        </a>


        <nav class="nav">

            <a href="index.php">
                Home
            </a>

            <a href="study.php">
                Study
            </a>

            <a
                href="novel.php"
                class="active"
            >
                Literature
            </a>

            <a href="news.php">
                News
            </a>

        </nav>

    </div>

</header>


<!-- ==========================================================
     MAIN
=========================================================== -->

<main class="page">


<?php if (!$isDetailPage): ?>


    <!-- ======================================================
         NOVELS LISTING
    ======================================================= -->

    <div class="breadcrumb">

        <a href="index.php">
            Home
        </a>

        <span>›</span>

        <span>
            Literature
        </span>

    </div>


    <section class="page-hero">

        <h1>
            Literature
        </h1>

        <p>
            Study the prescribed Literature texts with concise
            summaries and practise past questions for better
            examination preparation.
        </p>

    </section>


    <section class="novel-grid">

        <?php foreach ($books as $index => $book): ?>

            <?php

            $jsonPath =
                __DIR__ .
                DIRECTORY_SEPARATOR .
                'literature' .
                DIRECTORY_SEPARATOR .
                $book['json'];

            $data = loadJsonFile($jsonPath);

            $info = [];

            if ($data !== null) {
                $info = getBookInfo($data);
            }

            $title =
                $info['title'] !== ''
                    ? $info['title']
                    : ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $book['slug']
                        )
                    );

            $author =
                $info['author'] !== ''
                    ? $info['author']
                    : 'Literature';

            $category =
                $info['category'] !== ''
                    ? $info['category']
                    : 'Literature';

            $overview = '';

            if (
                isset($info['summary']['overview']) &&
                is_string($info['summary']['overview'])
            ) {
                $overview =
                    trim(
                        $info['summary']['overview']
                    );
            }

            if ($overview === '') {
                $overview =
                    'Study the summary and practise past questions for this prescribed Literature text.';
            }

            ?>

            <article class="novel-card">


                <!-- COVER -->

                <a
                    class="cover-wrap"
                    href="<?= esc(bookUrl($book['slug'], 'summary')) ?>"
                >

                    <img
                        src="<?= esc(coverUrl($book)) ?>"
                        alt="<?= esc($title) ?> cover"
                        loading="lazy"
                    >

                </a>


                <!-- CONTENT -->

                <div class="novel-content">

                    <div class="book-number">
                        Text <?= $index + 1 ?>
                    </div>

                    <h2 class="novel-title">
                        <?= esc($title) ?>
                    </h2>

                    <div class="novel-author">

                        <?= esc($author) ?>

                        <?php if ($category !== ''): ?>

                            · <?= esc($category) ?>

                        <?php endif; ?>

                    </div>

                    <p class="novel-description">
                        <?= esc($overview) ?>
                    </p>


                    <!-- TWO PILL BUTTONS -->

                    <div class="novel-actions">

                        <a
                            class="pill-btn summary"
                            href="<?= esc(bookUrl($book['slug'], 'summary')) ?>"
                        >
                            Summary
                        </a>

                        <a
                            class="pill-btn practice"
                            href="<?= esc(bookUrl($book['slug'], 'practice')) ?>"
                        >
                            Practice Past Questions
                        </a>

                    </div>

                </div>

            </article>

        <?php endforeach; ?>

    </section>


<?php else: ?>


    <!-- ======================================================
         DETAIL PAGE
    ======================================================= -->

    <?php

    $title =
        $currentInfo['title'] ?? 'Literature';

    $author =
        $currentInfo['author'] ?? '';

    $category =
        $currentInfo['category'] ?? '';

    $summary =
        $currentInfo['summary'] ?? [];

    $overview =
        isset($summary['overview'])
            ? (string)$summary['overview']
            : '';

    $plotSummary =
        isset($summary['plot_summary'])
            ? (string)$summary['plot_summary']
            : '';

    ?>


    <div class="breadcrumb">

        <a href="index.php">
            Home
        </a>

        <span>›</span>

        <a href="novel.php">
            Literature
        </a>

        <span>›</span>

        <span>
            <?= esc($title) ?>
        </span>

    </div>


    <article class="detail-card">


        <!-- ==================================================
             BOOK HEADER
        =================================================== -->

        <div class="detail-top">


            <div class="detail-cover">

                <img
                    src="<?= esc(coverUrl($currentBook)) ?>"
                    alt="<?= esc($title) ?> cover"
                >

            </div>


            <div class="detail-info">

                <h1>
                    <?= esc($title) ?>
                </h1>

                <?php if ($author !== ''): ?>

                    <div class="detail-author">
                        By <?= esc($author) ?>
                    </div>

                <?php endif; ?>


                <?php if ($category !== ''): ?>

                    <div class="detail-meta">
                        <?= esc($category) ?>
                    </div>

                <?php endif; ?>


                <div class="detail-actions">

                    <a
                        class="detail-pill primary"
                        href="<?= esc(bookUrl($currentBook['slug'], 'summary')) ?>"
                    >
                        Summary
                    </a>

                    <a
                        class="detail-pill secondary"
                        href="<?= esc(bookUrl($currentBook['slug'], 'practice')) ?>"
                    >
                        Practice Past Questions
                    </a>

                    <a
                        class="detail-pill secondary"
                        href="novel.php"
                    >
                        All Novels
                    </a>

                </div>

            </div>

        </div>


        <!-- ==================================================
             SUMMARY VIEW
        =================================================== -->

        <?php if ($requestedView === 'summary'): ?>

            <div class="reading-area">

                <h2 class="section-heading">
                    Summary
                </h2>


                <?php if ($overview !== ''): ?>

                    <h3>
                        Overview
                    </h3>

                    <p>
                        <?= nl2br(esc($overview)) ?>
                    </p>

                <?php endif; ?>


                <?php if ($plotSummary !== ''): ?>

                    <h3>
                        Plot Summary
                    </h3>

                    <p>
                        <?= nl2br(esc($plotSummary)) ?>
                    </p>

                <?php endif; ?>


                <?php if (
                    $overview === '' &&
                    $plotSummary === ''
                ): ?>

                    <div class="notice">

                        A summary has not been added to this
                        Literature text yet.

                    </div>

                <?php endif; ?>

            </div>


        <!-- ==================================================
             PRACTICE VIEW
        =================================================== -->

        <?php else: ?>

            <div class="reading-area">

                <h2 class="section-heading">
                    Practice Past Questions
                </h2>


                <div class="practice-intro">

                    Practise questions from
                    <strong><?= esc($title) ?></strong>.
                    Select the questions below and use them
                    for Literature examination preparation.

                </div>


                <?php if (!empty($currentQuestions)): ?>


                    <?php foreach (
                        $currentQuestions as $questionIndex => $question
                    ): ?>

                        <?php

                        if (!is_array($question)) {
                            continue;
                        }

                        $questionText =
                            (string)(
                                $question['question']
                                ?? $question['text']
                                ?? ''
                            );

                        $options =
                            isset($question['options']) &&
                            is_array($question['options'])
                                ? $question['options']
                                : [];

                        $answer =
                            (string)(
                                $question['answer']
                                ?? ''
                            );

                        $year =
                            (string)(
                                $question['year']
                                ?? ''
                            );

                        ?>

                        <div class="question-card">

                            <div class="question-number">

                                QUESTION
                                <?= $questionIndex + 1 ?>

                            </div>


                            <div class="question-text">

                                <?= nl2br(
                                    esc($questionText)
                                ) ?>

                            </div>


                            <?php if (!empty($options)): ?>

                                <div class="options">

                                    <?php foreach (
                                        $options as $option
                                    ): ?>

                                        <div class="option">

                                            <?= esc($option) ?>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>


                            <?php if ($year !== ''): ?>

                                <span class="question-year">

                                    <?= esc($year) ?>

                                </span>

                            <?php endif; ?>


                            <?php if ($answer !== ''): ?>

                                <div class="answer-box">

                                    Answer:
                                    <?= esc($answer) ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>


                <?php else: ?>

                    <div class="notice">

                        Practice questions could not be loaded
                        at the moment.

                    </div>

                <?php endif; ?>

            </div>

        <?php endif; ?>


    </article>


<?php endif; ?>


</main>


<!-- ==========================================================
     FOOTER
=========================================================== -->

<footer class="site-footer">

    <div class="footer-inner">

        <p>
            <strong>
                Flexi Educational Consult
            </strong>
        </p>

        <p>
            Literature Study Centre
        </p>

        <p>
            © <?= date('Y') ?>
            Flexi Educational Consult.
            All rights reserved.
        </p>

    </div>

</footer>
</body>
</html>
