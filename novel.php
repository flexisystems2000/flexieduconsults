<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// NOVELS / LITERATURE
//
// IMPORTANT:
// This page uses the same HEADER and FOOTER structure as
// index.php.
//
// BOOK DATA:
//   First 9 books:
//      literature/*.json
//
//   The Lekki Headmaster:
//      Summary:
//         literature/the_lekki_headmaster.json
//
//      Practice:
//         Flexi-JAMB-CBT-App-/question_bank/
//         the_lekki_headmaster.json
//
// PRACTICE MODE:
//   One question at a time.
//   Previous / Next navigation.
// ============================================================

declare(strict_types=1);


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
// HELPER: LOAD JSON
// ============================================================
function loadJsonFile($file)
{
    if (!is_file($file)) {
        return null;
    }

    $contents = @file_get_contents($file);

    if (
        $contents === false ||
        trim($contents) === ''
    ) {
        return null;
    }

    $data = json_decode(
        $contents,
        true
    );

    if (!is_array($data)) {
        return null;
    }

    return $data;
}


// ============================================================
// HELPER: GET BOOK INFORMATION
// Supports the different structures used by the existing
// Literature JSON files.
// ============================================================
function getBookInfo($data)
{
    if (!is_array($data)) {
        return [
            'title' => '',
            'author' => '',
            'category' => '',
            'summary' => []
        ];
    }

    $info = [];

    if (
        isset($data['play_info']) &&
        is_array($data['play_info'])
    ) {
        $info = $data['play_info'];
    }
    elseif (
        isset($data['play']) &&
        is_array($data['play'])
    ) {
        $info = $data['play'];
    }

    $title = '';

    if (
        isset($info['title']) &&
        is_string($info['title'])
    ) {
        $title = $info['title'];
    }
    elseif (
        isset($data['title']) &&
        is_string($data['title'])
    ) {
        $title = $data['title'];
    }
    elseif (
        isset($data['play']) &&
        is_string($data['play'])
    ) {
        $title = $data['play'];
    }

    $author = '';

    if (
        isset($info['author']) &&
        is_string($info['author'])
    ) {
        $author = $info['author'];
    }
    elseif (
        isset($data['author']) &&
        is_string($data['author'])
    ) {
        $author = $data['author'];
    }

    $category = '';

    if (
        isset($info['category']) &&
        is_string($info['category'])
    ) {
        $category = $info['category'];
    }
    elseif (
        isset($data['category']) &&
        is_string($data['category'])
    ) {
        $category = $data['category'];
    }

    $summary = [];

    if (
        isset($info['summary']) &&
        is_array($info['summary'])
    ) {
        $summary = $info['summary'];
    }
    elseif (
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
function getQuestions($data)
{
    if (!is_array($data)) {
        return [];
    }

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

    /*
     * PHP 8.1+.
     * array_is_list() confirms that the JSON itself is
     * an array of questions.
     */
    if (
        function_exists('array_is_list') &&
        array_is_list($data)
    ) {
        return $data;
    }

    return [];
}


// ============================================================
// BOOK DEFINITIONS
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

    [
        'slug' => 'the_lekki_headmaster',
        'json' => 'the_lekki_headmaster.json',
        'cover' => 'images (1) (11).jpeg',
        'special_practice' => true
    ]

];


// ============================================================
// REQUEST
// ============================================================
$requestedSlug = isset($_GET['book'])
    ? trim((string)$_GET['book'])
    : '';

$requestedView = isset($_GET['view'])
    ? trim((string)$_GET['view'])
    : '';

$currentBook = null;

foreach ($books as $book) {

    if (
        $requestedSlug !== '' &&
        $book['slug'] === $requestedSlug
    ) {
        $currentBook = $book;
        break;
    }
}


// ============================================================
// DETAIL PAGE?
// ============================================================
$isDetailPage =
    $currentBook !== null;


// ============================================================
// DEFAULT VIEW
// ============================================================
if (
    $isDetailPage &&
    !in_array(
        $requestedView,
        ['summary', 'practice'],
        true
    )
) {
    $requestedView = 'summary';
}


// ============================================================
// QUESTION NUMBER
//
// q=1 means first question.
// q=2 means second question, etc.
// ============================================================
$questionNumber =
    isset($_GET['q'])
        ? max(1, (int)$_GET['q'])
        : 1;


// ============================================================
// CURRENT BOOK DATA
// ============================================================
$currentData = null;

$currentInfo = [
    'title' => '',
    'author' => '',
    'category' => '',
    'summary' => []
];

$currentQuestions = [];


// ============================================================
// LOAD BOOK
// ============================================================
if ($isDetailPage) {

    $jsonPath =
        __DIR__ .
        DIRECTORY_SEPARATOR .
        'literature' .
        DIRECTORY_SEPARATOR .
        $currentBook['json'];

    $currentData =
        loadJsonFile($jsonPath);


    if ($currentData !== null) {

        $currentInfo =
            getBookInfo($currentData);

        $currentQuestions =
            getQuestions($currentData);

    }


    // ========================================================
    // THE LEKKI HEADMASTER
    //
    // Its practice questions come from the JAMB CBT App
    // repository rather than the Literature summary JSON.
    // ========================================================
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


        $context =
            stream_context_create([
                'http' => [
                    'method' => 'GET',

                    'header' =>
                        "Accept: application/json\r\n" .
                        "User-Agent: Flexi-Educational-Consult\r\n",

                    'timeout' => 15,

                    'ignore_errors' => true
                ]
            ]);


        $remoteQuestions =
            @file_get_contents(
                $lekkiUrl,
                false,
                $context
            );


        if (
            $remoteQuestions !== false &&
            trim($remoteQuestions) !== ''
        ) {

            $decodedQuestions =
                json_decode(
                    $remoteQuestions,
                    true
                );


            if (is_array($decodedQuestions)) {

                if (
                    function_exists('array_is_list') &&
                    array_is_list($decodedQuestions)
                ) {

                    $currentQuestions =
                        $decodedQuestions;

                }
                elseif (
                    isset($decodedQuestions['cbt_questions']) &&
                    is_array(
                        $decodedQuestions['cbt_questions']
                    )
                ) {

                    $currentQuestions =
                        $decodedQuestions['cbt_questions'];

                }
                elseif (
                    isset($decodedQuestions['questions']) &&
                    is_array(
                        $decodedQuestions['questions']
                    )
                ) {

                    $currentQuestions =
                        $decodedQuestions['questions'];

                }

            }

        }

    }

}


