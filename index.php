<?php
// ============================================================
// FLEXI EDUCATIONAL CONSULT
// HOMEPAGE
// ============================================================

// ------------------------------------------------------------
// PAGE SETTINGS FOR SHARED HEAD COMPONENT
// ------------------------------------------------------------
$pageTitle = 'Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria';

$pageDescription = 'Flexi Educational Consult (Flexi Tutors) is an online CBT exam practice portal for JAMB, WAEC, NECO, and JUPEB.';

$pageKeywords = 'flexieduconsult, Flexi Educational Consult, Flexi Tutors, JAMB, WAEC, NECO, Flexi, CBT, jamb tutorials, waec tutorials, ssce tutorials, tutorials in shomolu, tutorials in bariga';

$pageImage = 'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';

// Homepage canonical changes when pagination is used.
$pageCanonical = 'https://www.flexieduconsult.com.ng/' .
    ($currentPage > 1 ? '?page=' . $currentPage : '');

$includeAdsense = true;
$includeToastify = true;

// Shared header uses this to identify the active page.
$activePage = 'index.php';


// ============================================================
// SERVER-SIDE: FETCH NEWS + PAGINATION
// ============================================================

$firebaseProjectId = "waec2026jamb2027";

$apiUrl = "https://firestore.googleapis.com/v1/projects/{$firebaseProjectId}/databases/(default)/documents/news";

$perPage = 10;

