<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// SCHOLARSHIP PAGE
// ============================================================

$supabaseUrl = 'https://ryvauylmymcvbvvlaceb.supabase.co';

$supabaseKey = 'sb_publishable_zF84MIhSPOZ3MXth_LLqDA_yQ4pIvp6';


// ============================================================
// FETCH SCHOLARSHIPS FROM SUPABASE
// ============================================================

$endpoint =
    $supabaseUrl .
    '/rest/v1/scholarships' .
    '?select=id,title,organization,description,eligibility,amount,deadline,location,study_level,field_of_study,application_url,source_url,image_url,slug,status,featured,created_at,updated_at' .
    '&status=eq.active' .
    '&order=featured.desc,deadline.asc,created_at.desc';


$ch = curl_init($endpoint);

curl_setopt_array($ch, [

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_HTTPHEADER => [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ],

    CURLOPT_TIMEOUT => 20,

    CURLOPT_CONNECTTIMEOUT => 10

]);


$response = curl_exec($ch);

$httpCode = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

$curlError = curl_error($ch);

curl_close($ch);


// ============================================================
// PROCESS RESPONSE
// ============================================================

$scholarships = [];

$hasError = false;

$errorMessage = '';


if (
    !$curlError &&
    $httpCode >= 200 &&
    $httpCode < 300 &&
    $response !== false
) {

    $decoded = json_decode(
        $response,
        true
    );

    if (is_array($decoded)) {

        $scholarships = $decoded;

    } else {

        $hasError = true;

        $errorMessage =
            'Invalid response received from the scholarship database.';

    }

} else {

    $hasError = true;

    if ($curlError) {

        $errorMessage =
            'Unable to connect to the scholarship database.';

    } else {

        $errorMessage =
            'The scholarship database returned an error.';

    }

}


// ============================================================
// HELPERS
// ============================================================

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


function formatDeadline($date)
{
    if (!$date) {
        return 'No deadline specified';
    }

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return $date;
    }

    return date(
        'M j, Y',
        $timestamp
    );
}


function deadlineClass($date)
{
    if (!$date) {
        return '';
    }

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return '';
    }

    $days = floor(
        (
            $timestamp -
            time()
        ) / 86400
    );

    if ($days < 0) {
        return 'deadline-past';
    }

    if ($days <= 14) {
        return 'deadline-soon';
    }

    return '';
}


function scholarshipImage($image)
{
    if (!empty($image)) {
        return $image;
    }

    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg"
              width="800"
              height="450"
              viewBox="0 0 800 450">

            <rect
                width="800"
                height="450"
                fill="#003366"
            />

            <path
                d="M400 100
                   L515 145
                   L400 190
                   L285 145
                   Z"
                fill="none"
                stroke="#FFD700"
                stroke-width="8"
                stroke-linejoin="round"
            />

            <path
                d="M315 158
                   V225
                   C315 260 485 260 485 225
                   V158"
                fill="none"
                stroke="#FFD700"
                stroke-width="8"
            />

            <line
                x1="515"
                y1="145"
                x2="515"
                y2="235"
                stroke="#FFD700"
                stroke-width="8"
            />

            <circle
                cx="515"
                cy="247"
                r="12"
                fill="#FFD700"
            />

            <text
                x="400"
                y="340"
                text-anchor="middle"
                font-family="Arial,sans-serif"
                font-size="30"
                font-weight="700"
                fill="white">
                Flexi Educational Consult
            </text>

        </svg>'
    );
}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0"
>

<meta
    name="theme-color"
    content="#003366"
>

<title>
    Scholarships | Flexi Educational Consult
</title>

<meta
    name="description"
    content="Find current scholarship opportunities for secondary school, undergraduate, postgraduate and international students through Flexi Educational Consult."
>

<meta
    name="keywords"
    content="scholarships, Nigerian scholarships, undergraduate scholarships, postgraduate scholarships, international scholarships, Flexi Educational Consult"
>

