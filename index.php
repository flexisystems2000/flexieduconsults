<?php
// Server-side fetch for Google AdSense crawlers
$firebaseProjectId = "waec2026jamb2027";
$apiUrl = "https://firestore.googleapis.com/v1/projects/{$firebaseProjectId}/databases/(default)/documents/news";
$serverNewsHtml = '';

$response = @file_get_contents($apiUrl);
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['documents'])) {
        $docs = $data['documents'];
        $count = 0;
        foreach ($docs as $doc) {
            if ($count >= 5) break;
            $fields = isset($doc['fields']) ? $doc['fields'] : [];
            $title = isset($fields['title']['stringValue']) ? $fields['title']['stringValue'] : 'News Update';
            $imageUrl = isset($fields['imageUrl']['stringValue']) ? $fields['imageUrl']['stringValue'] : 'https://via.placeholder.com/80';
            $slug = isset($fields['slug']['stringValue']) ? $fields['slug']['stringValue'] : '';
            $docNameParts = explode('/', $doc['name']);
            $docId = end($docNameParts);

            $targetUrl = $slug
                ? "/news/" . rawurlencode($slug)
                : "/news/id/" . rawurlencode($docId);

            $serverNewsHtml .= '<tr onclick="window.location.href=\'' . $targetUrl . '\'">';
            $serverNewsHtml .= '<td style="width:88px;"><img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($title) . '" class="td-img"></td>';
            $serverNewsHtml .= '<td class="td-title">' . htmlspecialchars($title) . '</td>';
            $serverNewsHtml .= '<td class="td-arrow">❯</td>';
            $serverNewsHtml .= '</tr>';
            $count++;
        }
    }
}
if (empty($serverNewsHtml)) {
    $serverNewsHtml = '<tr><td colspan="3" style="padding: 15px; color: #555; font-size: 14px;">Loading latest educational news updates, admission guidelines, and exam notices for JAMB and WAEC candidates.</td></tr>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9836330764964180"
         crossorigin="anonymous"></script>

    <link rel="icon" type="image/png" sizes="32x32" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="icon" type="image/png" sizes="16x16" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Flexi Educational Consult (Flexi Tutors) is an online CBT exam practice portal for JAMB, WAEC, NECO, and JUPEB.">
    <meta name="keywords" content="flexieduconsult, Flexi Educational Consult, Flexi Tutors, JAMB, WAEC, NECO, Flexi, CBT, jamb tutorials, waec tutorials, ssce tutorials, tutorials in shomolu, tutorials in bariga">

    <meta property="og:title" content="Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria">
    <meta property="og:description" content="Flexi Educational Consult (Flexi Tutors) is an online CBT exam practice portal for JAMB, WAEC, NECO, and JUPEB.">
    <meta property="og:image" content="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <meta property="og:url" content="https://flexieduconsult.com.ng/">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria">
    <meta name="twitter:description" content="Flexi Educational Consult (Flexi Tutors) is an online CBT exam practice portal for JAMB, WAEC, NECO, and JUPEB.">
    <meta name="twitter:image" content="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">

    <link rel="canonical" href="https://flexieduconsult.com.ng/" />

    <title>Flexi Tutors | JAMB, WAEC & CBT Prep Nigeria</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "@id": "https://flexieduconsult.com.ng/#organization",
      "name": "Flexi Educational Consult",
      "alternateName": "Flexi Tutors",
      "url": "https://flexieduconsult.com.ng",
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
    :root {
        --blue: #003366;
        --green: #2E8B57;
        --yellow: #FFD700;
        --bg: #f4f7f6;
        --radius: 12px;
        --shadow: 0 4px 18px rgba(0,0,0,0.06);
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        background: var(--bg);
        margin: 0;
        padding: 0;
        color: #333;
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

    /* Wider content on desktop — matches news-view */
    .container {
        max-width: 920px;
        margin: 24px auto;
        width: 94%;
    }

    #welcome-banner {
        margin-bottom: 16px;
        padding: 16px 18px;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border-left: 5px solid var(--blue);
        display: none;
        animation: fadeIn .5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

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

    .slider-slide.active { opacity: 1; }

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

    .active-dot { background: white; }

    .section-heading {
        color: var(--blue);
        border-left: 5px solid var(--green);
        padding-left: 12px;
        font-size: 1.25rem;
        margin: 28px 0 14px;
        font-weight: 700;
    }

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

    .news-table tr:last-child { border-bottom: none; }

    .news-table tr:hover { background: #f8faf9; }

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
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pg-active {
        background: var(--blue) !important;
        color: white !important;
        border-color: var(--blue);
    }

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

    input,
    textarea {
        width: 100%;
        padding: 12px 14px;
        margin-bottom: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
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

    .btn-green:hover { opacity: 0.92; }

    .menu-container { position: relative; }

    .menu-btn {
        width: 36px;
        height: 36px;
        cursor: pointer;
        background: none;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 4px;
    }

    .menu-btn span {
        width: 18px;
        height: 2px;
        background: white;
    }

    .square-menu {
        display: none;
        position: absolute;
        top: 48px;
        right: 0;
        width: 240px;
        background: var(--blue);
        border: 2px solid var(--green);
        border-radius: 10px;
        z-index: 2000;
        box-shadow: 0 12px 32px rgba(0,0,0,.35);
        overflow: hidden;
    }

    .square-menu a {
        display: block;
        padding: 14px 16px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        border-bottom: 1px solid rgba(255,255,255,.1);
        transition: background 0.15s;
    }

    .square-menu a:hover { background: rgba(255,255,255,0.08); }

    .square-menu a:last-child { border-bottom: none; }

    /* Testimonials */
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

    .testimonial-slide.active { transform: translateX(0); }
    .testimonial-slide.exit { transform: translateX(-100%); }

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

    /* Quotes */
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

    .quote-slide.active { opacity: 1; }

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

    /* Announcement */
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

    /* ========== DESKTOP ========== */
    @media (min-width: 768px) {
        .container {
            max-width: 980px;
            margin: 32px auto;
        }

        header {
            height: 56px;
            padding: 0 28px;
        }

        .brand-name { font-size: 16px; }

        .logo-img {
            height: 36px;
            width: 36px;
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

        .td-title { font-size: 16px; }

        .news-table td { padding: 16px 18px; }

        .td-img {
            width: 100px;
            height: 72px;
        }

        .testimonial-slider-container {
            height: 160px;
        }

        .testimonial-text { font-size: 15px; }

        .card {
            padding: 28px 32px;
        }
    }

    @media (min-width: 1100px) {
        .container {
            max-width: 1040px;
        }

        .slider-container {
            height: 260px;
        }
    }

    @media (max-width: 480px) {
        .testimonial-slider-container { height: 165px; }
        .slider-container { height: 120px; }
        .container { margin: 16px auto; }
        .card { padding: 18px 16px; }
        .news-table td { padding: 12px; }
        .td-img {
            width: 72px;
            height: 54px;
        }
        .td-title { font-size: 14px; }
    }

    /* Footer */
    .footer {
        background: linear-gradient(135deg, #011627 0%, #032038 100%);
        color: #e2e8f0;
        padding: 60px 20px 30px;
        margin-top: 50px;
        border-top: 4px solid var(--green);
        font-size: 14px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr 1.3fr 1fr;
        gap: 35px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer h4 {
        color: var(--yellow);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin: 0 0 16px 0;
        position: relative;
        padding-bottom: 6px;
    }

    .footer h4::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 24px;
        height: 2px;
        background: var(--yellow);
        opacity: 0.7;
        border-radius: 2px;
    }

    .footer-about {
        line-height: 1.7;
        color: #94a3b8;
        margin: 0;
    }

    .footer-links-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links-list li { margin-bottom: 10px; }

    .footer a {
        color: #cbd5e0;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .footer-links-list a:hover {
        color: var(--yellow);
        transform: translateX(4px);
        display: inline-block;
    }

    .whatsapp-channel-link {
        color: #25D366 !important;
        font-weight: 600;
        display: inline-block;
    }

    .whatsapp-channel-link:hover {
        opacity: 0.85;
        transform: translateX(4px);
    }

    .contact-group { margin-bottom: 16px; }

    .contact-group h5 {
        color: #ffffff;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin: 0 0 6px 0;
        opacity: 0.9;
    }

    .contact-group a {
        display: block;
        color: #94a3b8;
        font-size: 0.88rem;
        margin-bottom: 4px;
    }

    .contact-group a:hover { color: #ffffff; }

    .footer-bottom {
        max-width: 1200px;
        margin: 40px auto 0;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        text-align: center;
        font-size: 13px;
        color: #64748b;
    }

    @media (max-width: 900px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
    }

    @media (max-width: 550px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 28px;
        }
    }
</style>
</head>
<body>
  <header>
    <div class="header-left">
        <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" alt="Flexi Educational Consult Official Logo" class="logo-img">
        <span class="brand-name">Flexi Educational Consult</span>
    </div>

    <div class="menu-container">
        <button class="menu-btn" onclick="toggleMenu()" aria-label="Toggle Navigation Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="square-menu" id="squareMenu">
            <a href="index.php">Home</a>
            <a href="videos.html">Watch Video Lessons</a>
            <a href="syllabus.html">Access the JAMB and WAEC syllabus here</a>
            <a href="brochure.html">Access JAMB Brochure</a>
            <a href="cbt.html">CBT Simulator</a>
            <a href="groups.html">Classroom (Groups and chats)</a>
            <a href="purchase.html">Purchase Scratch Cards</a>
            <a href="pdf.html">Get your PDFs from here</a>
            <a href="location.html">Tutorial Centers Near You</a>
            <a href="profile.html">User Profile</a>
            <a href="#" id="auth-menu-btn">Login</a>
        </div>
    </div>
</header>

<div class="container">
    <h1 class="sr-only">Flexi Educational Consult - Online JAMB WAEC CBT Exam Practice Portal</h1>

    <div id="welcome-banner">
        <span id="user-greeting" style="font-weight:bold; color:var(--blue); font-size:1.1rem;"></span>
    </div>

    <div id="announcement-banner">
        <h4 id="ann-title" style="margin: 0 0 5px 0;"></h4>
        <p id="ann-content" style="margin: 0; font-size: 0.95rem;"></p>
        <small id="ann-date" style="display: block; margin-top: 8px; opacity: 0.8;"></small>
    </div>

    <div id="quote-slider-container" class="quote-slider-container" style="display: none;">
        <div id="quote-slides-wrapper" class="quote-slides-wrapper"></div>
        <div id="quote-dots" class="slider-dots"></div>
    </div>

    <div class="slider-container">
        <div class="slider-slide active">
            <img src="https://i.postimg.cc/XvkqQc3F/20260418-185555-2.jpg" alt="JAMB UTME CBT Online Exam Practice Banner">
        </div>
        <div class="slider-slide">
            <img src="https://i.postimg.cc/pXBjLFpj/20260418-190953-2.jpg" alt="WAEC NECO SSCE Preparation Tutorials Banner">
        </div>
        <div class="slider-slide">
            <img src="https://i.postimg.cc/76p3Srkc/Screenshot-20260418-191139-2.png" alt="Flexi Educational Consult Academic Registration Banner">
        </div>
        <div class="slider-dots">
            <span class="dot active-dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <h2 class="section-heading">News Updates</h2>

    <table class="news-table">
        <tbody id="news-table-body">
            <?php echo $serverNewsHtml; ?>
        </tbody>
    </table>

    <div class="pagination-bar" id="pagination-controls"></div>

    <div class="testimonials-section">
        <h2 class="testimonials-heading">What Our Students Say</h2>

        <div class="testimonial-slider-container">
            <div class="testimonial-slide active">
                <div class="student-info">
                    <div class="student-avatar">CO</div>
                    <div class="student-details">
                        <h4>Chidi O.</h4>
                        <span>JAMB Candidate (Score: 312)</span>
                    </div>
                </div>
                <p class="testimonial-text">
                    "The CBT Simulator on Flexi Tutors is the closest thing to the actual JAMB exam. It gave me the speed and accuracy I needed to score over 300!"
                </p>
            </div>

            <div class="testimonial-slide">
                <div class="student-info">
                    <div class="student-avatar">AA</div>
                    <div class="student-details">
                        <h4>Amina A.</h4>
                        <span>WAEC Student (5 A1s)</span>
                    </div>
                </div>
                <p class="testimonial-text">
                    "I downloaded all my past questions in PDF here. The online classroom groups kept me accountable during my final revisions. Thank you, Flexi!"
                </p>
            </div>

            <div class="testimonial-slide">
                <div class="student-info">
                    <div class="student-avatar">TE</div>
                    <div class="student-details">
                        <h4>Tunde E.</h4>
                        <span>Post-UTME Student</span>
                    </div>
                </div>
                <p class="testimonial-text">
                    "Highly recommended! The instant admission updates kept me from missing important post-UTME screening dates. Exceptional platform."
                </p>
            </div>
        </div>
    </div>

    <div class="card">
        <h3>Customer Care</h3>
        <form id="contact-form">
            <input type="email" name="email" id="contact-email" placeholder="Your Email Address" required>
            <textarea name="message" rows="3" placeholder="How can we help you?" required></textarea>
            <button type="submit" id="submit-btn" class="btn-green">Submit Inquiry</button>
        </form>
    </div>
</div>

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <p class="footer-about">
               We empower Nigerian students with admission updates, CBT preparation, tutorials, past questions in PDF, and premium educational support.
            </p>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links-list">
                <li><a href="index.php">Home</a></li>
                <li><a href="https://elearning.flexieduconsult.com.ng" target="_blank" rel="noopener">WhatsApp Masterclass (E-Learning)</a></li>
                <li><a href="syllabus.html">Access the JAMB/WAEC syllabus</a></li>
                <li><a href="brochure.html">Access JAMB Brochure</a></li>
                <li><a href="videos.html">Video Lessons</a></li>
                <li><a href="pdf.html">Past Questions & PDFs</a></li>
                <li><a href="cbt.html">CBT Simulator</a></li>
                <li><a href="groups.html">Classroom Groups and chats</a></li>
                <li><a href="location.html">Tutorial Centres</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Support & Community</h4>
            <div class="contact-group">
                <a href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u" target="_blank" class="whatsapp-channel-link">
                   Join our WhatsApp Channel
                </a>
            </div>
            <div class="contact-group">
                <h5>Contact Us</h5>
                <a href="tel:+2349034159839">(+234) 903 415 9839</a>
                <a href="tel:+2347033855206">(+234) 703 385 5206</a>
            </div>
            <div class="contact-group">
                <h5>Email Us</h5>
                <a href="mailto:support@flexieduconsult.com.ng">support@flexieduconsult.com.ng</a>
                <a href="mailto:info@flexieduconsult.com.ng">info@flexieduconsult.com.ng</a>
            </div>
        </div>

        <div class="footer-col social-links">
            <h4>Follow Us</h4>
            <ul class="footer-links-list">
                <li><a href="https://www.facebook.com/profile.php?id=61589793118693" target="_blank">Facebook @flexieduconsult</a></li>
                <li><a href="https://instagram.com/flexieduconsult2000" target="_blank">Instagram @flexieduconsult2000</a></li>
                <li><a href="https://www.tiktok.com/@flexieduconsult" target="_blank">TikTok @flexieduconsult</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        &copy; <span id="current-year"></span> Flexi Educational Consult. All Rights Reserved.
    </div>
</footer>

<div id="support-widget" style="position:fixed; bottom:20px; right:20px; z-index:9999; display:flex; flex-direction:column; align-items:flex-end; gap:10px;">
    <div id="support-tooltip" style="background:#003366; color:white; padding:10px 15px; border-radius:20px 20px 0px 20px; font-size:0.85rem; box-shadow:0 4px 10px rgba(0,0,0,0.2); animation: fadeIn 0.5s; opacity: 1;">
    Chat with Jarvis AI for support
    </div>
    <button id="support-btn" onclick="window.location.href='contactsupport.html'" aria-label="Support Chat" style="background:#2E8B57; border:none; width:60px; height:60px; border-radius:50%; cursor:pointer; box-shadow:0 4px 15px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; transition:transform 0.3s;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="white">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
        </svg>
    </button>
</div>

<style>
    #support-btn:hover { transform: scale(1.1); }
</style>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore, collection, getDocs, query, orderBy, limit } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

const firebaseConfig = {
   apiKey: "AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",
   authDomain: "auth.flexieduconsult.com.ng",
   projectId: "waec2026jamb2027"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const auth = getAuth(app);

onAuthStateChanged(auth, (user) => {
    const welcomeBanner = document.getElementById('welcome-banner');
    const userGreeting = document.getElementById('user-greeting');
    const authMenuBtn = document.getElementById('auth-menu-btn');
    const contactEmail = document.getElementById('contact-email');
    if (user) {
        welcomeBanner.style.display = "block";
        const name = user.displayName || user.email.split('@')[0];
        userGreeting.innerHTML = `Welcome back, <span style="color:var(--green)">${name}</span> 👋`;
        authMenuBtn.innerText = "Logout";
        authMenuBtn.style.color = "#ff4d4d";
        authMenuBtn.onclick = async (e) => {
            e.preventDefault();
            await signOut(auth);
            window.location.reload();
        };
        if (contactEmail) contactEmail.value = user.email;
    } else {
        welcomeBanner.style.display = "none";
        authMenuBtn.innerText = "Login";
        authMenuBtn.style.color = "white";
        authMenuBtn.onclick = () => {
            window.location.href = "login.html";
        };
    }
});

async function loadAnnouncement() {
    try {
        const annBanner = document.getElementById('announcement-banner');
        const q = query(
            collection(db, "announcements"),
            orderBy("createdAt", "desc"),
            limit(1)
        );
        const snap = await getDocs(q);
        if (!snap.empty) {
            const data = snap.docs[0].data();
            document.getElementById('ann-title').innerText = data.title;
            document.getElementById('ann-content').innerText = data.content;
            document.getElementById('ann-date').innerText = `Posted: ${new Date(data.createdAt.seconds * 1000).toLocaleDateString()}`;
            annBanner.style.display = "block";
        }
    } catch (e) {
        console.error("Error loading announcement:", e);
    }
}

let quoteSlides = [];
let currentQuoteIndex = 0;
let quoteInterval = null;

function escapeHtml(str) {
    return str ? str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;") : '';
}

async function loadMotivationalQuote() {
    try {
        const container = document.getElementById('quote-slider-container');
        const wrapper = document.getElementById('quote-slides-wrapper');
        const dotsContainer = document.getElementById('quote-dots');

        const q = query(collection(db, "quotes"), orderBy("createdAt", "desc"));
        const snap = await getDocs(q);

        if (snap.empty) {
            container.style.display = 'none';
            return;
        }

        wrapper.innerHTML = '';
        dotsContainer.innerHTML = '';

        let index = 0;
        snap.forEach(docSnap => {
            const data = docSnap.data();
            const slide = document.createElement('div');
            slide.className = `quote-slide ${index === 0 ? 'active' : ''}`;
            if (data.bgImage) {
                slide.style.backgroundImage = `url('${data.bgImage}')`;
            }

            slide.innerHTML = `
                <div class="quote-content-box">
                    <blockquote style="color: ${data.textColor || '#ffffff'};">"${escapeHtml(data.text)}"</blockquote>
                    <cite style="color: ${data.authorColor || '#f1f5f9'};">— ${escapeHtml(data.author)}</cite>
                </div>
            `;
            wrapper.appendChild(slide);

            const dot = document.createElement('span');
            dot.className = `dot ${index === 0 ? 'active-dot' : ''}`;
            dotsContainer.appendChild(dot);
            index++;
        });

        container.style.display = 'block';
        quoteSlides = document.querySelectorAll('.quote-slide');
        const quoteDots = dotsContainer.querySelectorAll('.dot');

        if (quoteSlides.length > 1) {
            if (quoteInterval) clearInterval(quoteInterval);
            quoteInterval = setInterval(() => {
                quoteSlides[currentQuoteIndex].classList.remove('active');
                if (quoteDots[currentQuoteIndex]) quoteDots[currentQuoteIndex].classList.remove('active-dot');
                currentQuoteIndex = (currentQuoteIndex + 1) % quoteSlides.length;
                quoteSlides[currentQuoteIndex].classList.add('active');
                if (quoteDots[currentQuoteIndex]) quoteDots[currentQuoteIndex].classList.add('active-dot');
            }, 5000);
        }
    } catch (e) {
        console.error("Error loading quotes slider:", e);
    }
}

const contactForm = document.getElementById('contact-form');
const submitBtn = document.getElementById('submit-btn');
contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    submitBtn.disabled = true;
    submitBtn.innerText = "Sending...";
    const formData = new FormData(contactForm);
    try {
        const response = await fetch("https://formspree.io/f/xojywaeg", {
            method: "POST",
            body: formData,
            headers: { "Accept": "application/json" }
        });
        if (response.ok) {
            Toastify({
                text: "Inquiry sent successfully!",
                duration: 4000,
                gravity: "top",
                position: "right",
                style: { background: "#2E8B57" }
            }).showToast();
            contactForm.reset();
        } else {
            throw new Error();
        }
    } catch (error) {
        Toastify({
            text: "Failed to send. Try again.",
            duration: 4000,
            gravity: "top",
            position: "right",
            style: { background: "#b22222" }
        }).showToast();
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerText = "Submit Inquiry";
    }
});

window.toggleMenu = () => {
    const m = document.getElementById('squareMenu');
    m.style.display = (m.style.display === "block") ? "none" : "block";
};

document.addEventListener('click', (e) => {
    const m = document.getElementById('squareMenu');
    const b = document.querySelector('.menu-btn');
    if (m && m.style.display === "block" && !m.contains(e.target) && !b.contains(e.target)) {
        m.style.display = "none";
    }
});

const slides = document.querySelectorAll('.slider-slide');
const dots = document.querySelectorAll('.slider-container > .slider-dots .dot');
let currentSlide = 0;

function showSlide(index) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active-dot'));
    slides[index].classList.add('active');
    if (dots[index]) dots[index].classList.add('active-dot');
}

setInterval(() => {
    currentSlide++;
    if (currentSlide >= slides.length) currentSlide = 0;
    showSlide(currentSlide);
}, 5000);

loadAnnouncement();
loadMotivationalQuote();

document.getElementById('current-year').textContent = new Date().getFullYear();

const tSlides = document.querySelectorAll('.testimonial-slide');
let currentTSlide = 0;

function showNextTestimonial() {
    const oldSlide = tSlides[currentTSlide];
    oldSlide.classList.remove('active');
    oldSlide.classList.add('exit');
    currentTSlide = (currentTSlide + 1) % tSlides.length;
    const nextSlide = tSlides[currentTSlide];
    nextSlide.classList.remove('exit');
    nextSlide.classList.add('active');
    setTimeout(() => {
        if (!oldSlide.classList.contains('active')) oldSlide.classList.remove('exit');
    }, 600);
}

setInterval(showNextTestimonial, 6000);

window.addEventListener('load', () => {
    setTimeout(() => {
        const tooltip = document.getElementById('support-tooltip');
        if (tooltip) {
            tooltip.style.transition = 'opacity 0.5s ease';
            tooltip.style.opacity = '0';
            setTimeout(() => { tooltip.style.display = 'none'; }, 500);
        }
    }, 5000);
});
</script>
</body>
</html>