$currentPage = isset($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$allNews = [];

$response = @file_get_contents($apiUrl);

if ($response) {
    $data = json_decode($response, true);

    if (isset($data['documents']) && is_array($data['documents'])) {

        foreach ($data['documents'] as $doc) {

            $fields = $doc['fields'] ?? [];

            $docNameParts = explode('/', $doc['name'] ?? '');
            $docId = end($docNameParts);

            $timestamp = 0;

            if (!empty($fields['timestamp']['timestampValue'])) {
                $timestamp = strtotime(
                    $fields['timestamp']['timestampValue']
                );
            }

            $allNews[] = [
                'id'        => $docId,
                'title'     => $fields['title']['stringValue'] ?? 'News Update',
                'imageUrl'  => $fields['imageUrl']['stringValue'] ?? 'https://via.placeholder.com/88x66',
                'slug'      => $fields['slug']['stringValue'] ?? '',
                'timestamp' => $timestamp,
            ];
        }
    }
}


// ------------------------------------------------------------
// SORT NEWEST FIRST
// ------------------------------------------------------------

usort($allNews, function ($a, $b) {
    return $b['timestamp'] <=> $a['timestamp'];
});


// ------------------------------------------------------------
// PAGINATION CALCULATIONS
// ------------------------------------------------------------

$totalItems = count($allNews);

$totalPages = max(
    1,
    (int) ceil($totalItems / $perPage)
);

$currentPage = min(
    $currentPage,
    $totalPages
);

$offset = ($currentPage - 1) * $perPage;

$newsForPage = array_slice(
    $allNews,
    $offset,
    $perPage
);


// ============================================================
// BUILD NEWS TABLE HTML
// ============================================================

$serverNewsHtml = '';

if (empty($newsForPage)) {

    $serverNewsHtml =
        '<tr>' .
        '<td colspan="3" style="padding:15px;color:#555;font-size:14px;">' .
        'No news updates available at the moment.' .
        '</td>' .
        '</tr>';

} else {

    foreach ($newsForPage as $item) {

        $targetUrl = $item['slug']
            ? "/news/" . rawurlencode($item['slug'])
            : "/news/id/" . rawurlencode($item['id']);

        $safeUrl = htmlspecialchars(
            $targetUrl,
            ENT_QUOTES,
            'UTF-8'
        );

        $safeImage = htmlspecialchars(
            $item['imageUrl'],
            ENT_QUOTES,
            'UTF-8'
        );

        $safeTitle = htmlspecialchars(
            $item['title'],
            ENT_QUOTES,
            'UTF-8'
        );

        $serverNewsHtml .=
            '<tr onclick="window.location.href=\'' .
            $safeUrl .
            '\'">';

        $serverNewsHtml .=
            '<td style="width:88px;">' .
            '<img src="' . $safeImage . '"' .
            ' alt="' . $safeTitle . '"' .
            ' class="td-img"' .
            ' loading="lazy">' .
            '</td>';

        $serverNewsHtml .=
            '<td class="td-title">' .
            $safeTitle .
            '</td>';

        $serverNewsHtml .=
            '<td class="td-arrow">❯</td>';

        $serverNewsHtml .= '</tr>';
    }
}


// ============================================================
// BUILD PAGINATION HTML
// ============================================================

function flexiBuildPagination($currentPage, $totalPages)
{
    if ($totalPages <= 1) {
        return '';
    }

    $html =
        '<div class="pagination-bar" id="pagination-controls">';

    // Previous
    if ($currentPage > 1) {

        $html .=
            '<a href="?page=' .
            ($currentPage - 1) .
            '" class="pg-btn" aria-label="Previous page">‹</a>';

    } else {

        $html .=
            '<span class="pg-btn pg-disabled" aria-hidden="true">‹</span>';
    }


    // Page numbers
    $range = 2;

    $start = max(
        1,
        $currentPage - $range
    );

    $end = min(
        $totalPages,
        $currentPage + $range
    );


    if ($start > 1) {

        $html .=
            '<a href="?page=1" class="pg-btn">1</a>';

        if ($start > 2) {

            $html .=
                '<span class="pg-btn pg-ellipsis">…</span>';
        }
    }


    for ($i = $start; $i <= $end; $i++) {

        if ($i == $currentPage) {

            $html .=
                '<span class="pg-btn pg-active">' .
                $i .
                '</span>';

        } else {

            $html .=
                '<a href="?page=' .
                $i .
                '" class="pg-btn">' .
                $i .
                '</a>';
        }
    }


    if ($end < $totalPages) {

        if ($end < $totalPages - 1) {

            $html .=
                '<span class="pg-btn pg-ellipsis">…</span>';
        }

        $html .=
            '<a href="?page=' .
            $totalPages .
            '" class="pg-btn">' .
            $totalPages .
            '</a>';
    }


    // Next
    if ($currentPage < $totalPages) {

        $html .=
            '<a href="?page=' .
            ($currentPage + 1) .
            '" class="pg-btn" aria-label="Next page">›</a>';

    } else {

        $html .=
            '<span class="pg-btn pg-disabled" aria-hidden="true">›</span>';
    }


    $html .= '</div>';

    return $html;
}


$paginationHtml = flexiBuildPagination(
    $currentPage,
    $totalPages
);


// ============================================================
// SHARED HEAD
// ============================================================

require __DIR__ . '/includes/head.php';


// ============================================================
// PAGE-SPECIFIC STRUCTURED DATA
// ============================================================
//
// Kept here so the homepage retains its existing
// EducationalOrganization structured data.
// ============================================================
?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "@id": "https://www.flexieduconsult.com.ng/#organization",
  "name": "Flexi Educational Consult",
  "alternateName": "Flexi Tutors",
  "url": "https://www.flexieduconsult.com.ng",
  "logo": "https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg",
  "image": "https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg",
  "description": "Educational platform and tutoring provider in Nigeria offering UTME/JAMB CBT preparation, WAEC/NECO tutorials, and computer-based test assessments.",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "NG"
  },
  "areaServed": "NG",
  "sameAs": [
    "https://www.facebook.com/share/1HgB8GWwZ1/",
    "https://www.linkedin.com/company/flexi-educational-consult"
  ],
  "knowsAbout": [
    "UTME/JAMB Examination Preparation",
    "WAEC & NECO Secondary School Examinations",
    "Computer-Based Testing (CBT) Assessments",
    "Academic Tutoring"
  ]
}
</script>


<style>
/* ============================================================
   HOMEPAGE-ONLY STYLES
   Shared header/footer styles are now in:
   assets/css/flexi-brand.css
   ============================================================ */

:root {
    --blue: var(--flexi-primary);
    --green: var(--flexi-secondary);
    --yellow: var(--flexi-accent);
    --bg: #f4f7f6;
    --radius: 12px;
    --shadow: 0 4px 18px rgba(0,0,0,0.06);
}


/* ------------------------------------------------------------
   HOMEPAGE BASE
   ------------------------------------------------------------ */