<link
    rel="canonical"
    href="https://www.flexieduconsult.com.ng/scholarship.php"
>


<style>

/* ============================================================
   GLOBAL
============================================================ */

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background: #f5f7fa;

    color: #1f2937;

    line-height: 1.6;
}

a {
    color: inherit;
    text-decoration: none;
}

button,
input,
select {
    font: inherit;
}


/* ============================================================
   HEADER
============================================================ */

header {

    background: #003366;

    color: white;

    min-height: 64px;

    display: flex;

    align-items: center;

    padding: 10px 20px;

    border-bottom:
        4px solid #2E8B57;

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

    height: 40px;

    width: 40px;

    object-fit: contain;

    border-radius: 5px;

    background: white;
}

.brand-name {

    font-size: 17px;

    font-weight: 700;

    letter-spacing: .2px;
}


/* ============================================================
   HERO
============================================================ */

.hero {

    background:
        linear-gradient(
            135deg,
            #003366 0%,
            #004b80 55%,
            #2E8B57 100%
        );

    color: white;

    padding:
        58px
        18px
        65px;
}

.hero-inner {

    max-width: 1100px;

    margin: auto;

    text-align: center;
}


/* SVG HERO ICON */

.hero-badge {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding:
        8px
        14px;

    border-radius: 30px;

    background:
        rgba(255,255,255,.12);

    border:
        1px solid
        rgba(255,255,255,.22);

    font-size: 12px;

    font-weight: 700;

    margin-bottom: 17px;
}

.hero-badge svg {

    width: 18px;

    height: 18px;

    flex-shrink: 0;
}

.hero h1 {

    margin:
        0
        auto
        12px;

    max-width: 850px;

    font-size:
        clamp(
            30px,
            6vw,
            48px
        );

    line-height: 1.12;
}

.hero p {

    max-width: 720px;

    margin: auto;

    font-size: 16px;

    opacity: .93;
}


/* ============================================================
   MAIN
============================================================ */

.main-container {

    max-width: 1200px;

    margin: auto;

    padding:
        30px
        18px
        70px;
}


/* ============================================================
   FILTER
============================================================ */

.filter-box {

    background: white;

    border-radius: 15px;

    padding: 18px;

    margin-top: -48px;

    position: relative;

    z-index: 5;

    box-shadow:
        0
        7px
        28px
        rgba(0,0,0,.10);

    display: grid;

    grid-template-columns:
        minmax(0,1fr)
        230px;

    gap: 12px;
}

.input-wrap {

    position: relative;
}

.input-icon {

    position: absolute;

    left: 14px;

    top: 50%;

    transform:
        translateY(-50%);

    width: 19px;

    height: 19px;

    color: #64748b;

    pointer-events: none;
}

.search-input,
.study-filter {

    width: 100%;

    height: 48px;

    border:
        1px solid
        #d8dee7;

    border-radius: 9px;

    background: white;

    color: #1f2937;

    outline: none;
}

.search-input {

    padding:
        0
        15px
        0
        44px;
}

.study-filter {

    padding:
        0
        13px;

    cursor: pointer;
}

.search-input:focus,
.study-filter:focus {

    border-color:
        #2E8B57;

    box-shadow:
        0
        0
        0
        3px
        rgba(46,139,87,.12);
}


/* ============================================================
   SECTION HEADER
============================================================ */

.section-header {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    gap: 15px;

    margin:
        35px
        0
        18px;
}

.section-title {

    margin: 0;

    color: #003366;

    font-size: 25px;
}

.section-subtitle {

    margin:
        4px
        0
        0;

    color: #64748b;

    font-size: 14px;
}

.result-count {

    color: #64748b;

    font-size: 13px;

    white-space: nowrap;
}


/* ============================================================
   GRID
============================================================ */

.scholarship-grid {

    display: grid;

    grid-template-columns:
        repeat(
            3,
            minmax(0,1fr)
        );

    gap: 20px;
}


/* ============================================================
   CARD
============================================================ */