// ============================================================
// SAFE BOOK VALUES
// ============================================================
$title =
    isset($currentInfo['title'])
        ? trim((string)$currentInfo['title'])
        : '';

$author =
    isset($currentInfo['author'])
        ? trim((string)$currentInfo['author'])
        : '';

$category =
    isset($currentInfo['category'])
        ? trim((string)$currentInfo['category'])
        : '';

$summary =
    isset($currentInfo['summary']) &&
    is_array($currentInfo['summary'])
        ? $currentInfo['summary']
        : [];

$overview =
    isset($summary['overview'])
        ? trim((string)$summary['overview'])
        : '';

$plotSummary =
    isset($summary['plot_summary'])
        ? trim((string)$summary['plot_summary'])
        : '';


// ============================================================
// URL HELPERS
// ============================================================
function bookUrl(
    $slug,
    $view = 'summary',
    $question = null
) {

    $url =
        'novel.php?book=' .
        rawurlencode($slug) .
        '&view=' .
        rawurlencode($view);

    if ($question !== null) {

        $url .=
            '&q=' .
            max(1, (int)$question);
    }

    return $url;
}


function coverUrl($book)
{
    return
        'literature/assets/' .
        rawurlencode($book['cover']);
}


// ============================================================
// PRACTICE NAVIGATION DATA
// ============================================================
$totalQuestions =
    count($currentQuestions);

if ($totalQuestions > 0) {

    if ($questionNumber > $totalQuestions) {
        $questionNumber = $totalQuestions;
    }

    $currentQuestion =
        $currentQuestions[
            $questionNumber - 1
        ];

}
else {

    $currentQuestion = null;

}


$previousQuestion =
    $questionNumber - 1;

$nextQuestion =
    $questionNumber + 1;


// ============================================================
// PAGE TITLE
// ============================================================
$pageTitle =
    $isDetailPage && $title !== ''
        ? $title . ' | Flexi Educational Consult'
        : 'Literature | Flexi Educational Consult';


// ============================================================
// CURRENT YEAR
// ============================================================
$currentYear = date('Y');

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="theme-color"
        content="#003366">

    <meta
        name="description"
        content="Study prescribed Literature texts, summaries and practice questions with Flexi Educational Consult.">

    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">

    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">

    <title>
        <?= esc($pageTitle) ?>
    </title>


<style>

/* ============================================================
   SAME CORE COLORS AS INDEX.PHP
============================================================ */

:root {

    --blue: #003366;

    --green: #2E8B57;

    --yellow: #FFD700;

    --bg: #f4f7f6;

    --radius: 12px;

    --shadow:
        0 4px 18px rgba(0,0,0,0.06);

}


/* ============================================================
   GLOBAL
============================================================ */

* {

    box-sizing: border-box;

    -webkit-user-select: none;

    -moz-user-select: none;

    -ms-user-select: none;

    user-select: none;

}


body {

    font-family:
        'Segoe UI',
        system-ui,
        -apple-system,
        sans-serif;

    background: var(--bg);

    margin: 0;

    padding: 0;

    color: #333;

}


a {

    text-decoration: none;

}


/* ============================================================
   EXACT INDEX.PHP HEADER
============================================================ */

header {

    background: var(--blue);

    color: white;

    height: 52px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 20px;

    border-bottom: 3px solid var(--green);

    position: sticky;

    top: 0;

    z-index: 1000;

}


.header-left {

    display: flex;

    align-items: center;

    gap: 12px;

}


.logo-img {

    height: 34px;

    width: 34px;

    object-fit: contain;

    border-radius: 4px;

}


.brand-name {

    font-size: 15px;

    font-weight: 700;

    letter-spacing: 0.2px;

}


.menu-container {

    position: relative;

}


.menu-btn {

    width: 38px;

    height: 38px;

    cursor: pointer;

    background:
        rgba(255,255,255,.06);

    border:
        1px solid rgba(255,255,255,.32);

    border-radius: 8px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: white;

}


.menu-svg {

    width: 21px;

    height: 21px;

    display: block;

}


.square-menu {

    display: none;

    position: absolute;

    top: 48px;

    right: 0;

    width: 300px;

    background:
        linear-gradient(
            145deg,
            #003366 0%,
            #075a55 52%,
            #2E8B57 100%
        );

    border: 2px solid var(--green);

    border-radius: 10px;

    z-index: 2000;

    box-shadow:
        0 12px 32px rgba(0,0,0,.35);

    overflow: hidden;

}


