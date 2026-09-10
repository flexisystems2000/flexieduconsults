<?php
$firebaseProjectId = "waec2026jamb2027";
$apiUrl = "https://firestore.googleapis.com/v1/projects/{$firebaseProjectId}/databases/(default)/documents/news";

$perPage = 10;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

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
                $parsedTimestamp = strtotime($fields['timestamp']['timestampValue']);

                if ($parsedTimestamp !== false) {
                    $timestamp = $parsedTimestamp;
                }
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

usort($allNews, function ($a, $b) {
    return $b['timestamp'] <=> $a['timestamp'];
});

$totalItems = count($allNews);
$totalPages = max(1, (int)ceil($totalItems / $perPage));

$currentPage = min($currentPage, $totalPages);

$offset = ($currentPage - 1) * $perPage;
$newsForPage = array_slice($allNews, $offset, $perPage);

$serverNewsHtml = '';

if (empty($newsForPage)) {
    $serverNewsHtml = '
        <tr>
            <td colspan="3" style="padding:15px;color:#555;font-size:14px;">
                No news updates available at the moment.
            </td>
        </tr>
    ';
} else {
    foreach ($newsForPage as $item) {
        $targetUrl = !empty($item['slug'])
            ? "/news/" . rawurlencode($item['slug'])
            : "/news/id/" . rawurlencode($item['id']);

        $safeTargetUrl = htmlspecialchars($targetUrl, ENT_QUOTES, 'UTF-8');
        $safeImageUrl = htmlspecialchars($item['imageUrl'], ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');

        $serverNewsHtml .= '
            <tr
                onclick="window.location.href=\'' . $safeTargetUrl . '\'"
                tabindex="0"
                role="link"
                onkeydown="if(event.key===\'Enter\'||event.key===\' \'){event.preventDefault();window.location.href=\'' . $safeTargetUrl . '\';}"
                aria-label="Read: ' . $safeTitle . '"
            >
                <td style="width:88px;">
                    <img
                        src="' . $safeImageUrl . '"
                        alt="' . $safeTitle . '"
                        class="td-img"
                        loading="lazy"
                    >
                </td>

                <td class="td-title">
                    ' . $safeTitle . '
                </td>

                <td class="td-arrow" aria-hidden="true">
                    ❯
                </td>
            </tr>
        ';
    }
}

function buildPagination($currentPage, $totalPages)
{
    if ($totalPages <= 1) {
        return '';
    }

    $html = '<div class="pagination-bar" id="pagination-controls" aria-label="News pagination">';

    if ($currentPage > 1) {
        $previousPage = $currentPage - 1;

        $html .= '
            <a
                href="?page=' . $previousPage . '"
                class="pg-btn"
                aria-label="Previous page"
            >‹</a>
        ';
    } else {
        $html .= '
            <span
                class="pg-btn pg-disabled"
                aria-hidden="true"
            >‹</span>
        ';
    }

    $range = 2;

    $start = max(1, $currentPage - $range);
    $end = min($totalPages, $currentPage + $range);

    if ($start > 1) {
        $html .= '
            <a href="?page=1" class="pg-btn" aria-label="Page 1">1</a>
        ';

        if ($start > 2) {
            $html .= '
                <span class="pg-btn pg-ellipsis" aria-hidden="true">…</span>
            ';
        }
    }

    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            $html .= '
                <span
                    class="pg-btn pg-active"
                    aria-current="page"
                >' . $i . '</span>
            ';
        } else {
            $html .= '
                <a
                    href="?page=' . $i . '"
                    class="pg-btn"
                    aria-label="Page ' . $i . '"
                >' . $i . '</a>
            ';
        }
    }

    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '
                <span class="pg-btn pg-ellipsis" aria-hidden="true">…</span>
            ';
        }

        $html .= '
            <a
                href="?page=' . $totalPages . '"
                class="pg-btn"
                aria-label="Page ' . $totalPages . '"
            >' . $totalPages . '</a>
        ';
    }

    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;

        $html .= '
            <a
                href="?page=' . $nextPage . '"
                class="pg-btn"
                aria-label="Next page"
            >›</a>
        ';
    } else {
        $html .= '
            <span
                class="pg-btn pg-disabled"
                aria-hidden="true"
            >›</span>
        ';
    }

    $html .= '</div>';

    return $html;
}

$paginationHtml = buildPagination($currentPage, $totalPages);

/*
|--------------------------------------------------------------------------
| Shared Flexi Head Configuration
|--------------------------------------------------------------------------
*/