.scholarship-card {

    background: white;

    border:
        1px solid
        #e4e8ee;

    border-radius: 14px;

    overflow: hidden;

    display: flex;

    flex-direction: column;

    min-width: 0;

    box-shadow:
        0
        3px
        14px
        rgba(0,0,0,.045);

    transition:
        transform .2s ease,
        box-shadow .2s ease;
}

.scholarship-card:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0
        10px
        28px
        rgba(0,0,0,.09);
}


/* ============================================================
   IMAGE
============================================================ */

.card-image-wrap {

    position: relative;

    height: 185px;

    background: #e9eef3;

    overflow: hidden;
}

.card-image {

    width: 100%;

    height: 100%;

    object-fit: cover;

    display: block;
}

.featured-badge {

    position: absolute;

    top: 12px;

    left: 12px;

    background: #FFD700;

    color: #1f2937;

    padding:
        5px
        9px;

    border-radius: 6px;

    font-size: 11px;

    font-weight: 800;

    display: flex;

    align-items: center;

    gap: 5px;
}

.featured-badge svg {

    width: 13px;

    height: 13px;
}


/* ============================================================
   CARD BODY
============================================================ */

.card-body {

    padding: 18px;

    display: flex;

    flex-direction: column;

    flex: 1;
}

.organization {

    color: #2E8B57;

    font-size: 12px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .3px;

    margin-bottom: 6px;
}

.card-title {

    margin:
        0
        0
        9px;

    color: #003366;

    font-size: 18px;

    line-height: 1.35;
}

.card-description {

    margin:
        0
        0
        15px;

    color: #64748b;

    font-size: 13px;

    line-height: 1.6;

    display: -webkit-box;

    -webkit-line-clamp: 3;

    -webkit-box-orient: vertical;

    overflow: hidden;
}


/* ============================================================
   DETAILS
============================================================ */

.details {

    display: grid;

    grid-template-columns: 1fr;

    gap: 9px;

    margin-bottom: 18px;
}

.detail-item {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    color: #475569;

    font-size: 12px;
}

.detail-icon {

    width: 17px;

    height: 17px;

    flex:
        0
        0
        17px;

    color: #2E8B57;

    margin-top: 1px;
}

.detail-label {

    font-weight: 700;

    color: #334155;
}

.detail-value {

    color: #64748b;
}

.deadline-soon {

    color: #b45309 !important;

    font-weight: 700;
}

.deadline-past {

    color: #b91c1c !important;

    font-weight: 700;
}


/* ============================================================
   APPLY BUTTON
============================================================ */

.apply-btn {

    margin-top: auto;

    width: 100%;

    min-height: 44px;

    border-radius: 8px;

    background: #003366;

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    font-size: 14px;

    font-weight: 700;

    transition:
        background .2s ease;
}

.apply-btn:hover {

    background: #00284f;
}

.apply-icon {

    width: 17px;

    height: 17px;
}


/* ============================================================
   STATE BOX
============================================================ */

.state-box {

    background: white;

    border:
        1px solid
        #e4e8ee;

    border-radius: 14px;

    padding:
        50px
        20px;

    text-align: center;

    color: #64748b;
}

.state-icon {

    width: 42px;

    height: 42px;

    margin:
        0
        auto
        13px;

    color: #2E8B57;
}

.state-box h3 {

    margin:
        0
        0
        6px;

    color: #003366;
}


/* ============================================================
   FOOTER
============================================================ */

footer {

    background: #003366;

    color: white;

    text-align: center;

    padding:
        25px
        18px;

    border-top:
        4px solid
        #2E8B57;
}

footer p {

    margin: 4px 0;

    font-size: 13px;

    opacity: .9;
}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 950px) {

    .scholarship-grid {

        grid-template-columns:
            repeat(
                2,
                minmax(0,1fr)
            );
    }

}


