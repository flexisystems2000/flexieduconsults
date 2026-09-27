<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// NOVELS / LITERATURE
//
// 10 PRESCRIBED LITERATURE TEXTS
//
// FIRST 9 BOOKS:
//   literature/*.json
//
// THE LEKKI HEADMASTER:
//   SUMMARY:
//      literature/the_lekki_headmaster.json
//
//   PRACTICE:
//      Flexi-JAMB-CBT-App-/question_bank/
//      the_lekki_headmaster.json
//
// PRACTICE:
//   - One question at a time
//   - Click an option
//   - Immediate CORRECT / WRONG feedback
//   - Previous / Next without page reload
// ============================================================

declare(strict_types=1);


// ============================================================
// ERROR HANDLING
// Prevent PHP warnings/notices from being displayed publicly.
// ============================================================
ini_set('display_errors', '0');
ini_set('log_errors', '1');


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

    if (
        !is_array($data) ||
        json_last_error() !== JSON_ERROR_NONE
    ) {
        return null;
    }

    return $data;
}


// ============================================================
// HELPER: GET BOOK INFORMATION
//
// Supports the different structures used by the existing
// Literature JSON files.
// ============================================================
function getBookInfo(?array $data): array
{
    $result = [
        'title' => '',
        'author' => '',
        'category' => '',
        'summary' => []
    ];

    if (!is_array($data)) {
        return $result;
    }

    $info = [];

    // play_info structure
    if (
        isset($data['play_info']) &&
        is_array($data['play_info'])
    ) {
        $info = $data['play_info'];
    }

    // Some files may have play as an object.
    elseif (
        isset($data['play']) &&
        is_array($data['play'])
    ) {
        $info = $data['play'];
    }

    // Title
    if (
        isset($info['title']) &&
        is_string($info['title'])
    ) {
        $result['title'] =
            trim($info['title']);
    }

    elseif (
        isset($data['title']) &&
        is_string($data['title'])
    ) {
        $result['title'] =
            trim($data['title']);
    }

    elseif (
        isset($data['play']) &&
        is_string($data['play'])
    ) {
        $result['title'] =
            trim($data['play']);
    }

    // Author
    if (
        isset($info['author']) &&
        is_string($info['author'])
    ) {
        $result['author'] =
            trim($info['author']);
    }

    elseif (
        isset($data['author']) &&
        is_string($data['author'])
    ) {
        $result['author'] =
            trim($data['author']);
    }

    // Category
    if (
        isset($info['category']) &&
        is_string($info['category'])
    ) {
        $result['category'] =
            trim($info['category']);
    }

    elseif (
        isset($data['category']) &&
        is_string($data['category'])
    ) {
        $result['category'] =
            trim($data['category']);
    }

    // Summary
    if (
        isset($info['summary']) &&
        is_array($info['summary'])
    ) {
        $result['summary'] =
            $info['summary'];
    }

    elseif (
        isset($data['summary']) &&
        is_array($data['summary'])
    ) {
        $result['summary'] =
            $data['summary'];
    }

    return $result;
}


// ============================================================
// HELPER: GET QUESTIONS
// ============================================================
function getQuestions(?array $data): array
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
$requestedSlug =
    isset($_GET['book'])
        ? trim((string)$_GET['book'])
        : '';

$requestedView =
    isset($_GET['view'])
        ? trim((string)$_GET['view'])
        : '';

$questionNumber =
    isset($_GET['q'])
        ? max(1, (int)$_GET['q'])
        : 1;


