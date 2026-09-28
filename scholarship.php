<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// SCHOLARSHIP PAGE
//
// Data source:
// Supabase -> public.scholarships
//
// Publicly displayed scholarships must have:
// status = active
// ============================================================

$supabaseUrl = 'https://ryvauylmymcvbvvlaceb.supabase.co';
$supabaseKey = 'sb_publishable_zF84MIhSPOZ3MXth_LLqDA_yQ4pIvp6';

$endpoint = $supabaseUrl . '/rest/v1/scholarships'
    . '?select=id,title,organization,description,eligibility,amount,deadline,location,study_level,field_of_study,application_url,source_url,image_url,slug,status,featured,created_at,updated_at'
    . '&status=eq.active'
    . '&order=featured.desc,deadline.asc,created_at.desc';

$ch = curl_init($endpoint);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ],
    CURLOPT_TIMEOUT => 15,
    CURLOPT_CONNECTTIMEOUT => 10
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

$scholarships = [];

if (!$curlError && $httpCode >= 200 && $httpCode < 300 && $response) {
    $decoded = json_decode($response, true);

    if (is_array($decoded)) {
        $scholarships = $decoded;
    }
}

$hasError = false;

if ($curlError || ($httpCode && ($httpCode < 200 || $httpCode >= 300))) {
    $hasError = true;
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
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

    return date('M j, Y', $timestamp);
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

    $days = floor(($timestamp - time()) / 86400);

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
        '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="450" viewBox="0 0 800 450">
            <rect width="800" height="450" fill="#003366"/>
            <circle cx="400" cy="180" r="70" fill="none" stroke="#FFD700" stroke-width="8"/>
            <path d="M335 180h130M400 115v130" stroke="#FFD700" stroke-width="8" stroke-linecap="round"/>
            <text x="400" y="330" text-anchor="middle" font-family="Arial,sans-serif" font-size="32" fill="white">Flexi Educational Consult</text>
        </svg>'
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <meta name="theme-color" content="#003366">

    <title>Scholarships | Flexi Educational Consult</title>

    <meta name="description"
          content="Find current scholarship opportunities for secondary school, undergraduate, postgraduate and international students through Flexi Educational Consult.">

    <meta name="keywords"
          content="scholarships, Nigerian scholarships, undergraduate scholarships, postgraduate scholarships, international scholarships, Flexi Educational Consult">

    <link rel="canonical"
          href="https://flexieduconsult.com.ng/scholarship.php">

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
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

        /* =====================================================
           HEADER
        ===================================================== */

        .top-header {
            background: #003366;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,.12);
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            min-height: 72px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #fff;
            color: #003366;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .brand-text {
            min-width: 0;
        }

        .brand-name {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            white-space: nowrap;
        }

        .brand-tagline {
            margin: 1px 0 0;
            font-size: 11px;
            opacity: .85;
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .desktop-nav a {
            color: #fff;
            padding: 9px 11px;
            border-radius: 7px;
            font-size: 14px;
            transition: background .2s ease;
        }

        .desktop-nav a:hover,
        .desktop-nav a.active {
            background: rgba(255,255,255,.14);
        }

        .mobile-menu-btn {
            display: none;
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 8px;
            background: rgba(255,255,255,.12);
            color: #fff;
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        .mobile-menu {
            display: none;
            background: #00284f;
            border-top: 1px solid rgba(255,255,255,.1);
        }

        .mobile-menu.open {
            display: block;
        }

        .mobile-menu a {
            display: block;
            padding: 13px 20px;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,.08);
            font-size: 14px;
        }

        /* =====================================================
           HERO
        ===================================================== */

        .hero {
            background:
                linear-gradient(135deg, #003366 0%, #004b80 55%, #2E8B57 100%);
            color: #fff;
            padding: 58px 18px 62px;
        }

        .hero-inner {
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 12px;
            border-radius: 30px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .hero h1 {
            margin: 0 auto 12px;
            max-width: 800px;
            font-size: clamp(30px, 6vw, 48px);
            line-height: 1.12;
        }

        .hero p {
            max-width: 720px;
            margin: 0 auto;
            font-size: 16px;
            opacity: .92;
        }

        /* =====================================================
           MAIN
        ===================================================== */

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 18px 60px;
        }

        /* =====================================================
           SEARCH / FILTERS
        ===================================================== */

        .filter-box {
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            margin-top: -48px;
            position: relative;
            z-index: 5;
            box-shadow: 0 7px 28px rgba(0,0,0,.10);
            display: grid;
            grid-template-columns: minmax(0, 1fr) 230px;
            gap: 12px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            width: 19px;
            height: 19px;
            pointer-events: none;
        }

        .search-input,
        .study-filter {
            width: 100%;
            height: 48px;
            border: 1px solid #d8dee7;
            border-radius: 9px;
            background: #fff;
            color: #1f2937;
            outline: none;
        }

        .search-input {
            padding: 0 15px 0 44px;
        }

        .study-filter {
            padding: 0 13px;
            cursor: pointer;
        }

        .search-input:focus,
        .study-filter:focus {
            border-color: #2E8B57;
            box-shadow: 0 0 0 3px rgba(46,139,87,.12);
        }

        /* =====================================================
           SECTION HEADER
        ===================================================== */

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 15px;
            margin: 35px 0 18px;
        }

        .section-title {
            margin: 0;
            color: #003366;
            font-size: 25px;
        }

        .section-subtitle {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .result-count {
            color: #64748b;
            font-size: 13px;
            white-space: nowrap;
        }

        /* =====================================================
           CARDS
        ===================================================== */

        .scholarship-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .scholarship-card {
            background: #fff;
            border: 1px solid #e4e8ee;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-width: 0;
            box-shadow: 0 3px 14px rgba(0,0,0,.045);
            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .scholarship-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(0,0,0,.09);
        }

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
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
        }

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
            margin: 0 0 9px;
            color: #003366;
            font-size: 18px;
            line-height: 1.35;
        }

        .card-description {
            margin: 0 0 15px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .details {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
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
            flex: 0 0 17px;
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

        .apply-btn {
            margin-top: auto;
            width: 100%;
            min-height: 44px;
            border-radius: 8px;
            background: #003366;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            transition: background .2s ease;
        }

        .apply-btn:hover {
            background: #00284f;
        }

        .apply-icon {
            width: 17px;
            height: 17px;
        }

        /* =====================================================
           STATES
        ===================================================== */

        .state-box {
            background: #fff;
            border: 1px solid #e4e8ee;
            border-radius: 14px;
            padding: 50px 20px;
            text-align: center;
            color: #64748b;
        }

        .state-icon {
            width: 42px;
            height: 42px;
            margin: 0 auto 13px;
            color: #2E8B57;
        }

        .state-box h3 {
            margin: 0 0 6px;
            color: #003366;
            font-size: 18px;
        }

        .state-box p {
            margin: 0;
            font-size: 14px;
        }

        .spinner {
            width: 36px;
            height: 36px;
            margin: 0 auto 15px;
            border: 3px solid #dbe4ec;
            border-top-color: #003366;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* =====================================================
           PAGINATION
        ===================================================== */

        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 28px;
        }

        .page-btn {
            min-width: 38px;
            height: 38px;
            padding: 0 10px;
            border: 1px solid #d8dee7;
            background: #fff;
            color: #334155;
            border-radius: 7px;
            cursor: pointer;
            font-size: 13px;
        }

        .page-btn:hover {
            border-color: #003366;
        }

        .page-btn.active {
            background: #003366;
            border-color: #003366;
            color: #fff;
        }

        .page-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        footer {
            background: #00284f;
            color: #fff;
            margin-top: 0;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 42px 18px 25px;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 35px;
        }

        .footer-title {
            margin: 0 0 10px;
            font-size: 17px;
        }

        .footer-text {
            margin: 0;
            color: rgba(255,255,255,.75);
            font-size: 13px;
            line-height: 1.7;
        }

        .footer-heading {
            margin: 0 0 12px;
            font-size: 14px;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-links a {
            color: rgba(255,255,255,.75);
            font-size: 13px;
        }

        .footer-links a:hover {
            color: #fff;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 17px 18px;
            border-top: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.6);
            font-size: 12px;
            text-align: center;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 950px) {
            .scholarship-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .desktop-nav {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .footer-inner {
                grid-template-columns: 1fr 1fr;
            }

            .footer-inner > div:first-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 650px) {
            .header-inner {
                min-height: 64px;
            }

            .brand-name {
                font-size: 15px;
            }

            .brand-tagline {
                display: none;
            }

            .hero {
                padding: 45px 16px 58px;
            }

            .hero p {
                font-size: 14px;
            }

            .main-container {
                padding-left: 14px;
                padding-right: 14px;
            }

            .filter-box {
                grid-template-columns: 1fr;
                margin-top: -40px;
            }

            .section-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 5px;
            }

            .scholarship-grid {
                grid-template-columns: 1fr;
            }

            .card-image-wrap {
                height: 200px;
            }

            .footer-inner {
                grid-template-columns: 1fr;
            }

            .footer-inner > div:first-child {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<!-- =========================================================
     HEADER
========================================================= -->

<header class="top-header">

    <div class="header-inner">

        <a href="/" class="brand" aria-label="Flexi Educational Consult Home">

            <div class="brand-logo" aria-hidden="true">
                F
            </div>

            <div class="brand-text">
                <p class="brand-name">Flexi Educational Consult</p>
                <p class="brand-tagline">Your Education, Our Priority</p>
            </div>

        </a>

        <nav class="desktop-nav" aria-label="Main navigation">

            <a href="/">Home</a>

            <a href="/study.php">Study</a>

            <a href="/novel.php">Literature</a>

            <a href="/scholarship.php" class="active">
                Scholarships
            </a>

            <a href="/news">News</a>

            <a href="/contactsupport.html">Support</a>

        </nav>

        <button
            class="mobile-menu-btn"
            id="mobileMenuBtn"
            type="button"
            aria-label="Open navigation menu"
            aria-expanded="false"
        >
            <svg
                width="21"
                height="21"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                aria-hidden="true"
            >
                <path d="M4 6h16"></path>
                <path d="M4 12h16"></path>
                <path d="M4 18h16"></path>
            </svg>
        </button>

    </div>

    <div class="mobile-menu" id="mobileMenu">

        <a href="/">Home</a>
        <a href="/study.php">Study</a>
        <a href="/novel.php">Literature</a>
        <a href="/scholarship.php">Scholarships</a>
        <a href="/news">News</a>
        <a href="/contactsupport.html">Support</a>

    </div>

</header>


<!-- =========================================================
     HERO
========================================================= -->

<section class="hero">

    <div class="hero-inner">

        <div class="hero-badge">

            <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M12 3l2.6 5.27L20.4 9.1l-4.2 4.1.99 5.79L12 16.27 6.81 19l.99-5.79-4.2-4.1 5.8-.83L12 3z"></path>
            </svg>

            Scholarship Opportunities

        </div>

        <h1>
            Discover Scholarship Opportunities
        </h1>

        <p>
            Explore available scholarships and educational funding
            opportunities for students at different levels of study.
        </p>

    </div>

</section>


<!-- =========================================================
     MAIN
========================================================= -->

<main class="main-container">

    <!-- SEARCH -->

    <section class="filter-box" aria-label="Scholarship search and filters">

        <div class="input-wrap">

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
                <circle cx="11" cy="11" r="7"></circle>
                <path d="m20 20-4-4"></path>
            </svg>

            <input
                type="search"
                id="searchInput"
                class="search-input"
                placeholder="Search scholarships, organizations, fields..."
                autocomplete="off"
                aria-label="Search scholarships"
            >

        </div>

        <select
            id="studyFilter"
            class="study-filter"
            aria-label="Filter by study level"
        >

            <option value="">All Study Levels</option>
            <option value="secondary school">Secondary School</option>
            <option value="undergraduate">Undergraduate</option>
            <option value="postgraduate">Postgraduate</option>
            <option value="master's">Master's</option>
            <option value="phd">PhD</option>
            <option value="international students">International Students</option>

        </select>

    </section>


    <!-- SECTION HEADER -->

    <section class="section-header">

        <div>

            <h2 class="section-title">
                Available Scholarships
            </h2>

            <p class="section-subtitle">
                Find an opportunity that matches your academic goals.
            </p>

        </div>

        <div
            class="result-count"
            id="resultCount"
            aria-live="polite"
        >
            Loading...
        </div>

    </section>


    <!-- LOADING -->

    <div
        class="state-box"
        id="loadingState"
    >

        <div class="spinner" aria-hidden="true"></div>

        <h3>Loading scholarships</h3>

        <p>
            Please wait while we retrieve the latest opportunities.
        </p>

    </div>


    <!-- ERROR -->

    <div
        class="state-box"
        id="errorState"
        style="display:none;"
    >

        <svg
            class="state-icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <circle cx="12" cy="12" r="9"></circle>
            <path d="M12 8v4"></path>
            <path d="M12 16h.01"></path>
        </svg>

        <h3>Unable to load scholarships</h3>

        <p>
            We could not retrieve the scholarship list right now.
            Please try again later.
        </p>

    </div>


    <!-- EMPTY -->

    <div
        class="state-box"
        id="emptyState"
        style="display:none;"
    >

        <svg
            class="state-icon"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <path d="M4 7h16"></path>
            <path d="M6 7l1 13h10l1-13"></path>
            <path d="M9 7V4h6v3"></path>
            <path d="M10 11v5"></path>
            <path d="M14 11v5"></path>
        </svg>

        <h3>No scholarships found</h3>

        <p>
            Try another search term or select a different study level.
        </p>

    </div>


    <!-- SCHOLARSHIP CARDS -->

    <section
        class="scholarship-grid"
        id="scholarshipGrid"
        aria-live="polite"
    ></section>


    <!-- PAGINATION -->

    <nav
        class="pagination"
        id="pagination"
        aria-label="Scholarship pagination"
    ></nav>

</main>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    <div class="footer-inner">

        <div>

            <h3 class="footer-title">
                Flexi Educational Consult
            </h3>

            <p class="footer-text">
                Providing educational resources, scholarship
                opportunities, examination support and learning
                information for students.
            </p>

        </div>

        <div>

            <h4 class="footer-heading">
                Resources
            </h4>

            <div class="footer-links">

                <a href="/study.php">Study Resources</a>

                <a href="/novel.php">Literature</a>

                <a href="/scholarship.php">Scholarships</a>

                <a href="/cbt.html">CBT Practice</a>

                <a href="/pdf.html">PDF Resources</a>

            </div>

        </div>

        <div>

            <h4 class="footer-heading">
                Quick Links
            </h4>

            <div class="footer-links">

                <a href="/">Home</a>

                <a href="/news">News</a>

                <a href="/groups.html">Groups</a>

                <a href="/location.html">Location</a>

                <a href="/contactsupport.html">Support</a>

            </div>

        </div>

    </div>

    <div class="footer-bottom">

        © <?php echo date('Y'); ?>
        Flexi Educational Consult. All rights reserved.

    </div>

</footer>


<script>
/* ============================================================
   MOBILE MENU
============================================================ */

const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

mobileMenuBtn.addEventListener('click', function () {

    const isOpen = mobileMenu.classList.toggle('open');

    mobileMenuBtn.setAttribute(
        'aria-expanded',
        isOpen ? 'true' : 'false'
    );

});


/* ============================================================
   SCHOLARSHIP DATA
============================================================ */

const scholarshipData = <?php
    echo json_encode(
        $scholarships,
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    );
?>;

const serverHasError = <?php echo $hasError ? 'true' : 'false'; ?>;


/* ============================================================
   ELEMENTS
============================================================ */

const loadingState = document.getElementById('loadingState');
const errorState = document.getElementById('errorState');
const emptyState = document.getElementById('emptyState');
const scholarshipGrid = document.getElementById('scholarshipGrid');
const pagination = document.getElementById('pagination');
const resultCount = document.getElementById('resultCount');
const searchInput = document.getElementById('searchInput');
const studyFilter = document.getElementById('studyFilter');


/* ============================================================
   SETTINGS
============================================================ */

const ITEMS_PER_PAGE = 9;

let currentPage = 1;
let filteredScholarships = [];


/* ============================================================
   SVG ICONS
============================================================ */

const icons = {

    calendar: `
        <svg class="detail-icon"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <rect x="3" y="4" width="18" height="17" rx="2"></rect>
            <path d="M16 2v4"></path>
            <path d="M8 2v4"></path>
            <path d="M3 10h18"></path>
        </svg>
    `,

    money: `
        <svg class="detail-icon"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <circle cx="12" cy="12" r="9"></circle>
            <path d="M15 8.5c-.7-.7-1.7-1-3-1-1.7 0-3 .9-3 2.2 0 3.1 6 1.4 6 4.3 0 1.3-1.2 2.3-3.1 2.3-1.3 0-2.4-.4-3.1-1.1"></path>
            <path d="M12 6v12"></path>
        </svg>
    `,

    location: `
        <svg class="detail-icon"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0z"></path>
            <circle cx="12" cy="10" r="2.5"></circle>
        </svg>
    `,

    education: `
        <svg class="detail-icon"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <path d="M3 9l9-5 9 5-9 5-9-5z"></path>
            <path d="M7 11v5c3 2 7 2 10 0v-5"></path>
            <path d="M21 10v6"></path>
        </svg>
    `,

    book: `
        <svg class="detail-icon"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5z"></path>
            <path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20"></path>
        </svg>
    `,

    external: `
        <svg class="apply-icon"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             aria-hidden="true">
            <path d="M14 5h5v5"></path>
            <path d="M10 14L19 5"></path>
            <path d="M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5"></path>
        </svg>
    `
};


/* ============================================================
   ESCAPE HTML
============================================================ */

function escapeHTML(value) {

    if (value === null || value === undefined) {
        return '';
    }

    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


/* ============================================================
   FORMAT DATE
============================================================ */

function formatDate(dateString) {

    if (!dateString) {
        return 'No deadline specified';
    }

    const date = new Date(dateString + 'T00:00:00');

    if (Number.isNaN(date.getTime())) {
        return escapeHTML(dateString);
    }

    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
}


/* ============================================================
   DEADLINE CLASS
============================================================ */

function getDeadlineClass(dateString) {

    if (!dateString) {
        return '';
    }

    const date = new Date(dateString + 'T23:59:59');

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    const difference =
        date.getTime() - Date.now();

    const days =
        Math.ceil(difference / 86400000);

    if (days < 0) {
        return 'deadline-past';
    }

    if (days <= 14) {
        return 'deadline-soon';
    }

    return '';
}


/* ============================================================
   NORMALIZE TEXT
============================================================ */

function normalizeText(value) {

    return String(value || '')
        .toLowerCase()
        .trim();

}


/* ============================================================
   FILTER SCHOLARSHIPS
============================================================ */

function filterScholarships() {

    const search =
        normalizeText(searchInput.value);

    const level =
        normalizeText(studyFilter.value);

    filteredScholarships =
        scholarshipData.filter(function (item) {

            const searchableText = [
                item.title,
                item.organization,
                item.description,
                item.eligibility,
                item.field_of_study,
                item.location,
                item.study_level
            ]
            .map(normalizeText)
            .join(' ');

            const matchesSearch =
                !search ||
                searchableText.includes(search);

            const matchesLevel =
                !level ||
                normalizeText(item.study_level)
                    .includes(level);

            return matchesSearch && matchesLevel;

        });

    currentPage = 1;

    render();

}


/* ============================================================
   CREATE CARD
============================================================ */

function createCard(item) {

    const title =
        escapeHTML(item.title || 'Scholarship Opportunity');

    const organization =
        escapeHTML(
            item.organization ||
            'Scholarship Provider'
        );

    const description =
        escapeHTML(
            item.description ||
            'Scholarship opportunity for eligible students.'
        );

    const amount =
        escapeHTML(
            item.amount ||
            'Not specified'
        );

    const location =
        escapeHTML(
            item.location ||
            'Not specified'
        );

    const studyLevel =
        escapeHTML(
            item.study_level ||
            'Not specified'
        );

    const field =
        escapeHTML(
            item.field_of_study ||
            'Not specified'
        );

    const deadline =
        formatDate(item.deadline);

    const deadlineClassName =
        getDeadlineClass(item.deadline);

    const image =
        escapeHTML(
            item.image_url ||
            ''
        );

    const applicationUrl =
        escapeHTML(
            item.application_url ||
            '#'
        );

    const featured =
        item.featured === true;

    return `
        <article class="scholarship-card">

            <div class="card-image-wrap">

                <img
                    class="card-image"
                    src="${image}"
                    alt="${title}"
                    loading="lazy"
                    onerror="this.style.display='none';"
                >

                ${
                    featured
                    ? `
                        <span class="featured-badge">
                            Featured Scholarship
                        </span>
                    `
                    : ''
                }

            </div>

            <div class="card-body">

                <div class="organization">
                    ${organization}
                </div>

                <h3 class="card-title">
                    ${title}
                </h3>

                <p class="card-description">
                    ${description}
                </p>

                <div class="details">

                    <div class="detail-item">
                        ${icons.money}

                        <div>
                            <span class="detail-label">
                                Amount:
                            </span>
                            <span class="detail-value">
                                ${amount}
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        ${icons.calendar}

                        <div>
                            <span class="detail-label">
                                Deadline:
                            </span>

                            <span
                                class="detail-value ${deadlineClassName}"
                            >
                                ${deadline}
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        ${icons.education}

                        <div>
                            <span class="detail-label">
                                Study Level:
                            </span>

                            <span class="detail-value">
                                ${studyLevel}
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        ${icons.book}

                        <div>
                            <span class="detail-label">
                                Field:
                            </span>

                            <span class="detail-value">
                                ${field}
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        ${icons.location}

                        <div>
                            <span class="detail-label">
                                Location:
                            </span>

                            <span class="detail-value">
                                ${location}
                            </span>
                        </div>
                    </div>

                </div>

                <a
                    class="apply-btn"
                    href="${applicationUrl}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Apply Now
                    ${icons.external}
                </a>

            </div>

        </article>
    `;
}


/* ============================================================
   RENDER
============================================================ */

function render() {

    loadingState.style.display = 'none';
    errorState.style.display = 'none';

    scholarshipGrid.innerHTML = '';
    pagination.innerHTML = '';

    if (!filteredScholarships.length) {

        emptyState.style.display = 'block';

        resultCount.textContent =
            '0 scholarships';

        return;
    }

    emptyState.style.display = 'none';

    const totalItems =
        filteredScholarships.length;

    const totalPages =
        Math.ceil(totalItems / ITEMS_PER_PAGE);

    if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    const start =
        (currentPage - 1) *
        ITEMS_PER_PAGE;

    const end =
        start +
        ITEMS_PER_PAGE;

    const pageItems =
        filteredScholarships.slice(start, end);

    scholarshipGrid.innerHTML =
        pageItems
            .map(createCard)
            .join('');

    const firstItem =
        start + 1;

    const lastItem =
        Math.min(end, totalItems);

    resultCount.textContent =
        `${firstItem}-${lastItem} of ${totalItems} scholarship${totalItems === 1 ? '' : 's'}`;

    renderPagination(totalPages);

}


/* ============================================================
   PAGINATION
============================================================ */

function renderPagination(totalPages) {

    if (totalPages <= 1) {
        return;
    }

    const previousButton =
        document.createElement('button');

    previousButton.type = 'button';
    previousButton.className = 'page-btn';
    previousButton.textContent = 'Previous';
    previousButton.disabled = currentPage === 1;

    previousButton.addEventListener(
        'click',
        function () {

            if (currentPage > 1) {

                currentPage--;

                render();

                scrollToResults();

            }

        }
    );

    pagination.appendChild(previousButton);


    let startPage =
        Math.max(1, currentPage - 2);

    let endPage =
        Math.min(totalPages, currentPage + 2);

    for (
        let page = startPage;
        page <= endPage;
        page++
    ) {

        const button =
            document.createElement('button');

        button.type = 'button';

        button.className =
            'page-btn' +
            (page === currentPage ? ' active' : '');

        button.textContent = page;

        button.setAttribute(
            'aria-label',
            `Go to page ${page}`
        );

        if (page === currentPage) {
            button.setAttribute(
                'aria-current',
                'page'
            );
        }

        button.addEventListener(
            'click',
            function () {

                currentPage = page;

                render();

                scrollToResults();

            }
        );

        pagination.appendChild(button);

    }


    const nextButton =
        document.createElement('button');

    nextButton.type = 'button';
    nextButton.className = 'page-btn';
    nextButton.textContent = 'Next';
    nextButton.disabled =
        currentPage === totalPages;

    nextButton.addEventListener(
        'click',
        function () {

            if (currentPage < totalPages) {

                currentPage++;

                render();

                scrollToResults();

            }

        }
    );

    pagination.appendChild(nextButton);

}


/* ============================================================
   SCROLL TO RESULTS
============================================================ */

function scrollToResults() {

    const headerOffset = 90;

    const position =
        scholarshipGrid.getBoundingClientRect().top +
        window.scrollY -
        headerOffset;

    window.scrollTo({
        top: position,
        behavior: 'smooth'
    });

}


/* ============================================================
   SEARCH EVENTS
============================================================ */

searchInput.addEventListener(
    'input',
    filterScholarships
);

studyFilter.addEventListener(
    'change',
    filterScholarships
);


/* ============================================================
   INITIALIZE
============================================================ */

if (serverHasError) {

    loadingState.style.display = 'none';
    errorState.style.display = 'block';
    resultCount.textContent = '';

} else {

    filteredScholarships =
        Array.isArray(scholarshipData)
            ? scholarshipData
            : [];

    render();

}
</script>
</body>
</html>