.square-menu.menu-open {

    display: block;

    animation:
        menuDrop
        .18s
        ease-out;

}


@keyframes menuDrop {

    from {

        opacity: 0;

        transform:
            translateY(-6px)
            scale(.98);

    }

    to {

        opacity: 1;

        transform:
            translateY(0)
            scale(1);

    }

}


.square-menu a,
.study-menu-toggle {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 13px 15px;

    color: white;

    text-decoration: none;

    font-size: 14px;

    border-bottom:
        1px solid rgba(255,255,255,.1);

    transition:
        background .15s,
        padding-left .15s;

}


.square-menu a:hover,
.study-menu-toggle:hover {

    background:
        rgba(255,255,255,0.12);

    padding-left: 19px;

}


.study-menu-toggle {

    width: 100%;

    background: transparent;

    border-top: none;

    border-left: none;

    border-right: none;

    text-align: left;

    cursor: pointer;

}


.menu-icon {

    width: 21px;

    height: 21px;

    flex:
        0 0 21px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    color:
        rgba(255,255,255,.96);

}


.menu-icon svg {

    width: 20px;

    height: 20px;

    display: block;

}


.menu-label {

    min-width: 0;

    line-height: 1.35;

}


.study-chevron {

    width: 18px;

    height: 18px;

    margin-left: auto;

    transition:
        transform .2s ease;

}


.study-menu-toggle[aria-expanded="true"]
.study-chevron {

    transform:
        rotate(180deg);

}


.study-submenu {

    display: none;

    margin:
        0 8px 5px 34px;

    padding: 4px;

    border-left:
        1px solid rgba(255,255,255,.20);

    background:
        rgba(0,0,0,.10);

    border-radius:
        0 8px 8px 0;

}


.study-submenu.open {

    display: block;

}


.study-submenu a {

    min-height: 40px;

    padding:
        9px 10px;

    font-size: 13px;

    font-weight: 500;

}


.study-submenu .menu-icon {

    width: 18px;

    height: 18px;

}


.study-submenu .menu-icon svg {

    width: 18px;

    height: 18px;

}


/* ============================================================
   PAGE CONTAINER
============================================================ */

.container {

    max-width: 1180px;

    margin:
        24px auto 46px;

    width: 94%;

}


/* ============================================================
   PAGE INTRO
============================================================ */

.page-heading {

    background: white;

    border-radius:
        var(--radius);

    box-shadow:
        var(--shadow);

    padding:
        24px 28px;

    margin-bottom: 20px;

    border-left:
        5px solid var(--green);

}


.page-heading h1 {

    margin:
        0 0 8px;

    color:
        var(--blue);

    font-size:
        1.8rem;

}


.page-heading p {

    margin: 0;

    color: #64748b;

    font-size: 14px;

}


/* ============================================================
   NOVEL GRID
============================================================ */

.novel-grid {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 18px;

}


.novel-card {

    background: white;

    border-radius:
        var(--radius);

    overflow: hidden;

    box-shadow:
        var(--shadow);

    border:
        1px solid #e5e7eb;

    display: flex;

    flex-direction: column;

}


.novel-cover {

    width: 100%;

    height: 190px;

    object-fit: cover;

    background: #eef2f4;

}


.novel-content {

    padding:
        14px;

    display: flex;

    flex-direction: column;

    flex: 1;

}


.book-number {

    color:
        var(--green);

    font-size:
        11px;

    font-weight:
        800;

    text-transform:
        uppercase;

    margin-bottom:
        3px;

}


.novel-title {

    color:
        var(--blue);

    font-size:
        16px;

    line-height:
        1.3;

    margin:
        0 0 5px;

}


.novel-author {

    color:
        #667085;

    font-size:
        12px;

    margin-bottom:
        8px;

}


.novel-description {

    color:
        #596575;

    font-size:
        12px;

    line-height:
        1.55;

    margin:
        0 0 14px;

    display:
        -webkit-box;

    -webkit-line-clamp: 4;

    -webkit-box-orient:
        vertical;

    overflow:
        hidden;

}


.novel-actions {

    display:
        grid;

    grid-template-columns:
        1fr 1fr;

    gap:
        7px;

    margin-top:
        auto;

}


.pill-btn {

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    text-align:
        center;

    min-height:
        40px;

    padding:
        7px 8px;

    border-radius:
        999px;

    font-size:
        11px;

    font-weight:
        800;

}


.pill-summary {

    background:
        var(--blue);

    color:
        white;

}


.pill-practice {

    background:
        white;

    color:
        var(--blue);

    border:
        1px solid var(--blue);

}


/* ============================================================
   DETAIL PAGE
============================================================ */

.detail-card {

    background:
        white;

    border-radius:
        var(--radius);

    box-shadow:
        var(--shadow);

    overflow:
        hidden;

}


.detail-header {

    display:
        grid;

    grid-template-columns:
        170px 1fr;

    gap:
        24px;

    padding:
        24px;

    border-bottom:
        1px solid #e5e7eb;

}


.detail-cover {

    width:
        170px;

    height:
        220px;

    object-fit:
        cover;

    border-radius:
        9px;

}


.detail-info h1 {

    color:
        var(--blue);

    margin:
        0 0 7px;

    font-size:
        1.8rem;

    line-height:
        1.25;

}