// ============================================================
// FIND CURRENT BOOK
// ============================================================
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
// LOAD CURRENT BOOK
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
    // SPECIAL CASE:
    // THE LEKKI HEADMASTER PRACTICE QUESTIONS
    //
    // They come from the Flexi JAMB CBT App repository.
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


            if (
                is_array($decodedQuestions)
            ) {

                if (
                    function_exists('array_is_list') &&
                    array_is_list($decodedQuestions)
                ) {

                    $currentQuestions =
                        $decodedQuestions;
                }

                elseif (
                    isset(
                        $decodedQuestions['cbt_questions']
                    ) &&
                    is_array(
                        $decodedQuestions['cbt_questions']
                    )
                ) {

                    $currentQuestions =
                        $decodedQuestions['cbt_questions'];
                }

                elseif (
                    isset(
                        $decodedQuestions['questions']
                    ) &&
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
// SAFE BOOK INFORMATION
// ============================================================
$title =
    trim(
        (string)(
            $currentInfo['title'] ?? ''
        )
    );

$author =
    trim(
        (string)(
            $currentInfo['author'] ?? ''
        )
    );

$category =
    trim(
        (string)(
            $currentInfo['category'] ?? ''
        )
    );

$summary =
    isset($currentInfo['summary']) &&
    is_array($currentInfo['summary'])
        ? $currentInfo['summary']
        : [];

$overview =
    trim(
        (string)(
            $summary['overview'] ?? ''
        )
    );

$plotSummary =
    trim(
        (string)(
            $summary['plot_summary'] ?? ''
        )
    );


// ============================================================
// URL HELPERS
// ============================================================
function bookUrl(
    string $slug,
    string $view = 'summary'
): string {

    return
        'novel.php?book=' .
        rawurlencode($slug) .
        '&view=' .
        rawurlencode($view);
}


function coverUrl(array $book): string
{
    return
        'literature/assets/' .
        rawurlencode(
            (string)($book['cover'] ?? '')
        );
}


// ============================================================
// PRACTICE DATA
// ============================================================
$totalQuestions =
    count($currentQuestions);

if ($totalQuestions > 0) {

    if (
        $questionNumber >
        $totalQuestions
    ) {
        $questionNumber =
            $totalQuestions;
    }
}


// ============================================================
// PAGE TITLE
// ============================================================
$pageTitle =
    $isDetailPage && $title !== ''
        ? $title .
          ' | Flexi Educational Consult'
        : 'Literature | Flexi Educational Consult';

$currentYear =
    date('Y');

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
   FLEXI CORE COLORS
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


* {
    box-sizing: border-box;
}


body {

    margin: 0;
    padding: 0;

    background: var(--bg);

    color: #333;

    font-family:
        'Segoe UI',
        system-ui,
        -apple-system,
        sans-serif;
}


a {
    text-decoration: none;
}


/* ============================================================
   HEADER
============================================================ */

header {

    background: var(--blue);

    color: white;

    min-height: 52px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 20px;

    border-bottom:
        3px solid var(--green);

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

    letter-spacing: .2px;
}


/* ============================================================
   MENU
============================================================ */

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

    border:
        2px solid var(--green);

    border-radius: 10px;

    z-index: 2000;

    box-shadow:
        0 12px 32px
        rgba(0,0,0,.35);

    overflow: hidden;
}


.square-menu.menu-open {
    display: block;
}


.square-menu a,
.study-menu-toggle {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 13px 15px;

    color: white;

    font-size: 14px;

    border: 0;

    border-bottom:
        1px solid
        rgba(255,255,255,.1);
}


.square-menu a:hover,
.study-menu-toggle:hover {

    background:
        rgba(255,255,255,.12);
}


.study-menu-toggle {

    width: 100%;

    background: transparent;

    text-align: left;

    cursor: pointer;
}


.menu-icon {

    width: 21px;
    height: 21px;

    flex: 0 0 21px;

    display: inline-flex;

    align-items: center;

    justify-content: center;
}


.menu-icon svg {

    width: 20px;
    height: 20px;
}


.menu-label {
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
        1px solid
        rgba(255,255,255,.20);

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

    padding: 9px 10px;

    font-size: 13px;
}


/* ============================================================
   MAIN CONTAINER
============================================================ */

.container {

    max-width: 1180px;

    width: 94%;

    margin:
        24px auto 46px;
}


/* ============================================================
   PAGE HEADING
============================================================ */

.page-heading {

    background: white;

    border-radius:
        var(--radius);

    box-shadow:
        var(--shadow);

    padding:
        24px 28px;

    margin-bottom:
        20px;

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

    color:
        #64748b;

    font-size: 14px;
}


/* ============================================================
   NOVEL GRID
============================================================ */

.novel-grid {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0,1fr));

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

    padding: 14px;

    display: flex;

    flex-direction: column;

    flex: 1;
}