.flexi-home {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: var(--bg);
    margin: 0;
    padding: 0;
    color: #333;
}


/*
 * Preserve the original homepage anti-copy behaviour.
 * Inputs/textareas are explicitly re-enabled below.
 */

.flexi-home,
.flexi-home * {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}


/* ------------------------------------------------------------
   SCREEN READER HEADING
   ------------------------------------------------------------ */

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}


/* ------------------------------------------------------------
   MAIN CONTAINER
   ------------------------------------------------------------ */

.flexi-home .container {
    max-width: 920px;
    margin: 24px auto;
    width: 94%;
}


/* ------------------------------------------------------------
   WELCOME BANNER
   ------------------------------------------------------------ */

#welcome-banner {
    margin-bottom: 16px;
    padding: 16px 18px;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border-left: 5px solid var(--blue);
    display: none;
    animation: flexiFadeIn .5s ease-in;
}


@keyframes flexiFadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


/* ------------------------------------------------------------
   ANNOUNCEMENT
   ------------------------------------------------------------ */

#announcement-banner {
    display: none;
    margin-bottom: 16px;
    padding: 16px 18px;
    background: #003366;
    color: white;
    border-radius: var(--radius);
    border-left: 5px solid #FFD700;
    box-shadow: var(--shadow);
}


/* ------------------------------------------------------------
   MAIN IMAGE SLIDER
   ------------------------------------------------------------ */

.slider-container {
    position: relative;
    width: 100%;
    height: 140px;
    margin-bottom: 18px;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    background: #001f3f;
}


.slider-slide {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity .6s ease;
}


.slider-slide.active {
    opacity: 1;
}


.slider-slide img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #001f3f;
}


.slider-dots {
    position: absolute;
    bottom: 10px;
    width: 100%;
    text-align: center;
}


.dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,.45);
    margin: 0 4px;
}


.active-dot {
    background: white;
}


/* ------------------------------------------------------------
   SECTION HEADINGS
   ------------------------------------------------------------ */

.section-heading {
    color: var(--blue);
    border-left: 5px solid var(--green);
    padding-left: 12px;
    font-size: 1.25rem;
    margin: 28px 0 14px;
    font-weight: 700;
}


/* ------------------------------------------------------------
   NEWS TABLE
   ------------------------------------------------------------ */

.news-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}


.news-table tr {
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.2s;
}


.news-table tr:last-child {
    border-bottom: none;
}


.news-table tr:hover {
    background: #f8faf9;
}


.news-table td {
    padding: 14px 16px;
    vertical-align: middle;
}


.td-img {
    width: 88px;
    height: 66px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
    display: block;
    background: #001f3f;
}


.td-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--blue);
    line-height: 1.4;
}


.td-arrow {
    text-align: right;
    color: #aaa;
    width: 28px;
    font-size: 16px;
}


/* ------------------------------------------------------------
   PAGINATION
   ------------------------------------------------------------ */

.pagination-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin: 22px 0;
    padding: 10px;
    background: white;
    border-radius: 30px;
    box-shadow: var(--shadow);
    flex-wrap: wrap;
}


.pg-btn {
    min-width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    background: white;
    color: var(--blue);
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
}


.pg-btn:hover {
    background: #f0f4f8;
    border-color: var(--blue);
}


.pg-active {
    background: var(--blue);
    color: white;
    border-color: var(--blue);
    cursor: default;
}


.pg-disabled {
    opacity: 0.4;
    cursor: default;
}


.pg-ellipsis {
    cursor: default;
}


/* ------------------------------------------------------------
   CUSTOMER CARE CARD
   ------------------------------------------------------------ */

.card {
    background: white;
    padding: 24px 28px;
    border-radius: var(--radius);
    border-top: 5px solid var(--green);
    margin-top: 12px;
    box-shadow: var(--shadow);
}


.card h3 {
    margin: 0 0 16px 0;
    color: var(--blue);
    font-size: 1.15rem;
}


.card input,
.card textarea {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;

    -webkit-user-select: text;
    -moz-user-select: text;
    -ms-user-select: text;
    user-select: text;
}


.btn-green {
    background: var(--green);
    color: white;
    border: none;
    padding: 14px;
    width: 100%;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    font-size: 15px;
}