@media (max-width: 700px) {

    header {

        padding:
            9px
            15px;
    }

    .brand-name {

        font-size: 15px;
    }

    .hero {

        padding:
            50px
            15px
            62px;
    }

    .hero h1 {

        font-size: 34px;
    }

    .filter-box {

        grid-template-columns: 1fr;

        margin-top: -42px;
    }

    .section-header {

        align-items: flex-start;

        flex-direction: column;

        margin-top: 30px;
    }

    .scholarship-grid {

        grid-template-columns: 1fr;

        gap: 16px;
    }

    .card-image-wrap {

        height: 200px;
    }

}


@media (max-width: 420px) {

    .main-container {

        padding-left: 12px;

        padding-right: 12px;
    }

    .hero h1 {

        font-size: 30px;
    }

    .hero p {

        font-size: 14px;
    }

    .section-title {

        font-size: 23px;
    }

}


/* ============================================================
   HIDDEN
============================================================ */

.hidden {

    display: none !important;
}

</style>

</head>


<body>


<!-- ============================================================
     HEADER
============================================================ -->

<header>

    <div class="header-left">

        <img
            src="https://www.flexieduconsult.com.ng/assets/logo.png"
            alt="Flexi Educational Consult"
            class="logo-img"
            onerror="this.style.display='none';"
        >

        <div class="brand-name">
            Flexi Educational Consult
        </div>

    </div>

</header>


<!-- ============================================================
     HERO
============================================================ -->

<section class="hero">

    <div class="hero-inner">

        <div class="hero-badge">

            <!-- SCHOLARSHIP SVG ICON -->

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path
                    d="M2 8.5
                       12 4
                       22 8.5
                       12 13
                       2 8.5Z"
                />

                <path
                    d="M6 10.5
                       V15
                       C6 17
                       18 17
                       18 15
                       V10.5"
                />

                <path
                    d="M22 8.5
                       V15"
                />

                <circle
                    cx="22"
                    cy="17"
                    r="1"
                />

            </svg>

            Scholarship Opportunities

        </div>


        <h1>
            Discover Scholarship Opportunities
        </h1>


        <p>
            Explore available scholarships and educational
            funding opportunities for students at different
            levels of study.
        </p>

    </div>

</section>


<!-- ============================================================
     MAIN
============================================================ -->