.book-number {

    color:
        var(--green);

    font-size: 11px;

    font-weight: 800;

    text-transform:
        uppercase;

    margin-bottom: 3px;
}


.novel-title {

    color:
        var(--blue);

    font-size: 16px;

    line-height: 1.3;

    margin:
        0 0 5px;
}


.novel-author {

    color:
        #667085;

    font-size: 12px;

    margin-bottom: 8px;
}


.novel-description {

    color:
        #596575;

    font-size: 12px;

    line-height: 1.55;

    margin:
        0 0 14px;

    display:
        -webkit-box;

    -webkit-line-clamp: 4;

    -webkit-box-orient:
        vertical;

    overflow: hidden;
}


.novel-actions {

    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 7px;

    margin-top: auto;
}


.pill-btn {

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    min-height: 40px;

    padding: 7px 8px;

    border-radius: 999px;

    font-size: 11px;

    font-weight: 800;
}


.pill-summary {

    background:
        var(--blue);

    color: white;
}


.pill-practice {

    background: white;

    color:
        var(--blue);

    border:
        1px solid var(--blue);
}


/* ============================================================
   DETAIL PAGE
============================================================ */

.detail-card {

    background: white;

    border-radius:
        var(--radius);

    box-shadow:
        var(--shadow);

    overflow: hidden;
}


.detail-header {

    display: grid;

    grid-template-columns:
        170px 1fr;

    gap: 24px;

    padding: 24px;

    border-bottom:
        1px solid #e5e7eb;
}


.detail-cover {

    width: 170px;

    height: 220px;

    object-fit: cover;

    border-radius: 9px;
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

    display: inline-block;

    background:
        #edf7f1;

    color:
        #237246;

    border-radius: 999px;

    padding:
        5px 10px;

    font-size: 11px;

    font-weight: 800;

    margin-bottom: 17px;
}


.detail-actions {

    display: flex;

    flex-wrap: wrap;

    gap: 8px;
}


.detail-pill {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 40px;

    padding:
        8px 15px;

    border-radius: 999px;

    border:
        1px solid var(--blue);

    font-size: 12px;

    font-weight: 800;
}


.detail-pill.primary {

    background:
        var(--blue);

    color: white;
}


.detail-pill.secondary {

    background: white;

    color:
        var(--blue);
}


/* ============================================================
   READING AREA
============================================================ */

.reading-area {

    padding: 25px;
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

    display: flex;

    align-items: center;

    justify-content:
        space-between;

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

    background: #fff;

    border:
        1px solid #e1e7ec;

    border-radius: 12px;

    padding: 20px;
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

    display: grid;

    gap: 9px;
}


/* ============================================================
   CLICKABLE OPTIONS
============================================================ */

.option {

    width: 100%;

    padding:
        12px 13px;

    border:
        1px solid #e1e6eb;

    border-radius: 8px;

    background:
        #f9fafb;

    color:
        #374151;

    font-family: inherit;

    font-size: 14px;

    line-height: 1.5;

    text-align: left;

    cursor: pointer;

    transition:
        background .18s ease,
        border-color .18s ease,
        color .18s ease,
        transform .08s ease;
}


.option:hover {

    border-color:
        var(--blue);

    background:
        #f1f6fb;
}


.option:active {

    transform:
        scale(.995);
}


.option.selected-correct {

    background:
        #e8f7ef;

    border-color:
        var(--green);

    color:
        #17633c;
}


.option.selected-wrong {

    background:
        #fdecec;

    border-color:
        #c0392b;

    color:
        #9f2419;
}


.option.correct-answer {

    background:
        #e8f7ef;

    border-color:
        var(--green);

    color:
        #17633c;
}


.option:disabled {

    cursor: default;

    opacity: 1;
}