$pageNumber = $currentPage;

$pageTitle = 'Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria';

if ($pageNumber > 1) {
    $pageTitle .= ' - Page ' . $pageNumber;
}

$pageDescription = 'Flexi Educational Consult (Flexi Tutors) is an online CBT exam practice portal for JAMB, WAEC, NECO, and JUPEB.';

$pageKeywords = 'flexieduconsult, Flexi Educational Consult, Flexi Tutors, JAMB, WAEC, NECO, Flexi, CBT, jamb tutorials, waec tutorials, ssce tutorials, tutorials in shomolu, tutorials in bariga';

$pageImage = 'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';

$pageCanonical = 'https://www.flexieduconsult.com.ng/';

if ($pageNumber > 1) {
    $pageCanonical .= '?page=' . $pageNumber;
}

$includeToastify = true;
$includeAdsense = true;

/*
|--------------------------------------------------------------------------
| Shared Head
|--------------------------------------------------------------------------
*/

include __DIR__ . '/includes/head.php';

/*
|--------------------------------------------------------------------------
| Homepage Structured Data
|--------------------------------------------------------------------------
*/

$organizationSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'EducationalOrganization',
    'name' => 'Flexi Educational Consult',
    'alternateName' => 'Flexi Tutors',
    'url' => 'https://www.flexieduconsult.com.ng',
    'logo' => $pageImage,
    'image' => $pageImage,
    'description' => 'Flexi Educational Consult provides JAMB, WAEC, NECO and JUPEB preparation, CBT practice, academic tutoring, past questions and educational support in Nigeria.',
    'address' => [
        '@type' => 'PostalAddress',
        'addressCountry' => 'NG'
    ],
    'areaServed' => [
        '@type' => 'Country',
        'name' => 'Nigeria'
    ],
    'sameAs' => [
        'https://www.facebook.com/share/1HgB8GWwZ1/',
        'https://www.linkedin.com/company/flexi-educational-consult'
    ],
    'knowsAbout' => [
        'UTME',
        'JAMB',
        'WAEC',
        'NECO',
        'CBT',
        'Academic Tutoring'
    ]
];
?>

<script type="application/ld+json">
<?= json_encode(
    $organizationSchema,
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
); ?>
</script>

<?php
/*
|--------------------------------------------------------------------------
| Shared Header
|--------------------------------------------------------------------------
|
| The shared header expects $currentPage to identify the active page.
| The numeric pagination value is no longer needed after this point.
|
*/

$currentPage = 'index.php';

include __DIR__ . '/includes/header.php';
?>