.btn-green:hover {
    opacity: 0.92;
}


.btn-green:disabled {
    opacity: 0.65;
    cursor: wait;
}


/* ------------------------------------------------------------
   MOTIVATIONAL QUOTES
   ------------------------------------------------------------ */

.quote-slider-container {
    position: relative;
    width: 100%;
    min-height: 180px;
    margin-bottom: 16px;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    background: #001f3f;
}


.quote-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
}


.quote-slide.active {
    opacity: 1;
}


.quote-content-box {
    padding: 24px 28px;
    width: 100%;
    text-shadow: 0 1px 4px rgba(0,0,0,0.6);
}


.quote-content-box blockquote {
    margin: 0 0 10px 0;
    font-style: italic;
    font-size: 1.05rem;
    line-height: 1.45;
}


.quote-content-box cite {
    display: block;
    font-style: normal;
    font-weight: bold;
    font-size: 0.9rem;
    text-align: right;
}


/* ------------------------------------------------------------
   TESTIMONIALS
   ------------------------------------------------------------ */

.testimonials-section {
    margin-top: 36px;
    margin-bottom: 28px;
}


.testimonials-heading {
    color: var(--blue);
    border-left: 5px solid var(--green);
    padding-left: 12px;
    margin-bottom: 14px;
    font-size: 1.25rem;
    font-weight: 700;
}


.testimonial-slider-container {
    position: relative;
    width: 100%;
    height: 150px;
    overflow: hidden;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}


.testimonial-slide {
    position: absolute;
    width: 100%;
    height: 100%;
    padding: 22px 24px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    transform: translateX(100%);
}


.testimonial-slide.active {
    transform: translateX(0);
}


.testimonial-slide.exit {
    transform: translateX(-100%);
}


.student-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}


.student-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #e1e7ec;
    color: var(--blue);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
    border: 2px solid var(--green);
}


.student-details h4 {
    margin: 0;
    color: var(--blue);
    font-size: 15px;
    font-weight: 600;
}


.student-details span {
    font-size: 12px;
    color: #666;
}


.testimonial-text {
    margin: 0;
    font-size: 14px;
    color: #4a5568;
    line-height: 1.55;
    font-style: italic;
}


/* ------------------------------------------------------------
   SUPPORT WIDGET
   ------------------------------------------------------------ */

#support-btn {
    transition: transform 0.3s;
}


#support-btn:hover {
    transform: scale(1.1);
}


/* ------------------------------------------------------------
   RESPONSIVE
   ------------------------------------------------------------ */

@media (min-width: 768px) {

    .flexi-home .container {
        max-width: 980px;
        margin: 32px auto;
    }

    .slider-container {
        height: 220px;
    }

    .quote-slider-container {
        min-height: 220px;
    }

    .quote-content-box blockquote {
        font-size: 1.2rem;
    }

    .section-heading,
    .testimonials-heading {
        font-size: 1.35rem;
    }

    .td-title {
        font-size: 16px;
    }

    .news-table td {
        padding: 16px 18px;
    }

    .td-img {
        width: 100px;
        height: 72px;
    }

    .testimonial-slider-container {
        height: 160px;
    }

    .testimonial-text {
        font-size: 15px;
    }

    .card {
        padding: 28px 32px;
    }
}


@media (min-width: 1100px) {

    .flexi-home .container {
        max-width: 1040px;
    }

    .slider-container {
        height: 260px;
    }
}


@media (max-width: 480px) {

    .testimonial-slider-container {
        height: 165px;
    }

    .slider-container {
        height: 120px;
    }

    .flexi-home .container {
        margin: 16px auto;
    }

    .card {
        padding: 18px 16px;
    }

    .news-table td {
        padding: 12px;
    }

    .td-img {
        width: 72px;
        height: 54px;
    }

    .td-title {
        font-size: 14px;
    }
}
</style>


<!-- ============================================================
     SHARED HEADER
     ============================================================ -->

<?php
require __DIR__ . '/includes/header.php';
?>


<!-- ============================================================
     HOMEPAGE CONTENT
     ============================================================ -->