<main class="main-container">


    <!-- SEARCH -->

    <div class="filter-box">

        <div class="input-wrap">

            <!-- SEARCH SVG -->

            <svg
                class="input-icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >

                <circle
                    cx="11"
                    cy="11"
                    r="7"
                />

                <path
                    d="M20 20
                       L16.2 16.2"
                />

            </svg>


            <input
                type="search"
                id="searchInput"
                class="search-input"
                placeholder="Search scholarships, organizations..."
                autocomplete="off"
            >

        </div>


        <select
            id="studyFilter"
            class="study-filter"
        >

            <option value="">
                All Study Levels
            </option>

        </select>

    </div>


    <!-- SECTION TITLE -->

    <div class="section-header">

        <div>

            <h2 class="section-title">
                Available Scholarships
            </h2>

            <p class="section-subtitle">
                Browse scholarships currently available.
            </p>

        </div>


        <div
            id="resultCount"
            class="result-count"
        ></div>

    </div>


    <!-- SCHOLARSHIPS -->

    <div
        id="scholarshipContainer"
        class="scholarship-grid"
    >

        <?php if ($hasError): ?>

            <div
                class="state-box"
                style="grid-column:1/-1;"
            >

                <!-- ERROR SVG -->

                <svg
                    class="state-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />

                    <path
                        d="M12 8v5"
                    />

                    <circle
                        cx="12"
                        cy="16.5"
                        r=".7"
                        fill="currentColor"
                    />

                </svg>


                <h3>
                    Unable to load scholarships
                </h3>

                <p>
                    Please try again later.
                </p>

            </div>


        <?php elseif (empty($scholarships)): ?>


            <div
                class="state-box"
                style="grid-column:1/-1;"
            >

                <!-- EMPTY BOOK SVG -->

                <svg
                    class="state-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true"
                >

                    <path
                        d="M4 5.5
                           C4 4.7 4.7 4 5.5 4
                           H10
                           C11.1 4 12 4.9 12 6
                           V20
                           C12 18.9 11.1 18 10 18
                           H5.5
                           C4.7 18 4 17.3 4 16.5
                           V5.5Z"
                    />

                    <path
                        d="M20 5.5
                           C20 4.7 19.3 4 18.5 4
                           H14
                           C12.9 4 12 4.9 12 6
                           V20
                           C12 18.9 12.9 18 14 18
                           H18.5
                           C19.3 18 20 17.3 20 16.5
                           V5.5Z"
                    />

                </svg>


                <h3>
                    No scholarships available
                </h3>

                <p>
                    Check back later for new scholarship opportunities.
                </p>

            </div>


        <?php else: ?>


            <?php foreach ($scholarships as $scholarship): ?>

                <?php

                $title =
                    $scholarship['title'] ??
                    'Untitled Scholarship';

                $organization =
                    $scholarship['organization'] ??
                    '';

                $description =
                    $scholarship['description'] ??
                    '';

                $amount =
                    $scholarship['amount'] ??
                    '';

                $deadline =
                    $scholarship['deadline'] ??
                    '';

                $location =
                    $scholarship['location'] ??
                    '';

                $studyLevel =
                    $scholarship['study_level'] ??
                    '';

                $field =
                    $scholarship['field_of_study'] ??
                    '';

                $applicationUrl =
                    $scholarship['application_url'] ??
                    '';

                $sourceUrl =
                    $scholarship['source_url'] ??
                    '';

                $image =
                    scholarshipImage(
                        $scholarship['image_url'] ?? ''
                    );

                $featured =
                    !empty(
                        $scholarship['featured']
                    );

                ?>


                <article
                    class="scholarship-card"
                    data-title="<?= e($title) ?>"
                    data-organization="<?= e($organization) ?>"
                    data-description="<?= e($description) ?>"
                    data-study-level="<?= e($studyLevel) ?>"
                >


                    <!-- IMAGE -->

                    <div class="card-image-wrap">

                        <img
                            src="<?= e($image) ?>"
                            alt="<?= e($title) ?>"
                            class="card-image"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='<?= e(scholarshipImage('')) ?>';"
                        >


                        <?php if ($featured): ?>

                            <div class="featured-badge">

                                <!-- STAR SVG -->

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M12 2.8
                                           L14.8 8.5
                                           L21.1 9.4
                                           L16.5 13.8
                                           L17.6 20
                                           L12 17.1
                                           L6.4 20
                                           L7.5 13.8
                                           L2.9 9.4
                                           L9.2 8.5
                                           Z"
                                    />

                                </svg>

                                Featured

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- BODY -->

                    <div class="card-body">


                        <?php if ($organization): ?>

                            <div class="organization">
                                <?= e($organization) ?>
                            </div>

                        <?php endif; ?>


                        <h3 class="card-title">
                            <?= e($title) ?>
                        </h3>


                        <?php if ($description): ?>

                            <p class="card-description">
                                <?= e($description) ?>
                            </p>

                        <?php endif; ?>


                        <div class="details">


                            <?php if ($amount): ?>

                                <div class="detail-item">

                                    <!-- MONEY SVG -->

                                    <svg
                                        class="detail-icon"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >

                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="3"
                                        />

                                        <path
                                            d="M7 9h.01M17 15h.01"
                                        />

                                    </svg>


                                    <div>

                                        <span class="detail-label">
                                            Benefits:
                                        </span>

                                        <span class="detail-value">
                                            <?= e($amount) ?>
                                        </span>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <?php if ($deadline): ?>

                                <div class="detail-item">

                                    <!-- CALENDAR SVG -->

                                    <svg
                                        class="detail-icon"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >

                                        <rect
                                            x="3"
                                            y="4"
                                            width="18"
                                            height="17"
                                            rx="2"
                                        />

                                        <path
                                            d="M16 2v4M8 2v4M3 9h18"
                                        />

                                    </svg>


                                    <div>

                                        <span class="detail-label">
                                            Deadline:
                                        </span>

                                        <span
                                            class="detail-value <?= e(deadlineClass($deadline)) ?>"
                                        >
                                            <?= e(formatDeadline($deadline)) ?>
                                        </span>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <?php if ($location): ?>

                                <div class="detail-item">

                                    <!-- LOCATION SVG -->

                                    <svg
                                        class="detail-icon"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >

                                        <path
                                            d="M20 10
                                               C20 15
                                               12 21
                                               12 21
                                               C12 21
                                               4 15
                                               4 10
                                               A8 8 0 0 1 20 10Z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="10"
                                            r="2.5"
                                        />

                                    </svg>


                                    <div>

                                        <span class="detail-label">
                                            Location:
                                        </span>

                                        <span class="detail-value">
                                            <?= e($location) ?>
                                        </span>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <?php if ($studyLevel): ?>

                                <div class="detail-item">

                                    <!-- EDUCATION SVG -->

                                    <svg
                                        class="detail-icon"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >

                                        <path
                                            d="M2 8.5
                                               L12 4
                                               L22 8.5
                                               L12 13
                                               L2 8.5Z"
                                        />

                                        <path
                                            d="M6 10.5
                                               V15
                                               C6 17
                                               18 17
                                               18 15
                                               V10.5"
                                        />

                                        <path
                                            d="M22 8.5V15"
                                        />

                                    </svg>


                                    <div>

                                        <span class="detail-label">
                                            Study Level:
                                        </span>

                                        <span class="detail-value">
                                            <?= e($studyLevel) ?>
                                        </span>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <?php if ($field): ?>

                                <div class="detail-item">

                                    <!-- BOOK SVG -->

                                    <svg
                                        class="detail-icon"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >

                                        <path
                                            d="M4 5.5
                                               C4 4.7 4.7 4 5.5 4
                                               H10
                                               C11.1 4 12 4.9 12 6
                                               V20
                                               C12 18.9 11.1 18 10 18
                                               H5.5
                                               C4.7 18 4 17.3 4 16.5
                                               V5.5Z"
                                        />

                                        <path
                                            d="M20 5.5
                                               C20 4.7 19.3 4 18.5 4
                                               H14
                                               C12.9 4 12 4.9 12 6
                                               V20
                                               C12 18.9 12.9 18 14 18
                                               H18.5
                                               C19.3 18 20 17.3 20 16.5
                                               V5.5Z"
                                        />

                                    </svg>


                                    <div>

                                        <span class="detail-label">
                                            Field:
                                        </span>

                                        <span class="detail-value">
                                            <?= e($field) ?>
                                        </span>

                                    </div>

                                </div>

                            <?php endif; ?>


                        </div>


                        <?php

                        $applyLink =
                            $applicationUrl
                            ?: $sourceUrl;

                        ?>


                        <?php if ($applyLink): ?>

                            <a
                                href="<?= e($applyLink) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="apply-btn"
                            >

                                Apply / View Details

                                <!-- EXTERNAL LINK SVG -->

                                <svg
                                    class="apply-icon"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M14 3h7v7"
                                    />

                                    <path
                                        d="M10 14L21 3"
                                    />

                                    <path
                                        d="M21 14v5
                                           a2 2 0 0 1-2 2H5
                                           a2 2 0 0 1-2-2V5
                                           a2 2 0 0 1 2-2h5"
                                    />

                                </svg>

                            </a>

                        <?php else: ?>

                            <div
                                class="apply-btn"
                                style="
                                    background:#94a3b8;
                                    cursor:not-allowed;
                                "
                            >

                                Details unavailable

                            </div>

                        <?php endif; ?>


                    </div>

                </article>


            <?php endforeach; ?>


        <?php endif; ?>

    </div>