<main>
    <div class="container">

        <h1 class="sr-only">
            Flexi Educational Consult - JAMB, WAEC, NECO and CBT Preparation
        </h1>

        <!-- Welcome Banner -->
        <section id="welcome-banner" aria-live="polite">
            <div class="welcome-content">
                <h2 id="welcome-heading">
                    Welcome to Flexi Tutors
                </h2>

                <p id="welcome-message">
                    Your trusted platform for JAMB, WAEC, NECO and CBT preparation.
                </p>
            </div>
        </section>

        <!-- Announcement Banner -->
        <section
            id="announcement-banner"
            aria-live="polite"
            aria-label="Latest announcement"
        >
            <div id="announcement-content">
                Loading latest announcement...
            </div>
        </section>

        <!-- Motivational Quote Slider -->
        <section
            class="quote-slider-container"
            aria-label="Motivational quotes"
        >
            <div
                id="quote-slider"
                class="quote-slider"
                aria-live="polite"
            >
                <div class="quote-slide quote-loading">
                    Loading motivational quote...
                </div>
            </div>

            <div
                id="quote-dots"
                class="quote-dots"
                aria-label="Quote navigation"
            ></div>
        </section>

        <!-- Image Slider -->
        <section
            class="slider-container"
            aria-label="Flexi Educational Consult highlights"
        >
            <div class="slider-slide active">
                <img
                    src="https://i.postimg.cc/XvkqQc3F/20260418-185555-2.jpg"
                    alt="Flexi Educational Consult educational programme"
                    loading="eager"
                >
            </div>

            <div class="slider-slide">
                <img
                    src="https://i.postimg.cc/pXBjLFpj/20260418-190953-2.jpg"
                    alt="Flexi Tutors JAMB and academic preparation"
                    loading="lazy"
                >
            </div>

            <div class="slider-slide">
                <img
                    src="https://i.postimg.cc/76p3Srkc/Screenshot-20260418-191139-2.png"
                    alt="Flexi Educational Consult learning resources"
                    loading="lazy"
                >
            </div>

            <div
                class="slider-dots"
                aria-label="Image slider navigation"
            >
                <button
                    type="button"
                    class="dot active-dot"
                    aria-label="Show slide 1"
                    aria-current="true"
                ></button>

                <button
                    type="button"
                    class="dot"
                    aria-label="Show slide 2"
                    aria-current="false"
                ></button>

                <button
                    type="button"
                    class="dot"
                    aria-label="Show slide 3"
                    aria-current="false"
                ></button>
            </div>
        </section>

        <!-- News Updates -->
        <section class="news-section" aria-labelledby="news-heading">
            <h2 id="news-heading" class="section-heading">
                Latest News & Updates
            </h2>

            <div class="news-table-wrapper">
                <table class="news-table">
                    <thead>
                        <tr class="sr-only">
                            <th scope="col">Image</th>
                            <th scope="col">News title</th>
                            <th scope="col">Open</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?= $serverNewsHtml ?>
                    </tbody>
                </table>
            </div>

            <?= $paginationHtml ?>
        </section>

        <!-- Testimonials -->
        <section
            class="testimonials-section"
            aria-labelledby="testimonials-heading"
        >
            <h2 id="testimonials-heading" class="section-heading">
                What Our Students Say
            </h2>

            <div class="testimonials-grid">

                <article class="testimonial-card">
                    <div class="testimonial-rating" aria-label="5 out of 5 stars">
                        ★★★★★
                    </div>

                    <p class="testimonial-text">
                        "The CBT practice really helped me become comfortable
                        with computer-based examinations. I improved my speed
                        and confidence before JAMB."
                    </p>

                    <div class="testimonial-author">
                        <strong>Chidi O.</strong>
                        <span>JAMB Candidate — Score: 312</span>
                    </div>
                </article>

                <article class="testimonial-card">
                    <div class="testimonial-rating" aria-label="5 out of 5 stars">
                        ★★★★★
                    </div>

                    <p class="testimonial-text">
                        "The PDFs and classroom resources made revision much
                        easier. I was able to study important topics without
                        wasting time searching everywhere."
                    </p>

                    <div class="testimonial-author">
                        <strong>Amina A.</strong>
                        <span>WAEC Student — 5 A1s</span>
                    </div>
                </article>

                <article class="testimonial-card">
                    <div class="testimonial-rating" aria-label="5 out of 5 stars">
                        ★★★★★
                    </div>

                    <p class="testimonial-text">
                        "The admission updates helped me stay informed about
                        important opportunities and deadlines after my
                        examination."
                    </p>

                    <div class="testimonial-author">
                        <strong>Tunde E.</strong>
                        <span>Post-UTME Student</span>
                    </div>
                </article>

            </div>
        </section>

        <!-- Customer Care -->
        <section
            class="customer-care-section"
            aria-labelledby="customer-care-heading"
        >
            <div class="card">
                <h2 id="customer-care-heading">
                    Contact Customer Care
                </h2>

                <p>
                    Have a question, suggestion or issue? Send us a message
                    and our support team will get back to you.
                </p>

                <form
                    id="contact-form"
                    action="https://formspree.io/f/xojywaeg"
                    method="POST"
                >
                    <label for="contact-email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="contact-email"
                        name="email"
                        placeholder="Enter your email address"
                        autocomplete="email"
                        required
                    >

                    <label for="contact-message">
                        Message
                    </label>

                    <textarea
                        id="contact-message"
                        name="message"
                        rows="5"
                        placeholder="How can we help you?"
                        required
                    ></textarea>

                    <button
                        type="submit"
                        class="btn-green"
                        id="contact-submit"
                    >
                        Send Message
                    </button>
                </form>
            </div>
        </section>

    </div>
</main>

<?php
/*
|--------------------------------------------------------------------------
| Shared Footer
|--------------------------------------------------------------------------
*/

include __DIR__ . '/includes/footer.php';
?>

<!-- Jarvis AI Support Widget -->
<div
    id="support-widget"
    class="support-widget"
    aria-label="Jarvis AI Support"
>
    <div
        id="support-tooltip"
        class="support-tooltip"
    >
        Chat with Jarvis AI for support
    </div>

    <a
        href="/contactsupport.html"
        class="support-button"
        aria-label="Chat with Jarvis AI for support"
    >
        <svg
            width="28"
            height="28"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >
            <path
                d="M20 11.5C20 15.6421 16.1944 19 11.5 19C10.4477 19 9.44155 18.8336 8.52094 18.5285L4 20L5.4407 16.091C4.52996 14.8407 4 13.2358 4 11.5C4 7.35786 7.80558 4 12.5 4C17.1944 4 20 7.35786 20 11.5Z"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
            <path
                d="M8 11.5H8.01M12 11.5H12.01M16 11.5H16.01"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
            />
        </svg>
    </a>