<main class="flexi-home">

    <div class="container">

        <h1 class="sr-only">
            Flexi Educational Consult - Online JAMB WAEC CBT Exam Practice Portal
        </h1>


        <!-- WELCOME BANNER -->

        <div id="welcome-banner">

            <span
                id="user-greeting"
                style="font-weight:bold;color:var(--blue);font-size:1.1rem;"
            ></span>

        </div>


        <!-- ANNOUNCEMENT -->

        <div id="announcement-banner">

            <h4
                id="ann-title"
                style="margin:0 0 5px 0;"
            ></h4>

            <p
                id="ann-content"
                style="margin:0;font-size:0.95rem;"
            ></p>

            <small
                id="ann-date"
                style="display:block;margin-top:8px;opacity:0.8;"
            ></small>

        </div>


        <!-- MOTIVATIONAL QUOTE SLIDER -->

        <div
            id="quote-slider-container"
            class="quote-slider-container"
            style="display:none;"
        >

            <div
                id="quote-slides-wrapper"
                class="quote-slides-wrapper"
            ></div>

            <div
                id="quote-dots"
                class="slider-dots"
            ></div>

        </div>


        <!-- MAIN PROMOTIONAL SLIDER -->

        <div class="slider-container">

            <div class="slider-slide active">

                <img
                    src="https://i.postimg.cc/XvkqQc3F/20260418-185555-2.jpg"
                    alt="JAMB UTME CBT Online Exam Practice Banner"
                >

            </div>


            <div class="slider-slide">

                <img
                    src="https://i.postimg.cc/pXBjLFpj/20260418-190953-2.jpg"
                    alt="WAEC NECO SSCE Preparation Tutorials Banner"
                >

            </div>


            <div class="slider-slide">

                <img
                    src="https://i.postimg.cc/76p3Srkc/Screenshot-20260418-191139-2.png"
                    alt="Flexi Educational Consult Academic Registration Banner"
                >

            </div>


            <div class="slider-dots">

                <span class="dot active-dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>

            </div>

        </div>


        <!-- NEWS -->

        <h2 class="section-heading">
            News Updates
        </h2>


        <table class="news-table">

            <tbody id="news-table-body">

                <?php echo $serverNewsHtml; ?>

            </tbody>

        </table>


        <?php echo $paginationHtml; ?>


        <!-- TESTIMONIALS -->

        <div class="testimonials-section">

            <h2 class="testimonials-heading">
                What Our Students Say
            </h2>


            <div class="testimonial-slider-container">

                <div class="testimonial-slide active">

                    <div class="student-info">

                        <div class="student-avatar">
                            CO
                        </div>

                        <div class="student-details">

                            <h4>
                                Chidi O.
                            </h4>

                            <span>
                                JAMB Candidate (Score: 312)
                            </span>

                        </div>

                    </div>


                    <p class="testimonial-text">
                        "The CBT Simulator on Flexi Tutors is the closest thing to the actual JAMB exam. It gave me the speed and accuracy I needed to score over 300!"
                    </p>

                </div>


                <div class="testimonial-slide">

                    <div class="student-info">

                        <div class="student-avatar">
                            AA
                        </div>

                        <div class="student-details">

                            <h4>
                                Amina A.
                            </h4>

                            <span>
                                WAEC Student (5 A1s)
                            </span>

                        </div>

                    </div>


                    <p class="testimonial-text">
                        "I downloaded all my past questions in PDF here. The online classroom groups kept me accountable during my final revisions. Thank you, Flexi!"
                    </p>

                </div>


                <div class="testimonial-slide">

                    <div class="student-info">

                        <div class="student-avatar">
                            TE
                        </div>

                        <div class="student-details">

                            <h4>
                                Tunde E.
                            </h4>

                            <span>
                                Post-UTME Student
                            </span>

                        </div>

                    </div>


                    <p class="testimonial-text">
                        "Highly recommended! The instant admission updates kept me from missing important post-UTME screening dates. Exceptional platform."
                    </p>

                </div>

            </div>

        </div>


        <!-- CUSTOMER CARE -->

        <div class="card">

            <h3>
                Customer Care
            </h3>


            <form id="contact-form">

                <input
                    type="email"
                    name="email"
                    id="contact-email"
                    placeholder="Your Email Address"
                    required
                >


                <textarea
                    name="message"
                    rows="3"
                    placeholder="How can we help you?"
                    required
                ></textarea>


                <button
                    type="submit"
                    id="submit-btn"
                    class="btn-green"
                >
                    Submit Inquiry
                </button>

            </form>

        </div>

    </div>