.detail-author {

    color:
        #667085;

    font-size:
        14px;

    margin-bottom:
        12px;

}


.detail-category {

    display:
        inline-block;

    background:
        #edf7f1;

    color:
        #237246;

    border-radius:
        999px;

    padding:
        5px 10px;

    font-size:
        11px;

    font-weight:
        800;

    margin-bottom:
        17px;

}


.detail-actions {

    display:
        flex;

    flex-wrap:
        wrap;

    gap:
        8px;

}


.detail-pill {

    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    min-height:
        40px;

    padding:
        8px 15px;

    border-radius:
        999px;

    border:
        1px solid var(--blue);

    font-size:
        12px;

    font-weight:
        800;

}


.detail-pill.primary {

    background:
        var(--blue);

    color:
        white;

}


.detail-pill.secondary {

    background:
        white;

    color:
        var(--blue);

}


/* ============================================================
   READING AREA
============================================================ */

.reading-area {

    padding:
        25px;

}


.reading-area h2 {

    color:
        var(--blue);

    margin:
        0 0 18px;

    font-size:
        1.3rem;

}


.reading-area h3 {

    color:
        var(--blue);

    margin:
        24px 0 8px;

    font-size:
        1rem;

}


.reading-area p {

    color:
        #4b5563;

    font-size:
        14px;

    line-height:
        1.7;

    margin:
        0 0 14px;

}


/* ============================================================
   PRACTICE
============================================================ */

.practice-top {

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


.question-counter {

    color:
        #64748b;

    font-size:
        13px;

    font-weight:
        700;

}


.question-card {

    background:
        #ffffff;

    border:
        1px solid #e1e7ec;

    border-radius:
        12px;

    padding:
        20px;

}


.question-label {

    color:
        var(--green);

    font-size:
        11px;

    font-weight:
        800;

    text-transform:
        uppercase;

    margin-bottom:
        8px;

}


.question-text {

    color:
        #172033;

    font-size:
        16px;

    font-weight:
        700;

    line-height:
        1.6;

    margin-bottom:
        17px;

}


.options {

    display:
        grid;

    gap:
        9px;

}


.option {

    padding:
        11px 13px;

    border:
        1px solid #e1e6eb;

    border-radius:
        8px;

    background:
        #f9fafb;

    color:
        #374151;

    font-size:
        14px;

    line-height:
        1.5;

}


.question-year {

    display:
        inline-block;

    margin-top:
        14px;

    padding:
        4px 9px;

    background:
        #eef2f6;

    border-radius:
        999px;

    color:
        #64748b;

    font-size:
        11px;

    font-weight:
        700;

}


/* ============================================================
   PREVIOUS / NEXT NAVIGATION
============================================================ */

.practice-navigation {

    display:
        grid;

    grid-template-columns:
        1fr auto 1fr;

    align-items:
        center;

    gap:
        10px;

    margin-top:
        18px;

}


.question-nav-btn {

    min-height:
        45px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    padding:
        9px 15px;

    border-radius:
        999px;

    font-size:
        13px;

    font-weight:
        800;

    border:
        1px solid var(--blue);

}


.question-nav-btn.prev {

    justify-self:
        start;

    color:
        var(--blue);

    background:
        white;

}


.question-nav-btn.next {

    justify-self:
        end;

    color:
        white;

    background:
        var(--blue);

}


.question-nav-btn.disabled {

    opacity:
        .35;

    pointer-events:
        none;

}


.question-position {

    color:
        #64748b;

    font-size:
        12px;

    font-weight:
        800;

    white-space:
        nowrap;

}


.notice {

    background:
        #fff8e6;

    border:
        1px solid #f0dfad;

    color:
        #765b16;

    border-radius:
        10px;

    padding:
        15px;

    font-size:
        14px;

}


/* ============================================================
   EXACT INDEX.PHP FOOTER
============================================================ */

.footer {

    background:
        linear-gradient(
            135deg,
            #011627 0%,
            #032038 100%
        );

    color:
        #e2e8f0;

    padding:
        60px 20px 30px;

    margin-top:
        50px;

    border-top:
        4px solid var(--green);

    font-size:
        14px;

}


.footer-grid {

    display:
        grid;

    grid-template-columns:
        1.8fr
        1.2fr
        1.3fr
        1fr;

    gap:
        35px;

    max-width:
        1200px;

    margin:
        0 auto;

}


.footer h4 {

    color:
        var(--yellow);

    font-size:
        .9rem;

    text-transform:
        uppercase;

    letter-spacing:
        1.2px;

    margin:
        0 0 16px;

    position:
        relative;

    padding-bottom:
        6px;

}


.footer h4::after {

    content:
        '';

    position:
        absolute;

    left:
        0;

    bottom:
        0;

    width:
        24px;

    height:
        2px;

    background:
        var(--yellow);

    opacity:
        .7;

    border-radius:
        2px;

}


.footer-about {

    line-height:
        1.7;

    color:
        #94a3b8;

    margin:
        0;

}


.footer-links-list {

    list-style:
        none;

    padding:
        0;

    margin:
        0;

}


.footer-links-list li {

    margin-bottom:
        10px;

}


.footer a {

    color:
        #cbd5e0;

    text-decoration:
        none;

}


.footer-links-list a:hover {

    color:
        var(--yellow);

}


.whatsapp-channel-link {

    color:
        #25D366 !important;

    font-weight:
        600;

    display:
        inline-block;

}


.contact-group {

    margin-bottom:
        16px;

}


.contact-group h5 {

    color:
        #ffffff;

    font-size:
        .82rem;

    text-transform:
        uppercase;

    letter-spacing:
        .8px;

    margin:
        0 0 6px;

    opacity:
        .9;

}


.contact-group a {

    display:
        block;

    color:
        #94a3b8;

    font-size:
        .88rem;

    margin-bottom:
        4px;

}


.contact-group a:hover {

    color:
        #ffffff;

}


.footer-bottom {

    max-width:
        1200px;

    margin:
        40px auto 0;

    padding-top:
        20px;

    border-top:
        1px solid
        rgba(255,255,255,0.08);

    text-align:
        center;

    font-size:
        13px;

    color:
        #64748b;

}


/* ============================================================
   SUPPORT WIDGET
============================================================ */

#support-btn:hover {

    transform:
        scale(1.1);

}