</div>

<style>
/*
|--------------------------------------------------------------------------
| Homepage-specific styles
|--------------------------------------------------------------------------
|
| Shared header/footer styles live in:
| /assets/css/flexi-brand.css
|
| Do not add header/footer styles here.
|
*/

:root {
    --blue: var(--flexi-primary, #003366);
    --green: var(--flexi-secondary, #2E8B57);
    --yellow: var(--flexi-accent, #FFD700);
    --bg: #f4f7f6;
    --radius: 12px;
    --shadow: 0 4px 18px rgba(0, 0, 0, .06);
}

* {
    box-sizing: border-box;
    -webkit-user-select: none;
    user-select: none;
}

input,
textarea {
    -webkit-user-select: text;
    user-select: text;
}

body {
    margin: 0;
    background: var(--bg);
    color: #222;
    font-family: Arial, Helvetica, sans-serif;
}

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

/* Main container */

.container {
    width: min(1100px, calc(100% - 30px));
    margin: 0 auto;
    padding: 20px 0 40px;
}

/* Welcome banner */

#welcome-banner {
    display: none;
    margin: 10px 0 18px;
    padding: 18px 20px;
    border-radius: var(--radius);
    background: linear-gradient(
        135deg,
        rgba(0, 51, 102, .96),
        rgba(46, 139, 87, .96)
    );
    color: #fff;
    box-shadow: var(--shadow);
    animation: fadeIn .35s ease;
}

.welcome-content h2 {
    margin: 0 0 6px;
    font-size: 22px;
}

.welcome-content p {
    margin: 0;
    line-height: 1.6;
    font-size: 14px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Announcement */

#announcement-banner {
    margin: 0 0 20px;
    padding: 14px 16px;
    border-left: 4px solid var(--yellow);
    border-radius: 10px;
    background: #fff;
    box-shadow: var(--shadow);
}

#announcement-content {
    color: #333;
    line-height: 1.6;
    font-size: 14px;
}

/* Motivational quote slider */

.quote-slider-container {
    position: relative;
    margin: 0 0 22px;
    overflow: hidden;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.quote-slider {
    min-height: 150px;
    position: relative;
}

.quote-slide {
    min-height: 150px;
    padding: 35px 25px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    background-size: cover;
    background-position: center;
    color: #fff;
    position: relative;
}

.quote-slide::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, .48);
}

.quote-slide > * {
    position: relative;
    z-index: 1;
}

.quote-loading {
    background: linear-gradient(
        135deg,
        var(--blue),
        var(--green)
    );
    font-size: 16px;
}

.quote-text {
    max-width: 800px;
    margin: 0;
    font-size: 21px;
    line-height: 1.5;
    font-weight: 700;
}

.quote-author {
    margin-top: 12px;
    font-size: 14px;
    opacity: .95;
}

.quote-dots {
    position: absolute;
    bottom: 12px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 7px;
    z-index: 5;
}

.quote-dot {
    width: 8px;
    height: 8px;
    border: 0;
    border-radius: 50%;
    padding: 0;
    background: rgba(255, 255, 255, .5);
    cursor: pointer;
}

.quote-dot.active {
    background: #fff;
    transform: scale(1.2);
}

/* Image slider */

.slider-container {
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
    border-radius: var(--radius);
    background: #fff;
    box-shadow: var(--shadow);
}

.slider-slide {
    display: none;
    width: 100%;
}

.slider-slide.active {
    display: block;
}

.slider-slide img {
    display: block;
    width: 100%;
    height: 330px;
    object-fit: cover;
}

.slider-dots {
    position: absolute;
    bottom: 12px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 8px;
    z-index: 3;
}

.dot {
    width: 9px;
    height: 9px;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, .65);
    cursor: pointer;
}

.dot.active-dot {
    background: #fff;
    transform: scale(1.2);
}

/* Section headings */

.section-heading {
    margin: 28px 0 14px;
    color: var(--blue);
    font-size: 22px;
    line-height: 1.3;
}

/* News table */

.news-table-wrapper {
    width: 100%;
    overflow-x: auto;
    border-radius: var(--radius);
    background: #fff;
    box-shadow: var(--shadow);
}