</main>


<!-- ============================================================
     SUPPORT WIDGET
     ============================================================ -->

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
    "
>

    <div
        id="support-tooltip"
        style="
            background:#003366;
            color:white;
            padding:10px 15px;
            border-radius:20px 20px 0px 20px;
            font-size:0.85rem;
            box-shadow:0 4px 10px rgba(0,0,0,0.2);
            animation:flexiFadeIn 0.5s;
            opacity:1;
        "
    >
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
        "
    >

        <svg
            width="30"
            height="30"
            viewBox="0 0 24 24"
            fill="white"
            aria-hidden="true"
        >

            <path
                d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"
            />

        </svg>

    </button>

</div>


<!-- ============================================================
     TOASTIFY
     ============================================================ -->

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


<!-- ============================================================
     FIREBASE + HOMEPAGE JAVASCRIPT
     ============================================================ -->

<script type="module">

import {
    initializeApp
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";


import {
    getFirestore,
    collection,
    getDocs,
    query,
    orderBy,
    limit
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";


import {
    getAuth,
    onAuthStateChanged,
    signOut
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";


// ============================================================
// PRESERVE EXISTING COPY/PASTE RESTRICTIONS
// ============================================================

document.addEventListener('copy', (e) => {
    e.preventDefault();
});


document.addEventListener('cut', (e) => {
    e.preventDefault();
});


document.addEventListener('paste', (e) => {
    e.preventDefault();
});


// ============================================================
// FIREBASE CONFIGURATION
// ============================================================

const firebaseConfig = {

    apiKey: "AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",

    authDomain: "auth.flexieduconsult.com.ng",

    projectId: "waec2026jamb2027"

};


const app = initializeApp(firebaseConfig);

const db = getFirestore(app);

const auth = getAuth(app);


// ============================================================
// AUTHENTICATION STATE
// ============================================================

onAuthStateChanged(auth, (user) => {

    const welcomeBanner =
        document.getElementById('welcome-banner');

    const userGreeting =
        document.getElementById('user-greeting');

    const authMenuBtn =
        document.getElementById('flexiAuthBtn');

    const contactEmail =
        document.getElementById('contact-email');


    if (user) {

        if (welcomeBanner) {
            welcomeBanner.style.display = "block";
        }


        const name =
            user.displayName ||
            (user.email ? user.email.split('@')[0] : 'Student');


        if (userGreeting) {

            userGreeting.innerHTML =
                `Welcome back, <span style="color:var(--green)">${escapeHtml(name)}</span> 👋`;

        }


        if (authMenuBtn) {

            authMenuBtn.innerText = "Logout";

            authMenuBtn.style.color = "#ff4d4d";


            authMenuBtn.onclick = async (e) => {

                e.preventDefault();

                try {

                    await signOut(auth);

                    window.location.reload();

                } catch (error) {

                    console.error(
                        "Logout error:",
                        error
                    );

                }

            };

        }


        if (contactEmail && user.email) {

            contactEmail.value = user.email;

        }

    } else {

        if (welcomeBanner) {
            welcomeBanner.style.display = "none";
        }


        if (authMenuBtn) {

            authMenuBtn.innerText = "Login";

            authMenuBtn.style.color = "white";


            authMenuBtn.onclick = (e) => {

                e.preventDefault();

                window.location.href =
                    "/login.html";

            };

        }

    }

});


// ============================================================
// HTML ESCAPING
// ============================================================

function escapeHtml(str) {

    return String(str ?? '')
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

}


// ============================================================
// ANNOUNCEMENT
// ============================================================

async function loadAnnouncement() {

    try {

        const annBanner =
            document.getElementById('announcement-banner');


        const q = query(

            collection(
                db,
                "announcements"
            ),

            orderBy(
                "createdAt",
                "desc"
            ),

            limit(1)

        );


        const snap =
            await getDocs(q);


        if (!snap.empty) {

            const data =
                snap.docs[0].data();


            document.getElementById(
                'ann-title'
            ).innerText =
                data.title || '';


            document.getElementById(
                'ann-content'
            ).innerText =
                data.content || '';


            let postedDate = '';


            if (
                data.createdAt &&
                typeof data.createdAt.toDate === 'function'
            ) {

                postedDate =
                    data.createdAt
                        .toDate()
                        .toLocaleDateString();

            } else if (
                data.createdAt &&
                data.createdAt.seconds
            ) {

                postedDate =
                    new Date(
                        data.createdAt.seconds * 1000
                    ).toLocaleDateString();

            }


            document.getElementById(
                'ann-date'
            ).innerText =
                postedDate
                    ? `Posted: ${postedDate}`
                    : '';


            annBanner.style.display =
                "block";

        }

    } catch (e) {

        console.error(
            "Error loading announcement:",
            e
        );

    }

}


// ============================================================
// MOTIVATIONAL QUOTES
// ============================================================

let quoteSlides = [];

let currentQuoteIndex = 0;

let quoteInterval = null;


async function loadMotivationalQuote() {

    try {

        const container =
            document.getElementById(
                'quote-slider-container'
            );


        const wrapper =
            document.getElementById(
                'quote-slides-wrapper'
            );


        const dotsContainer =
            document.getElementById(
                'quote-dots'
            );


        const q = query(

            collection(
                db,
                "quotes"
            ),

            orderBy(
                "createdAt",
                "desc"
            )

        );


        const snap =
            await getDocs(q);


        if (snap.empty) {

            container.style.display =
                'none';

            return;

        }


        wrapper.innerHTML = '';

        dotsContainer.innerHTML = '';


        let index = 0;


        snap.forEach(docSnap => {

            const data =
                docSnap.data();


            const slide =
                document.createElement('div');


            slide.className =
                `quote-slide ${
                    index === 0 ? 'active' : ''
                }`;


            if (data.bgImage) {

                slide.style.backgroundImage =
                    `url('${String(data.bgImage).replace(/'/g, "\\'")}')`;

            }


            const textColor =
                data.textColor ||
                '#ffffff';


            const authorColor =
                data.authorColor ||
                '#f1f5f9';


            slide.innerHTML = `

                <div class="quote-content-box">

                    <blockquote
                        style="color:${escapeHtml(textColor)};"
                    >
                        "${escapeHtml(data.text)}"
                    </blockquote>

                    <cite
                        style="color:${escapeHtml(authorColor)};"
                    >
                        — ${escapeHtml(data.author)}
                    </cite>

                </div>

            `;


            wrapper.appendChild(slide);


            const dot =
                document.createElement('span');


            dot.className =
                `dot ${
                    index === 0
                        ? 'active-dot'
                        : ''
                }`;


            dotsContainer.appendChild(dot);


            index++;

        });


        container.style.display =
            'block';


        quoteSlides =
            document.querySelectorAll(
                '#quote-slides-wrapper .quote-slide'
            );


        const quoteDots =
            dotsContainer.querySelectorAll(
                '.dot'
            );


        if (quoteSlides.length > 1) {

            if (quoteInterval) {

                clearInterval(
                    quoteInterval
                );

            }


            quoteInterval =
                setInterval(() => {

                    quoteSlides[
                        currentQuoteIndex
                    ].classList.remove(
                        'active'
                    );


                    if (
                        quoteDots[
                            currentQuoteIndex
                        ]
                    ) {

                        quoteDots[
                            currentQuoteIndex
                        ].classList.remove(
                            'active-dot'
                        );

                    }


                    currentQuoteIndex =
                        (
                            currentQuoteIndex + 1
                        ) %
                        quoteSlides.length;


                    quoteSlides[
                        currentQuoteIndex
                    ].classList.add(
                        'active'
                    );


                    if (
                        quoteDots[
                            currentQuoteIndex
                        ]
                    ) {

                        quoteDots[
                            currentQuoteIndex
                        ].classList.add(
                            'active-dot'
                        );

                    }

                }, 5000);

        }

    } catch (e) {

        console.error(
            "Error loading quotes slider:",
            e
        );

    }

}


// ============================================================
// CUSTOMER CARE FORM
// ============================================================

const contactForm =
    document.getElementById(
        'contact-form'
    );


const submitBtn =
    document.getElementById(
        'submit-btn'
    );


if (contactForm) {

    contactForm.addEventListener(
        'submit',
        async (e) => {

            e.preventDefault();


            submitBtn.disabled = true;

            submitBtn.innerText =
                "Sending...";


            const formData =
                new FormData(
                    contactForm
                );


            try {

                const response =
                    await fetch(
                        "https://formspree.io/f/xojywaeg",
                        {
                            method: "POST",

                            body: formData,

                            headers: {
                                "Accept":
                                    "application/json"
                            }
                        }
                    );


                if (response.ok) {

                    Toastify({

                        text:
                            "Inquiry sent successfully!",

                        duration: 4000,

                        gravity: "top",

                        position: "right",

                        style: {
                            background:
                                "#2E8B57"
                        }

                    }).showToast();


                    contactForm.reset();

                } else {

                    throw new Error(
                        "Form submission failed"
                    );

                }

            } catch (error) {

                console.error(
                    "Customer care form error:",
                    error
                );


                Toastify({

                    text:
                        "Failed to send. Try again.",

                    duration: 4000,

                    gravity: "top",

                    position: "right",

                    style: {
                        background:
                            "#b22222"
                    }

                }).showToast();

            } finally {

                submitBtn.disabled = false;

                submitBtn.innerText =
                    "Submit Inquiry";

            }

        }
    );

}


// ============================================================
// MAIN PROMOTIONAL SLIDER
// ============================================================

const slides =
    document.querySelectorAll(
        '.slider-slide'
    );


const dots =
    document.querySelectorAll(
        '.slider-container > .slider-dots .dot'
    );


let currentSlide = 0;


function showSlide(index) {

    slides.forEach(
        s =>
            s.classList.remove(
                'active'
            )
    );


    dots.forEach(
        d =>
            d.classList.remove(
                'active-dot'
            )
    );


    if (slides[index]) {

        slides[index].classList.add(
            'active'
        );

    }


    if (dots[index]) {

        dots[index].classList.add(
            'active-dot'
        );

    }

}


if (slides.length > 1) {

    setInterval(() => {

        currentSlide++;


        if (
            currentSlide >=
            slides.length
        ) {

            currentSlide = 0;

        }


        showSlide(
            currentSlide
        );

    }, 5000);

}


// ============================================================
// LOAD DYNAMIC HOMEPAGE CONTENT
// ============================================================

loadAnnouncement();

loadMotivationalQuote();


// ============================================================
// TESTIMONIAL SLIDER
// ============================================================

const tSlides =
    document.querySelectorAll(
        '.testimonial-slide'
    );


let currentTSlide = 0;


function showNextTestimonial() {

    if (tSlides.length < 2) {
        return;
    }


    const oldSlide =
        tSlides[currentTSlide];


    oldSlide.classList.remove(
        'active'
    );


    oldSlide.classList.add(
        'exit'
    );


    currentTSlide =
        (
            currentTSlide + 1
        ) %
        tSlides.length;


    const nextSlide =
        tSlides[currentTSlide];


    nextSlide.classList.remove(
        'exit'
    );


    nextSlide.classList.add(
        'active'
    );


    setTimeout(() => {

        if (
            !oldSlide.classList.contains(
                'active'
            )
        ) {

            oldSlide.classList.remove(
                'exit'
            );

        }

    }, 600);

}


if (tSlides.length > 1) {

    setInterval(
        showNextTestimonial,
        6000
    );

}


// ============================================================
// SUPPORT TOOLTIP
// ============================================================

window.addEventListener(
    'load',
    () => {

        setTimeout(() => {

            const tooltip =
                document.getElementById(
                    'support-tooltip'
                );


            if (tooltip) {

                tooltip.style.transition =
                    'opacity 0.5s ease';


                tooltip.style.opacity =
                    '0';


                setTimeout(() => {

                    tooltip.style.display =
                        'none';

                }, 500);

            }

        }, 5000);

    }
);

</script>


<!-- ============================================================
     SHARED FOOTER
     ============================================================ -->

<?php
require __DIR__ . '/includes/footer.php';
?>

</body>
</html>