/* ============================================================
   DESKTOP
============================================================ */

@media (min-width: 768px) {

    header {

        height:
            64px;

        padding:
            0 34px;

    }


    .logo-img {

        height:
            40px;

        width:
            40px;

    }


    .brand-name {

        font-size:
            17px;

    }


    .square-menu {

        top:
            54px;

        width:
            340px;

        border-radius:
            14px;

    }


    .square-menu a,
    .study-menu-toggle {

        min-height:
            50px;

        padding:
            14px 17px;

        font-size:
            14px;

    }


    .study-submenu {

        margin-left:
            38px;

    }

}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 900px) {

    .novel-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }


    .footer-grid {

        grid-template-columns:
            1fr 1fr;

        gap:
            30px;

    }

}


@media (max-width: 600px) {

    .container {

        margin:
            16px auto 35px;

    }


    .page-heading {

        padding:
            18px;

    }


    .page-heading h1 {

        font-size:
            1.5rem;

    }


    .novel-grid {

        grid-template-columns:
            1fr;

        gap:
            15px;

    }


    .novel-cover {

        height:
            205px;

    }


    .detail-header {

        grid-template-columns:
            1fr;

        padding:
            18px;

    }


    .detail-cover {

        width:
            150px;

        height:
            195px;

    }


    .reading-area {

        padding:
            18px;

    }


    .practice-navigation {

        grid-template-columns:
            1fr 1fr;

    }


    .question-position {

        grid-column:
            1 / -1;

        grid-row:
            1;

        text-align:
            center;

        order:
            -1;

    }


    .question-nav-btn.prev {

        grid-column:
            1;

    }


    .question-nav-btn.next {

        grid-column:
            2;

    }


    .footer-grid {

        grid-template-columns:
            1fr;

        gap:
            28px;

    }

}


@media (max-width: 480px) {

    .footer {

        padding:
            45px 20px 25px;

    }

}

</style>

</head>


<body>


<!-- ==========================================================
     EXACT INDEX.PHP HEADER STRUCTURE
=========================================================== -->