.news-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.news-table tbody tr {
    cursor: pointer;
    transition:
        background .2s ease,
        transform .2s ease;
}

.news-table tbody tr:hover {
    background: #f7faf8;
}

.news-table tbody tr:focus {
    outline: 2px solid var(--green);
    outline-offset: -2px;
}

.news-table td {
    padding: 12px;
    border-bottom: 1px solid #edf0ee;
    vertical-align: middle;
}

.news-table tbody tr:last-child td {
    border-bottom: 0;
}

.td-img {
    display: block;
    width: 88px;
    height: 66px;
    object-fit: cover;
    border-radius: 8px;
    background: #eee;
}

.td-title {
    color: #222;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.45;
}

.td-arrow {
    width: 45px;
    color: var(--green);
    text-align: center;
    font-size: 20px;
}

/* Pagination */

.pagination-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin: 18px 0 30px;
}

.pg-btn {
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #dce4df;
    border-radius: 8px;
    background: #fff;
    color: var(--blue);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.pg-btn:hover {
    background: #f1f7f4;
}

.pg-active {
    border-color: var(--green);
    background: var(--green);
    color: #fff;
}

.pg-disabled {
    opacity: .4;
    cursor: default;
}

.pg-ellipsis {
    cursor: default;
}

/* Cards */

.card {
    padding: 22px;
    margin-top: 24px;
    border-radius: var(--radius);
    background: #fff;
    box-shadow: var(--shadow);
}

.card h3,
.card h2 {
    margin-top: 0;
    color: var(--blue);
}

.card p {
    line-height: 1.65;
}

/* Buttons */

.btn-green {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-height: 44px;
    padding: 10px 18px;
    border: 0;
    border-radius: 8px;
    background: var(--green);
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .2s ease, transform .2s ease;
}

.btn-green:hover {
    opacity: .9;
}

.btn-green:active {
    transform: translateY(1px);
}

.btn-green:disabled {
    opacity: .55;
    cursor: not-allowed;
}

/* Customer care form */

#contact-form {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

#contact-form label {
    margin-top: 5px;
    color: #333;
    font-size: 14px;
    font-weight: 600;
}

#contact-form input,
#contact-form textarea {
    width: 100%;
    padding: 12px 13px;
    border: 1px solid #d7dfdb;
    border-radius: 8px;
    background: #fff;
    color: #222;
    font: inherit;
    outline: none;
}

#contact-form input:focus,
#contact-form textarea:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 3px rgba(46, 139, 87, .1);
}

#contact-form textarea {
    resize: vertical;
}

/* Testimonials */

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

.testimonial-card {
    padding: 20px;
    border-radius: var(--radius);
    background: #fff;
    box-shadow: var(--shadow);
}

.testimonial-rating {
    margin-bottom: 10px;
    color: #e5b800;
    letter-spacing: 2px;
}

.testimonial-text {
    margin: 0;
    color: #444;
    font-size: 14px;
    line-height: 1.65;
}

.testimonial-author {
    display: flex;
    flex-direction: column;
    gap: 3px;
    margin-top: 15px;
}

.testimonial-author strong {
    color: var(--blue);
    font-size: 14px;
}

.testimonial-author span {
    color: #666;
    font-size: 12px;
}

/* Support widget */

.support-widget {
    position: fixed;
    right: 18px;
    bottom: 18px;
    z-index: 9998;
}

.support-button {
    width: 56px;
    height: 56px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background: var(--green);
    color: #fff;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(0, 0, 0, .2);
    transition: transform .2s ease, opacity .2s ease;
}

.support-button:hover {
    transform: translateY(-2px);
    opacity: .95;
}

.support-tooltip {
    position: absolute;
    right: 0;
    bottom: 68px;
    width: max-content;
    max-width: 240px;
    padding: 9px 12px;
    border-radius: 8px;
    background: #222;
    color: #fff;
    font-size: 12px;
    line-height: 1.4;
    box-shadow: var(--shadow);
    white-space: nowrap;
}

/* Responsive */

@media (max-width: 850px) {
    .testimonials-grid {
        grid-template-columns: 1fr;
    }

    .slider-slide img {
        height: 280px;
    }
}