</main>


<!-- ============================================================
     FOOTER
============================================================ -->

<footer>

    <p>
        © <?= date('Y') ?>
        Flexi Educational Consult
    </p>

    <p>
        Scholarships and educational opportunities
    </p>

</footer>


<script>

/* ============================================================
   DATA FROM PHP
============================================================ */

const scholarshipCards =
    Array.from(
        document.querySelectorAll(
            '.scholarship-card'
        )
    );

const searchInput =
    document.getElementById(
        'searchInput'
    );

const studyFilter =
    document.getElementById(
        'studyFilter'
    );

const resultCount =
    document.getElementById(
        'resultCount'
    );


/* ============================================================
   CREATE STUDY LEVEL FILTER OPTIONS
============================================================ */

const studyLevels =
    new Set();


scholarshipCards.forEach(
    function(card) {

        const level =
            card.dataset.studyLevel
                ? card.dataset.studyLevel.trim()
                : '';

        if (level) {

            studyLevels.add(level);

        }

    }
);


Array.from(studyLevels)
    .sort()
    .forEach(
        function(level) {

            const option =
                document.createElement(
                    'option'
                );

            option.value =
                level;

            option.textContent =
                level;

            studyFilter.appendChild(
                option
            );

        }
    );


/* ============================================================
   FILTER SCHOLARSHIPS
============================================================ */