<header>

    <div class="header-left">

        <img
            src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg"
            alt="Flexi Educational Consult Official Logo"
            class="logo-img">

        <span class="brand-name">
            Flexi Educational Consult
        </span>

    </div>


    <div class="menu-container">

        <button
            class="menu-btn"
            onclick="toggleMenu()"
            aria-label="Toggle Navigation Menu">

            <svg
                class="menu-svg"
                viewBox="0 0 24 24"
                aria-hidden="true"
                focusable="false">

                <path
                    d="M4 6h16M4 12h16M4 18h16"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"/>

            </svg>

        </button>


        <div
            class="square-menu"
            id="squareMenu">


            <a href="/index.php">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Home
                </span>

            </a>


            <a href="/videos.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"/>

                        <path
                            d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                        <path
                            d="M10 7l5 3-5 3V7z"
                            fill="currentColor"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Watch Video Lessons
                </span>

            </a>


            <button
                type="button"
                class="study-menu-toggle"
                id="study-menu-toggle"
                onclick="toggleStudyMenu(event)"
                aria-expanded="false"
                aria-controls="study-submenu">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"/>

                        <path
                            d="M4 5.5V19M8 7h8M8 11h8"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Study
                </span>

                <svg
                    class="study-chevron"
                    viewBox="0 0 24 24">

                    <path
                        d="m6 9 6 6 6-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>

                </svg>

            </button>


            <div
                class="study-submenu"
                id="study-submenu">


                <a href="/study.php">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">

                            <path
                                d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linejoin="round"/>

                            <path
                                d="M4 5.5V19"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"/>

                        </svg>

                    </span>

                    <span class="menu-label">
                        Past Questions
                    </span>

                </a>


                <a href="/novel.php">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">

                            <path
                                d="M5 4.5A2.5 2.5 0 0 1 7.5 2H19v18H7.5A2.5 2.5 0 0 0 5 22V4.5Z"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linejoin="round"/>

                            <path
                                d="M5 4.5V19M9 7h6M9 11h7M9 15h5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"/>

                        </svg>

                    </span>

                    <span class="menu-label">
                        Novels
                    </span>

                </a>


                <a href="/scholarship.php">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">

                            <path
                                d="M3 8.5 12 4l9 4.5-9 4.5-9-4.5Z"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linejoin="round"/>

                            <path
                                d="M6 11.5V16c2.8 2.2 9.2 2.2 12 0v-4.5M21 9v5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"/>

                        </svg>

                    </span>

                    <span class="menu-label">
                        Scholarships
                    </span>

                </a>

            </div>


            <a href="/download-app.php">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M12 3v11"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                        <path
                            d="M7.5 10.5L12 15l4.5-4.5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>

                        <path
                            d="M5 20h14"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Download Flexi
                </span>

            </a>


            <a href="/syllabus.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v18H6.5A2.5 2.5 0 0 0 4 22V4.5z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"/>

                        <path
                            d="M8 7h8M8 11h8M8 15h5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Access the JAMB and WAEC syllabus here
                </span>

            </a>


            <a href="/brochure.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"/>

                        <path
                            d="M14 3v5h5M8 12h8M8 16h6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Access JAMB Brochure
                </span>

            </a>


            <a href="/cbt.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="13"
                            rx="2"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                        <path
                            d="M8 21h8M12 17v4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    CBT Simulator
                </span>

            </a>


            <a href="/groups.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <circle
                            cx="9"
                            cy="8"
                            r="3"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                        <circle
                            cx="17"
                            cy="9"
                            r="2.5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                        <path
                            d="M3.5 20a5.5 5.5 0 0 1 11 0M15 14.5a4.5 4.5 0 0 1 5 5.5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Classroom (Groups and chats)
                </span>

            </a>


            <a href="/purchase.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="14"
                            rx="2"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                        <path
                            d="M3 10h18M7 15h4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Purchase Scratch Cards
                </span>

            </a>


            <a href="/pdf.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linejoin="round"/>

                        <path
                            d="M14 3v5h5M8 14h8M8 17h6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Get your PDFs from here
                </span>

            </a>


            <a href="/location.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M12 21s7-6.2 7-12A7 7 0 0 0 5 9c0 5.8 7 12 7 12z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                        <circle
                            cx="12"
                            cy="9"
                            r="2.3"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Tutorial Centers Near You
                </span>

            </a>


            <a href="/profile.html">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <circle
                            cx="12"
                            cy="8"
                            r="3.2"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

                        <path
                            d="M5 20a7 7 0 0 1 14 0"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    User Profile
                </span>

            </a>


            <a
                href="/login.html"
                id="auth-menu-btn">

                <span class="menu-icon">

                    <svg viewBox="0 0 24 24">

                        <path
                            d="M10 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-8"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>

                        <path
                            d="M4 12h10M10 8l4 4-4 4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>

                    </svg>

                </span>

                <span class="menu-label">
                    Login
                </span>

            </a>

        </div>

    </div>

</header>


<!-- ==========================================================
     MAIN
=========================================================== -->