@media (max-width: 600px) {
    .container {
        width: min(100% - 20px, 1100px);
        padding-top: 12px;
    }

    .welcome-content h2 {
        font-size: 19px;
    }

    .welcome-content p {
        font-size: 13px;
    }

    .section-heading {
        font-size: 19px;
    }

    .slider-slide img {
        height: 210px;
    }

    .quote-slide,
    .quote-loading {
        min-height: 145px;
        padding: 28px 18px;
    }

    .quote-text {
        font-size: 17px;
    }

    .td-img {
        width: 72px;
        height: 55px;
    }

    .news-table td {
        padding: 9px;
    }

    .td-title {
        font-size: 13px;
    }

    .td-arrow {
        width: 34px;
        font-size: 17px;
    }

    .card {
        padding: 17px;
    }

    .support-widget {
        right: 13px;
        bottom: 13px;
    }

    .support-button {
        width: 52px;
        height: 52px;
    }

    .support-tooltip {
        bottom: 63px;
        max-width: 210px;
        font-size: 11px;
    }
}

@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        scroll-behavior: auto !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
/*
|--------------------------------------------------------------------------
| Homepage JavaScript
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Disable copy / cut / paste
    |--------------------------------------------------------------------------
    */

    document.addEventListener('copy', function (event) {
        event.preventDefault();
    });

    document.addEventListener('cut', function (event) {
        event.preventDefault();
    });

    document.addEventListener('paste', function (event) {
        /*
         * Keep paste enabled inside form controls.
         */
        var target = event.target;

        if (
            target &&
            (
                target.tagName === 'INPUT' ||
                target.tagName === 'TEXTAREA'
            )
        ) {
            return;
        }

        event.preventDefault();
    });

    /*
    |--------------------------------------------------------------------------
    | Firebase
    |--------------------------------------------------------------------------
    */

    var firebaseConfig = {
        apiKey: "AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",
        authDomain: "auth.flexieduconsult.com.ng",
        projectId: "waec2026jamb2027"
    };

    var firebaseApp;
    var db;
    var auth;

    try {
        firebaseApp = firebase.initializeApp(firebaseConfig);
        db = firebase.getFirestore(firebaseApp);
        auth = firebase.getAuth(firebaseApp);
    } catch (error) {
        console.error('Firebase initialization error:', error);
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication / Welcome Banner
    |--------------------------------------------------------------------------
    */

    if (auth) {
        firebase.onAuthStateChanged(auth, function (user) {

            var welcomeBanner = document.getElementById('welcome-banner');
            var welcomeHeading = document.getElementById('welcome-heading');
            var welcomeMessage = document.getElementById('welcome-message');
            var authButton = document.getElementById('flexiAuthBtn');
            var contactEmail = document.getElementById('contact-email');

            if (user) {

                var displayName = user.displayName;

                if (!displayName && user.email) {
                    displayName = user.email.split('@')[0];
                }

                if (!displayName) {
                    displayName = 'Student';
                }

                if (welcomeBanner) {
                    welcomeBanner.style.display = 'block';
                }

                if (welcomeHeading) {
                    welcomeHeading.textContent = 'Welcome back, ' + displayName + '!';
                }

                if (welcomeMessage) {
                    welcomeMessage.textContent =
                        'We are glad to have you back. Continue learning, practising and preparing for your examination.';
                }

                if (contactEmail && user.email) {
                    contactEmail.value = user.email;
                }

                if (authButton) {
                    authButton.textContent = 'Logout';

                    authButton.href = '#';

                    authButton.onclick = function (event) {
                        event.preventDefault();

                        firebase.signOut(auth)
                            .then(function () {
                                window.location.reload();
                            })
                            .catch(function (error) {
                                console.error('Logout error:', error);
                            });
                    };
                }

            } else {

                if (welcomeBanner) {
                    welcomeBanner.style.display = 'none';
                }

                if (authButton) {
                    authButton.textContent = 'Login';
                    authButton.href = '/login.html';
                    authButton.onclick = null;
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Latest Announcement
    |--------------------------------------------------------------------------
    */

    async function loadAnnouncement() {

        var announcementContent =
            document.getElementById('announcement-content');

        if (!announcementContent || !db) {
            return;
        }

        try {

            var announcementsRef =
                firebase.collection(db, 'announcements');

            var announcementQuery =
                firebase.query(
                    announcementsRef,
                    firebase.orderBy('createdAt', 'desc'),
                    firebase.limit(1)
                );

            var snapshot =
                await firebase.getDocs(announcementQuery);

            if (snapshot.empty) {
                announcementContent.textContent =
                    'No new announcements at the moment.';
                return;
            }

            var announcement = snapshot.docs[0].data();

            var title =
                announcement.title || 'Announcement';

            var content =
                announcement.content || '';

            var dateText = '';

            if (announcement.createdAt) {
                try {
                    var date;

                    if (typeof announcement.createdAt.toDate === 'function') {
                        date = announcement.createdAt.toDate();
                    } else if (
                        announcement.createdAt.seconds !== undefined
                    ) {
                        date = new Date(
                            announcement.createdAt.seconds * 1000
                        );
                    }

                    if (date && !isNaN(date.getTime())) {
                        dateText = date.toLocaleDateString(
                            'en-NG',
                            {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            }
                        );
                    }
                } catch (dateError) {
                    console.warn(
                        'Announcement date formatting error:',
                        dateError
                    );
                }
            }

            announcementContent.innerHTML = '';

            var titleElement = document.createElement('strong');
            titleElement.textContent = title;

            var contentElement = document.createElement('div');
            contentElement.textContent = content;

            announcementContent.appendChild(titleElement);

            if (content) {
                announcementContent.appendChild(contentElement);
            }

            if (dateText) {
                var dateElement = document.createElement('small');
                dateElement.textContent = dateText;
                dateElement.style.display = 'block';
                dateElement.style.marginTop = '6px';
                dateElement.style.opacity = '.7';

                announcementContent.appendChild(dateElement);
            }

        } catch (error) {

            console.error(
                'Error loading announcement:',
                error
            );

            announcementContent.textContent =
                'Latest announcements will appear here.';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Motivational Quote Slider
    |--------------------------------------------------------------------------
    */

    var quoteInterval = null;
    var quoteIndex = 0;

    function escapeHtml(value) {

        var div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);

        return div.innerHTML;
    }

    async function loadMotivationalQuote() {

        var quoteSlider =
            document.getElementById('quote-slider');

        var quoteDots =
            document.getElementById('quote-dots');

        if (!quoteSlider || !quoteDots || !db) {
            return;
        }

        try {

            var quotesRef =
                firebase.collection(db, 'quotes');

            var quotesQuery =
                firebase.query(
                    quotesRef,
                    firebase.orderBy('createdAt', 'desc')
                );

            var snapshot =
                await firebase.getDocs(quotesQuery);

            if (snapshot.empty) {
                quoteSlider.innerHTML = `
                    <div class="quote-slide quote-loading">
                        Keep learning. Keep practising. Keep moving forward.
                    </div>
                `;

                quoteDots.innerHTML = '';

                return;
            }

            var quotes = [];

            snapshot.forEach(function (doc) {

                var data = doc.data();

                quotes.push({
                    quote: data.quote || data.text || '',
                    author: data.author || '',
                    imageUrl: data.imageUrl || '',
                    backgroundColor:
                        data.backgroundColor || ''
                });
            });

            if (!quotes.length) {
                return;
            }

            quoteIndex = 0;

            quoteSlider.innerHTML = '';
            quoteDots.innerHTML = '';

            quotes.forEach(function (item, index) {

                var slide =
                    document.createElement('div');

                slide.className = 'quote-slide';

                slide.style.display =
                    index === 0 ? 'flex' : 'none';

                if (item.imageUrl) {
                    slide.style.backgroundImage =
                        'url("' +
                        item.imageUrl.replace(/"/g, '') +
                        '")';
                } else if (item.backgroundColor) {
                    slide.style.background =
                        item.backgroundColor;
                } else {
                    slide.style.background =
                        'linear-gradient(135deg, var(--blue), var(--green))';
                }

                var quoteText =
                    document.createElement('p');

                quoteText.className = 'quote-text';

                quoteText.textContent =
                    item.quote || 'Keep going. Your effort matters.';

                slide.appendChild(quoteText);

                if (item.author) {
                    var author =
                        document.createElement('div');

                    author.className = 'quote-author';

                    author.textContent =
                        '— ' + item.author;

                    slide.appendChild(author);
                }

                quoteSlider.appendChild(slide);

                var dot =
                    document.createElement('button');

                dot.type = 'button';
                dot.className =
                    'quote-dot' +
                    (index === 0 ? ' active' : '');

                dot.setAttribute(
                    'aria-label',
                    'Show quote ' + (index + 1)
                );

                dot.setAttribute(
                    'aria-current',
                    index === 0 ? 'true' : 'false'
                );

                dot.addEventListener(
                    'click',
                    function () {
                        showQuote(index);
                        restartQuoteInterval();
                    }
                );

                quoteDots.appendChild(dot);
            });

            function showQuote(index) {

                var slides =
                    quoteSlider.querySelectorAll('.quote-slide');

                var dots =
                    quoteDots.querySelectorAll('.quote-dot');

                if (!slides.length) {
                    return;
                }

                quoteIndex =
                    (index + slides.length) % slides.length;

                slides.forEach(function (slide, slideIndex) {
                    slide.style.display =
                        slideIndex === quoteIndex
                            ? 'flex'
                            : 'none';
                });

                dots.forEach(function (dot, dotIndex) {

                    var active =
                        dotIndex === quoteIndex;

                    dot.classList.toggle(
                        'active',
                        active
                    );

                    dot.setAttribute(
                        'aria-current',
                        active ? 'true' : 'false'
                    );
                });
            }

            function restartQuoteInterval() {

                if (quoteInterval) {
                    clearInterval(quoteInterval);
                }

                quoteInterval =
                    setInterval(function () {
                        showQuote(quoteIndex + 1);
                    }, 5000);
            }

            if (quotes.length > 1) {
                restartQuoteInterval();
            }

        } catch (error) {

            console.error(
                'Error loading motivational quotes:',
                error
            );

            quoteSlider.innerHTML = `
                <div class="quote-slide quote-loading">
                    Stay focused. Every step you take brings you closer to your goal.
                </div>
            `;

            quoteDots.innerHTML = '';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Care Form
    |--------------------------------------------------------------------------
    */

    var contactForm =
        document.getElementById('contact-form');

    if (contactForm) {

        contactForm.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();

                var submitButton =
                    document.getElementById('contact-submit');

                var formData =
                    new FormData(contactForm);

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent =
                        'Sending...';
                }

                try {

                    var response =
                        await fetch(
                            contactForm.action,
                            {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept':
                                        'application/json'
                                }
                            }
                        );

                    if (!response.ok) {
                        throw new Error(
                            'Unable to send message.'
                        );
                    }

                    contactForm.reset();

                    if (window.Toastify) {

                        Toastify({
                            text:
                                'Your message has been sent successfully.',
                            duration: 4000,
                            gravity: 'top',
                            position: 'center',
                            close: true
                        }).showToast();

                    } else {
                        alert(
                            'Your message has been sent successfully.'
                        );
                    }

                } catch (error) {

                    console.error(
                        'Contact form error:',
                        error
                    );

                    if (window.Toastify) {

                        Toastify({
                            text:
                                'Unable to send your message. Please try again.',
                            duration: 4000,
                            gravity: 'top',
                            position: 'center',
                            close: true
                        }).showToast();

                    } else {
                        alert(
                            'Unable to send your message. Please try again.'
                        );
                    }

                } finally {

                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent =
                            'Send Message';
                    }
                }
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Homepage Image Slider
    |--------------------------------------------------------------------------
    */

    var imageSlides =
        document.querySelectorAll('.slider-slide');

    var imageDots =
        document.querySelectorAll('.slider-container .dot');

    var imageIndex = 0;
    var imageInterval = null;

    function showImageSlide(index) {

        if (!imageSlides.length) {
            return;
        }

        imageIndex =
            (index + imageSlides.length) %
            imageSlides.length;

        imageSlides.forEach(function (slide, slideIndex) {

            slide.classList.toggle(
                'active',
                slideIndex === imageIndex
            );
        });

        imageDots.forEach(function (dot, dotIndex) {

            var active =
                dotIndex === imageIndex;

            dot.classList.toggle(
                'active-dot',
                active
            );

            dot.setAttribute(
                'aria-current',
                active ? 'true' : 'false'
            );
        });
    }

    function restartImageSlider() {

        if (imageInterval) {
            clearInterval(imageInterval);
        }

        imageInterval =
            setInterval(function () {
                showImageSlide(imageIndex + 1);
            }, 5000);
    }

    imageDots.forEach(function (dot, index) {

        dot.addEventListener(
            'click',
            function () {
                showImageSlide(index);
                restartImageSlider();
            }
        );
    });

    if (imageSlides.length > 1) {
        restartImageSlider();
    }

    /*
    |--------------------------------------------------------------------------
    | Initialise Homepage Data
    |--------------------------------------------------------------------------
    */

    loadAnnouncement();
    loadMotivationalQuote();

    /*
    |--------------------------------------------------------------------------
    | Support Tooltip
    |--------------------------------------------------------------------------
    */

    var supportTooltip =
        document.getElementById('support-tooltip');

    if (supportTooltip) {

        setTimeout(function () {

            supportTooltip.style.opacity = '0';
            supportTooltip.style.visibility = 'hidden';

        }, 5000);
    }
});
</script>