/* ============================================================
   ANSWER FEEDBACK
============================================================ */

.answer-feedback {

    display: none;

    margin-top:
        14px;

    padding:
        11px 13px;

    border-radius:
        9px;

    font-size:
        14px;

    line-height:
        1.45;

    font-weight:
        800;
}


.answer-feedback.show {
    display: block;
}


.answer-feedback.correct {

    background:
        #e8f7ef;

    border:
        1px solid #a9dfc1;

    color:
        #17633c;
}


.answer-feedback.wrong {

    background:
        #fdecec;

    border:
        1px solid #efb2ac;

    color:
        #9f2419;
}


.question-year {

    display: inline-block;

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
   PREVIOUS / NEXT
============================================================ */

.practice-navigation {

    display: grid;

    grid-template-columns:
        1fr auto 1fr;

    align-items: center;

    gap: 10px;

    margin-top:
        18px;
}


.question-nav-btn {

    min-height:
        45px;

    display: flex;

    align-items: center;

    justify-content: center;

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

    font-family: inherit;

    cursor: pointer;
}


.question-nav-btn.prev {

    justify-self: start;

    color:
        var(--blue);

    background:
        white;
}


.question-nav-btn.next {

    justify-self: end;

    color: white;

    background:
        var(--blue);
}


.question-nav-btn:disabled {

    opacity:
        .35;

    cursor:
        default;

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


/* ============================================================
   NOTICE
============================================================ */

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
   FOOTER
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

    display: grid;

    grid-template-columns:
        1.8fr 1.2fr 1.3fr 1fr;

    gap: 35px;

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
}


.footer-about {

    line-height:
        1.7;

    color:
        #94a3b8;

    margin: 0;
}


.footer-links-list {

    list-style: none;

    padding: 0;

    margin: 0;
}


.footer-links-list li {

    margin-bottom:
        10px;
}


.footer a {

    color:
        #cbd5e0;
}


.footer a:hover {

    color:
        var(--yellow);
}


.whatsapp-channel-link {

    color:
        #25D366 !important;

    font-weight:
        600;
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

    margin:
        0 0 6px;
}


.contact-group a {

    display: block;

    color:
        #94a3b8;

    font-size:
        .88rem;

    margin-bottom:
        4px;
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
        rgba(255,255,255,.08);

    text-align:
        center;

    font-size:
        13px;

    color:
        #64748b;
}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (min-width: 768px) {

    header {

        min-height:
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
}


@media (max-width: 900px) {

    .novel-grid {

        grid-template-columns:
            repeat(2,minmax(0,1fr));
    }

    .footer-grid {

        grid-template-columns:
            1fr 1fr;
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
    }
}

</style>

</head>


<body>


<!-- ==========================================================
     FLEXI HEADER
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
            type="button"
            class="menu-btn"
            onclick="toggleMenu()"
            aria-label="Toggle Navigation Menu">

            <svg
                class="menu-svg"
                viewBox="0 0 24 24">

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
                            stroke-width="1.8"/>

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
                            stroke-width="1.8"/>

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
                        stroke-width="2"/>

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
                                stroke-width="1.8"/>

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
                                stroke-width="1.8"/>

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
                                stroke-width="1.8"/>

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
                            d="M12 3v11M7.5 10.5L12 15l4.5-4.5M5 20h14"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"/>

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
                            stroke-width="1.8"/>

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
                            stroke-width="1.8"/>

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
                            stroke-width="1.8"/>

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
                            stroke-width="1.8"/>

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
     MAIN CONTENT
=========================================================== -->

<div class="container">


<?php if (!$isDetailPage): ?>


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


    <?php foreach (
        $books as $index => $book
    ): ?>


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

        $cardTitle =
            $info['title'] !== ''
                ? $info['title']
                : ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $book['slug']
                    )
                );

        $cardAuthor =
            $info['author'] !== ''
                ? $info['author']
                : 'Literature';

        $cardCategory =
            $info['category'];

        $cardDescription = '';

        if (
            isset($info['summary']) &&
            is_array($info['summary'])
        ) {

            $cardDescription =
                trim(
                    (string)(
                        $info['summary']['overview']
                        ?? ''
                    )
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


        <div class="reading-area">


            <h2>
                Summary
            </h2>


            <?php if ($overview !== ''): ?>

                <h3>
                    Overview
                </h3>

                <p>

                    <?= nl2br(
                        esc($overview)
                    ) ?>

                </p>

            <?php endif; ?>


            <?php if ($plotSummary !== ''): ?>

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


        <!-- ==================================================
             PRACTICE
        =================================================== -->

        <div class="reading-area">


        <?php if ($totalQuestions > 0): ?>


            <div class="practice-top">

                <div
                    class="question-counter"
                    id="novelQuestionCounter">

                    Question
                    <?= $questionNumber ?>
                    of
                    <?= $totalQuestions ?>

                </div>

            </div>


            <div
                class="question-card"
                id="novelQuestionCard">


                <div
                    class="question-label"
                    id="novelQuestionLabel">

                    Question
                    <?= $questionNumber ?>

                </div>


                <div
                    class="question-text"
                    id="novelQuestionText">
                </div>


                <div
                    class="options"
                    id="novelOptions">
                </div>


                <div
                    id="novelAnswerFeedback"
                    class="answer-feedback"
                    aria-live="polite">
                </div>


                <div
                    class="question-year"
                    id="novelQuestionYear"
                    hidden>
                </div>


            </div>


            <div
                class="practice-navigation">


                <button
                    type="button"
                    class="question-nav-btn prev"
                    id="novelPreviousBtn">

                    ← Previous

                </button>


                <div
                    class="question-position"
                    id="novelQuestionPosition">

                    <?= $questionNumber ?>
                    /
                    <?= $totalQuestions ?>

                </div>


                <button
                    type="button"
                    class="question-nav-btn next"
                    id="novelNextBtn">

                    Next →

                </button>


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
     FOOTER
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
     PRACTICE JAVASCRIPT
     
     IMPORTANT:
     Previous / Next DOES NOT change the URL and DOES NOT
     reload novel.php.
=========================================================== -->

<script>

window.FLEXI_NOVEL_QUESTIONS =
    <?= json_encode(
        $currentQuestions,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    ) ?>;


(function () {

    const questions =
        Array.isArray(
            window.FLEXI_NOVEL_QUESTIONS
        )
            ? window.FLEXI_NOVEL_QUESTIONS
            : [];


    const questionText =
        document.getElementById(
            'novelQuestionText'
        );

    const questionLabel =
        document.getElementById(
            'novelQuestionLabel'
        );

    const questionCounter =
        document.getElementById(
            'novelQuestionCounter'
        );

    const questionPosition =
        document.getElementById(
            'novelQuestionPosition'
        );

    const optionsContainer =
        document.getElementById(
            'novelOptions'
        );

    const feedback =
        document.getElementById(
            'novelAnswerFeedback'
        );

    const yearBadge =
        document.getElementById(
            'novelQuestionYear'
        );

    const previousBtn =
        document.getElementById(
            'novelPreviousBtn'
        );

    const nextBtn =
        document.getElementById(
            'novelNextBtn'
        );


    // This script only runs on the practice page.
    if (
        !questionText ||
        !questionLabel ||
        !questionCounter ||
        !questionPosition ||
        !optionsContainer ||
        !feedback ||
        !previousBtn ||
        !nextBtn ||
        questions.length === 0
    ) {
        return;
    }


    // PHP question number becomes the initial question.
    let currentIndex =
        Math.max(
            0,
            Math.min(
                questions.length - 1,
                <?= max(
                    0,
                    $questionNumber - 1
                ) ?>
            )
        );


    // Stores answers selected by the user.
    const answers = {};


    // --------------------------------------------------------
    // ESCAPE HTML
    // --------------------------------------------------------
    function escapeHtml(value) {

        return String(value ?? '')
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
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );
    }


    // --------------------------------------------------------
    // QUESTION TEXT
    // --------------------------------------------------------
    function getQuestionText(question) {

        if (
            question &&
            typeof question.question ===
                'string'
        ) {
            return question.question;
        }

        if (
            question &&
            typeof question.text ===
                'string'
        ) {
            return question.text;
        }

        return '';
    }


    // --------------------------------------------------------
    // OPTIONS
    // --------------------------------------------------------
    function getOptions(question) {

        if (
            question &&
            Array.isArray(
                question.options
            )
        ) {
            return question.options;
        }

        return [];
    }


    // --------------------------------------------------------
    // CORRECT ANSWER
    // --------------------------------------------------------
    function getCorrectAnswer(question) {

        if (!question) {
            return '';
        }

        const answer =
            question.answer ??
            question.correctAnswer ??
            question.correct_answer ??
            '';

        return String(answer)
            .trim()
            .toUpperCase()
            .replace(
                /\.$/,
                ''
            );
    }


    // --------------------------------------------------------
    // OPTION LETTER
    // --------------------------------------------------------
    function optionLetter(index) {

        return String.fromCharCode(
            65 + index
        );
    }


    // --------------------------------------------------------
    // CHECK ANSWER
    // --------------------------------------------------------
    function checkAnswer(
        question,
        selectedIndex
    ) {

        const correct =
            getCorrectAnswer(
                question
            );

        if (correct === '') {
            return null;
        }


        const selectedLetter =
            optionLetter(
                selectedIndex
            );


        if (
            correct ===
            selectedLetter
        ) {
            return true;
        }


        const options =
            getOptions(
                question
            );


        if (
            selectedIndex >= 0 &&
            selectedIndex < options.length
        ) {

            const selectedText =
                String(
                    options[selectedIndex]
                )
                .trim()
                .toUpperCase();


            if (
                selectedText ===
                correct
            ) {
                return true;
            }
        }


        return false;
    }


    // --------------------------------------------------------
    // CLEAR FEEDBACK
    // --------------------------------------------------------
    function clearFeedback() {

        feedback.className =
            'answer-feedback';

        feedback.textContent =
            '';
    }


    // --------------------------------------------------------
    // SHOW FEEDBACK
    // --------------------------------------------------------
    function showFeedback(
        result,
        question
    ) {

        if (result === true) {

            feedback.className =
                'answer-feedback show correct';

            feedback.textContent =
                'CORRECT';

            return;
        }


        if (result === false) {

            const correct =
                getCorrectAnswer(
                    question
                );


            feedback.className =
                'answer-feedback show wrong';


            if (correct !== '') {

                feedback.textContent =
                    'WRONG — Correct answer: ' +
                    correct;

            }
            else {

                feedback.textContent =
                    'WRONG';
            }

            return;
        }


        clearFeedback();
    }


    // --------------------------------------------------------
    // RENDER QUESTION
    // --------------------------------------------------------
    function renderQuestion() {

        const question =
            questions[currentIndex] || {};


        const options =
            getOptions(question);


        const total =
            questions.length;


        const number =
            currentIndex + 1;


        const hasAnswered =
            Object.prototype.hasOwnProperty.call(
                answers,
                currentIndex
            );


        const selectedIndex =
            hasAnswered
                ? answers[currentIndex]
                : null;


        // Counter
        questionCounter.textContent =
            'Question ' +
            number +
            ' of ' +
            total;


        questionLabel.textContent =
            'Question ' +
            number;


        questionPosition.textContent =
            number +
            ' / ' +
            total;


        // Question text
        questionText.innerHTML =
            escapeHtml(
                getQuestionText(question)
            ).replace(
                /\n/g,
                '<br>'
            );


        // Clear old options.
        optionsContainer.innerHTML =
            '';


        // Create clickable buttons.
        options.forEach(
            function (
                optionText,
                index
            ) {

                const button =
                    document.createElement(
                        'button'
                    );


                button.type =
                    'button';


                button.className =
                    'option';


                button.innerHTML =
                    escapeHtml(
                        String(optionText)
                    );


                // If already answered,
                // show the result again.
                if (hasAnswered) {

                    const result =
                        checkAnswer(
                            question,
                            selectedIndex
                        );


                    if (
                        index ===
                        selectedIndex
                    ) {

                        if (
                            result === true
                        ) {

                            button.classList.add(
                                'selected-correct'
                            );

                        }
                        else {

                            button.classList.add(
                                'selected-wrong'
                            );
                        }
                    }


                    const correct =
                        getCorrectAnswer(
                            question
                        );


                    if (
                        /^[A-Z]$/.test(
                            correct
                        )
                    ) {

                        const correctIndex =
                            correct.charCodeAt(0)
                            - 65;


                        if (
                            index ===
                            correctIndex
                        ) {

                            button.classList.add(
                                'correct-answer'
                            );
                        }
                    }


                    button.disabled =
                        true;

                }


                // New answer.
                else {

                    button.addEventListener(
                        'click',
                        function () {

                            selectAnswer(
                                index
                            );

                        }
                    );
                }


                optionsContainer.appendChild(
                    button
                );

            }
        );


        // Feedback
        if (hasAnswered) {

            const result =
                checkAnswer(
                    question,
                    selectedIndex
                );


            showFeedback(
                result,
                question
            );

        }
        else {

            clearFeedback();
        }


        // Year
        const year =
            String(
                question.year ?? ''
            ).trim();


        if (year !== '') {

            yearBadge.hidden =
                false;

            yearBadge.textContent =
                year;

        }
        else {

            yearBadge.hidden =
                true;

            yearBadge.textContent =
                '';
        }


        // Navigation
        previousBtn.disabled =
            currentIndex === 0;


        nextBtn.disabled =
            currentIndex ===
            total - 1;
    }


    // --------------------------------------------------------
    // SELECT ANSWER
    // --------------------------------------------------------
    function selectAnswer(index) {

        const question =
            questions[currentIndex];


        const options =
            getOptions(question);


        if (
            !options[index]
        ) {
            return;
        }


        // Save answer.
        answers[currentIndex] =
            index;


        // Re-render immediately.
        renderQuestion();
    }


    // --------------------------------------------------------
    // PREVIOUS
    // --------------------------------------------------------
    function previousQuestion() {

        if (
            currentIndex <= 0
        ) {
            return;
        }


        currentIndex--;


        renderQuestion();


        scrollToQuestion();
    }


    // --------------------------------------------------------
    // NEXT
    // --------------------------------------------------------
    function nextQuestion() {

        if (
            currentIndex >=
            questions.length - 1
        ) {
            return;
        }


        currentIndex++;


        renderQuestion();


        scrollToQuestion();
    }


    // --------------------------------------------------------
    // SCROLL TO QUESTION
    //
    // This scrolls the screen to the question.
    // It DOES NOT reload the page.
    // --------------------------------------------------------
    function scrollToQuestion() {

        const card =
            document.getElementById(
                'novelQuestionCard'
            );


        if (!card) {
            return;
        }


        const top =
            card.getBoundingClientRect().top +
            window.scrollY -
            75;


        window.scrollTo({

            top:
                Math.max(
                    0,
                    top
                ),

            behavior:
                'smooth'
        });
    }


    // --------------------------------------------------------
    // BUTTON EVENTS
    // --------------------------------------------------------
    previousBtn.addEventListener(
        'click',
        previousQuestion
    );


    nextBtn.addEventListener(
        'click',
        nextQuestion
    );


    // --------------------------------------------------------
    // KEYBOARD NAVIGATION
    // --------------------------------------------------------
    document.addEventListener(
        'keydown',
        function(event) {

            if (
                event.key ===
                'ArrowLeft'
            ) {

                previousQuestion();

            }

            else if (
                event.key ===
                'ArrowRight'
            ) {

                nextQuestion();
            }
        }
    );


    // Initial question.
    renderQuestion();

})();


// ============================================================
// MENU
// ============================================================

window.toggleMenu =
    function () {

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

window.toggleStudyMenu =
    function (event) {

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


        if (
            !submenu ||
            !button
        ) {
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