function filterScholarships() {

    const search =
        searchInput.value
            .trim()
            .toLowerCase();

    const selectedLevel =
        studyFilter.value
            .trim()
            .toLowerCase();


    let visible = 0;


    scholarshipCards.forEach(
        function(card) {

            const text = [

                card.dataset.title,

                card.dataset.organization,

                card.dataset.description,

                card.dataset.studyLevel

            ]
            .join(' ')
            .toLowerCase();


            const level =
                (
                    card.dataset.studyLevel ||
                    ''
                )
                .trim()
                .toLowerCase();


            const matchesSearch =
                !search ||
                text.includes(search);


            const matchesLevel =
                !selectedLevel ||
                level === selectedLevel;


            if (
                matchesSearch &&
                matchesLevel
            ) {

                card.style.display =
                    '';

                visible++;

            } else {

                card.style.display =
                    'none';

            }

        }
    );


    if (resultCount) {

        if (
            !search &&
            !selectedLevel
        ) {

            resultCount.textContent =
                scholarshipCards.length +
                (
                    scholarshipCards.length === 1
                        ? ' scholarship'
                        : ' scholarships'
                );

        } else {

            resultCount.textContent =
                visible +
                (
                    visible === 1
                        ? ' scholarship found'
                        : ' scholarships found'
                );

        }

    }


    updateEmptySearchState(
        visible
    );

}


/* ============================================================
   SEARCH EMPTY STATE
============================================================ */

function updateEmptySearchState(
    visible
) {

    const existing =
        document.getElementById(
            'searchEmptyState'
        );


    if (existing) {

        existing.remove();

    }


    if (
        visible === 0 &&
        scholarshipCards.length > 0
    ) {

        const box =
            document.createElement(
                'div'
            );

        box.id =
            'searchEmptyState';

        box.className =
            'state-box';

        box.style.gridColumn =
            '1 / -1';


        box.innerHTML = `

            <svg
                class="state-icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >

                <circle
                    cx="11"
                    cy="11"
                    r="7"
                />

                <path
                    d="M20 20L16.2 16.2"
                />

            </svg>

            <h3>
                No scholarship found
            </h3>

            <p>
                Try another search term or study level.
            </p>

        `;


        document
            .getElementById(
                'scholarshipContainer'
            )
            .appendChild(box);

    }

}


/* ============================================================
   EVENTS
============================================================ */

if (searchInput) {

    searchInput.addEventListener(
        'input',
        filterScholarships
    );

}


if (studyFilter) {

    studyFilter.addEventListener(
        'change',
        filterScholarships
    );

}


/* ============================================================
   INITIAL COUNT
============================================================ */

filterScholarships();

</script>


</body>

</html>