<div class="container">


    <?php if (!$isDetailPage): ?>


        <!-- ==================================================
             NOVELS LIST
        =================================================== -->

        <div class="page-heading">

            <h1>
                Literature
            </h1>

            <p>
                Study the prescribed Literature texts with
                concise summaries and practise past questions
                for better examination preparation.
            </p>

        </div>


        <div class="novel-grid">


            <?php foreach ($books as $index => $book): ?>


                <?php

                $jsonPath =
                    __DIR__ .
                    DIRECTORY_SEPARATOR .
                    'literature' .
                    DIRECTORY_SEPARATOR .
                    $book['json'];


                $data =
                    loadJsonFile($jsonPath);


                $info =
                    getBookInfo($data);


                // IMPORTANT:
                // Safe fallbacks prevent PHP warnings from
                // appearing publicly.

                $cardTitle =
                    !empty($info['title'])
                        ? $info['title']
                        : ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $book['slug']
                            )
                        );


                $cardAuthor =
                    !empty($info['author'])
                        ? $info['author']
                        : 'Literature';


                $cardCategory =
                    !empty($info['category'])
                        ? $info['category']
                        : '';


                $cardDescription = '';


                if (
                    isset($info['summary']) &&
                    is_array($info['summary']) &&
                    isset($info['summary']['overview'])
                ) {

                    $cardDescription =
                        trim(
                            (string)
                            $info['summary']['overview']
                        );

                }


                if ($cardDescription === '') {

                    $cardDescription =
                        'Study this prescribed Literature text and practise examination questions.';

                }

                ?>


                <article class="novel-card">


                    <a
                        href="<?= esc(
                            bookUrl(
                                $book['slug'],
                                'summary'
                            )
                        ) ?>">

                        <img
                            class="novel-cover"
                            src="<?= esc(
                                coverUrl($book)
                            ) ?>"
                            alt="<?= esc(
                                $cardTitle
                            ) ?> cover"
                            loading="lazy">

                    </a>


                    <div class="novel-content">


                        <div class="book-number">

                            Text
                            <?= $index + 1 ?>

                        </div>


                        <h2 class="novel-title">

                            <?= esc(
                                $cardTitle
                            ) ?>

                        </h2>


                        <div class="novel-author">

                            <?= esc(
                                $cardAuthor
                            ) ?>

                            <?php if (
                                $cardCategory !== ''
                            ): ?>

                                ·
                                <?= esc(
                                    $cardCategory
                                ) ?>

                            <?php endif; ?>

                        </div>


                        <p class="novel-description">

                            <?= esc(
                                $cardDescription
                            ) ?>

                        </p>


                        <div class="novel-actions">


                            <a
                                class="pill-btn pill-summary"
                                href="<?= esc(
                                    bookUrl(
                                        $book['slug'],
                                        'summary'
                                    )
                                ) ?>">

                                Summary

                            </a>


                            <a
                                class="pill-btn pill-practice"
                                href="<?= esc(
                                    bookUrl(
                                        $book['slug'],
                                        'practice'
                                    )
                                ) ?>">

                                Practice Past Questions

                            </a>


                        </div>


                    </div>

                </article>


            <?php endforeach; ?>


        </div>


    <?php else: ?>


        <!-- ==================================================
             DETAIL PAGE
        =================================================== -->

        <div class="detail-card">


            <div class="detail-header">


                <img
                    class="detail-cover"
                    src="<?= esc(
                        coverUrl($currentBook)
                    ) ?>"
                    alt="<?= esc($title) ?> cover">


                <div class="detail-info">


                    <h1>

                        <?= esc($title) ?>

                    </h1>


                    <?php if ($author !== ''): ?>

                        <div class="detail-author">

                            By
                            <?= esc($author) ?>

                        </div>

                    <?php endif; ?>


                    <?php if ($category !== ''): ?>

                        <div class="detail-category">

                            <?= esc($category) ?>

                        </div>

                    <?php endif; ?>


                    <div class="detail-actions">


                        <a
                            class="detail-pill
                            <?= $requestedView === 'summary'
                                ? 'primary'
                                : 'secondary' ?>"
                            href="<?= esc(
                                bookUrl(
                                    $currentBook['slug'],
                                    'summary'
                                )
                            ) ?>">

                            Summary

                        </a>


                        <a
                            class="detail-pill
                            <?= $requestedView === 'practice'
                                ? 'primary'
                                : 'secondary' ?>"
                            href="<?= esc(
                                bookUrl(
                                    $currentBook['slug'],
                                    'practice'
                                )
                            ) ?>">

                            Practice Past Questions

                        </a>


                        <a
                            class="detail-pill secondary"
                            href="/novel.php">

                            All Novels

                        </a>


                    </div>


                </div>


            </div>


            <?php if (
                $requestedView === 'summary'
            ): ?>


                <!-- ==========================================
                     SUMMARY
                =========================================== -->

                <div class="reading-area">


                    <h2>
                        Summary
                    </h2>


                    <?php if (
                        $overview !== ''
                    ): ?>


                        <h3>
                            Overview
                        </h3>


                        <p>

                            <?= nl2br(
                                esc($overview)
                            ) ?>

                        </p>


                    <?php endif; ?>


                    <?php if (
                        $plotSummary !== ''
                    ): ?>


                        <h3>
                            Plot Summary
                        </h3>


                        <p>

                            <?= nl2br(
                                esc($plotSummary)
                            ) ?>

                        </p>


                    <?php endif; ?>


                    <?php if (
                        $overview === '' &&
                        $plotSummary === ''
                    ): ?>


                        <div class="notice">

                            A summary has not been added
                            for this Literature text yet.

                        </div>


                    <?php endif; ?>


                </div>


            <?php else: ?>


                <!-- ==========================================
                     PRACTICE
                =========================================== -->

                <div class="reading-area">


                    <div class="practice-top">


                        <div class="question-counter">

                            <?php if (
                                $totalQuestions > 0
                            ): ?>

                                Question
                                <?= $questionNumber ?>
                                of
                                <?= $totalQuestions ?>

                            <?php else: ?>

                                Practice Questions

                            <?php endif; ?>


                        </div>


                    </div>


                    <?php if (
                        $currentQuestion !== null &&
                        is_array($currentQuestion)
                    ): ?>


                        <?php

                        $questionText =
                            isset(
                                $currentQuestion['question']
                            )
                                ? (string)
                                    $currentQuestion['question']
                                : (
                                    isset(
                                        $currentQuestion['text']
                                    )
                                        ? (string)
                                            $currentQuestion['text']
                                        : ''
                                );


                        $options =
                            isset(
                                $currentQuestion['options']
                            ) &&
                            is_array(
                                $currentQuestion['options']
                            )
                                ? $currentQuestion['options']
                                : [];


                        $year =
                            isset(
                                $currentQuestion['year']
                            )
                                ? (string)
                                    $currentQuestion['year']
                                : '';

                        ?>


                        <div class="question-card">


                            <div class="question-label">

                                Question
                                <?= $questionNumber ?>

                            </div>


                            <div class="question-text">

                                <?= nl2br(
                                    esc($questionText)
                                ) ?>

                            </div>


                            <?php if (
                                !empty($options)
                            ): ?>


                                <div class="options">


                                    <?php foreach (
                                        $options as $option
                                    ): ?>


                                        <div class="option">

                                            <?= esc(
                                                $option
                                            ) ?>

                                        </div>


                                    <?php endforeach; ?>


                                </div>


                            <?php endif; ?>


                            <?php if (
                                $year !== ''
                            ): ?>


                                <div class="question-year">

                                    <?= esc($year) ?>

                                </div>


                            <?php endif; ?>


                        </div>


                        <!-- ==================================
                             PREVIOUS / NEXT
                        =================================== -->

                        <div
                            class="practice-navigation">


                            <?php if (
                                $previousQuestion >= 1
                            ): ?>


                                <a
                                    class="question-nav-btn prev"
                                    href="<?= esc(
                                        bookUrl(
                                            $currentBook['slug'],
                                            'practice',
                                            $previousQuestion
                                        )
                                    ) ?>">

                                    ← Previous

                                </a>


                            <?php else: ?>


                                <span
                                    class="
                                        question-nav-btn
                                        prev
                                        disabled
                                    ">

                                    ← Previous

                                </span>


                            <?php endif; ?>


                            <div
                                class="question-position">

                                <?= $questionNumber ?>
                                /
                                <?= $totalQuestions ?>

                            </div>


                            <?php if (
                                $nextQuestion <=
                                $totalQuestions
                            ): ?>


                                <a
                                    class="question-nav-btn next"
                                    href="<?= esc(
                                        bookUrl(
                                            $currentBook['slug'],
                                            'practice',
                                            $nextQuestion
                                        )
                                    ) ?>">

                                    Next →

                                </a>


                            <?php else: ?>


                                <span
                                    class="
                                        question-nav-btn
                                        next
                                        disabled
                                    ">

                                    Next →

                                </span>


                            <?php endif; ?>


                        </div>


                    <?php else: ?>


                        <div class="notice">

                            Practice questions could not
                            be loaded at the moment.

                        </div>


                    <?php endif; ?>


                </div>


            <?php endif; ?>


        </div>


    <?php endif; ?>


</div>


<!-- ==========================================================
     EXACT INDEX.PHP FOOTER STRUCTURE
=========================================================== -->

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


                <li>
                    <a
                        href="https://elearning.flexieduconsult.com.ng"
                        target="_blank"
                        rel="noopener">

                        WhatsApp Masterclass (E-Learning)

                    </a>
                </li>


                <li>
                    <a href="/syllabus.html">

                        Access the JAMB/WAEC syllabus

                    </a>
                </li>


                <li>
                    <a href="/brochure.html">

                        Access JAMB Brochure

                    </a>
                </li>


                <li>
                    <a href="/videos.html">

                        Video Lessons

                    </a>
                </li>


                <li>
                    <a href="/pdf.html">

                        Past Questions & PDFs

                    </a>
                </li>


                <li>
                    <a href="/cbt.html">

                        CBT Simulator

                    </a>
                </li>


                <li>
                    <a href="/groups.html">

                        Classroom Groups and chats

                    </a>
                </li>


                <li>
                    <a href="/location.html">

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
        <?= esc($currentYear) ?>
        Flexi Educational Consult.
        All Rights Reserved.

    </div>


</footer>


<!-- ==========================================================
     SAME SUPPORT WIDGET AS INDEX.PHP
=========================================================== -->

<div
    id="support-widget"
    style="
        position:fixed;
        bottom:20px;
        right:20px;
        z-index:9999;
        display:flex;
        flex-direction:column;
        align-items:flex-end;
        gap:10px;
    ">


    <div
        id="support-tooltip"
        style="
            background:#003366;
            color:white;
            padding:10px 15px;
            border-radius:20px 20px 0px 20px;
            font-size:0.85rem;
            box-shadow:0 4px 10px rgba(0,0,0,0.2);
            opacity:1;
        ">

        Chat with Jarvis AI for support

    </div>


    <button
        id="support-btn"
        onclick="window.location.href='/contactsupport.html'"
        aria-label="Support Chat"
        style="
            background:#2E8B57;
            border:none;
            width:60px;
            height:60px;
            border-radius:50%;
            cursor:pointer;
            box-shadow:0 4px 15px rgba(0,0,0,0.3);
            display:flex;
            align-items:center;
            justify-content:center;
            transition:transform 0.3s;
        ">


        <svg
            width="30"
            height="30"
            viewBox="0 0 24 24"
            fill="white">

            <path
                d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>

        </svg>


    </button>


</div>


<script>

// ============================================================
// SAME MENU BEHAVIOUR AS INDEX.PHP
// ============================================================

window.toggleMenu = function() {

    const menu =
        document.getElementById(
            'squareMenu'
        );

    if (!menu) {
        return;
    }

    menu.classList.toggle(
        'menu-open'
    );

};


// ============================================================
// STUDY SUBMENU
// ============================================================

window.toggleStudyMenu = function(event) {

    if (event) {
        event.stopPropagation();
    }


    const submenu =
        document.getElementById(
            'study-submenu'
        );


    const button =
        document.getElementById(
            'study-menu-toggle'
        );


    if (!submenu || !button) {
        return;
    }


    const isOpen =
        submenu.classList.contains(
            'open'
        );


    submenu.classList.toggle(
        'open',
        !isOpen
    );


    button.setAttribute(
        'aria-expanded',
        String(!isOpen)
    );

};


// ============================================================
// CLOSE MENU WHEN CLICKING OUTSIDE
// ============================================================

document.addEventListener(
    'click',
    function(event) {

        const menu =
            document.getElementById(
                'squareMenu'
            );


        const button =
            document.querySelector(
                '.menu-btn'
            );


        if (
            menu &&
            menu.classList.contains(
                'menu-open'
            ) &&
            !menu.contains(
                event.target
            ) &&
            button &&
            !button.contains(
                event.target
            )
        ) {

            menu.classList.remove(
                'menu-open'
            );


            const submenu =
                document.getElementById(
                    'study-submenu'
                );


            const studyButton =
                document.getElementById(
                    'study-menu-toggle'
                );


            if (submenu) {

                submenu.classList.remove(
                    'open'
                );

            }


            if (studyButton) {

                studyButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }

        }

    }
);

</script>
</body>
</html>
