<?php
function flexiEsc($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
$brochureDataFile=__DIR__.'/data/brochure-courses.json';
$brochureData=is_file($brochureDataFile)?json_decode(file_get_contents($brochureDataFile),true):[];
if(!is_array($brochureData))$brochureData=[];
$institutionOptions=['Universities'=>'Degree (Universities)','Polytechnics'=>'ND / HND (Polytechnics & Monotechnics)','Colleges'=>'NCE (Colleges of Education)','IEIs'=>'Innovation Enterprise Institutions (IEIs)'];
$facultyOptions=['Administration'=>'Administration','Agriculture'=>'Agriculture','Engineering'=>'Engineering, Environment & Technology','Education'=>'Education','Law'=>'Law','Medical'=>'Medical, Pharmaceutical & Health Services','Sciences'=>'Sciences','SocialSciences'=>'Social Sciences'];
$institution=isset($_GET['institution'],$institutionOptions[$_GET['institution']])?$_GET['institution']:'Universities';
$faculty=isset($_GET['faculty'],$brochureData[$_GET['faculty']])?$_GET['faculty']:'Administration';
$selectedInstitutionText=$institutionOptions[$institution];$selectedFacultyText=$facultyOptions[$faculty]??$faculty;$courses=$brochureData[$faculty]??[];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>JAMB Brochure | Flexi Educational Consult</title>
  <meta name="description" content="Explore the JAMB brochure and course information for Nigerian universities, polytechnics, colleges of education and other institutions.">
  <link rel="canonical" href="https://www.flexieduconsult.com.ng/brochure.php">
  <style>
    :root {
      --blue: #003366;
      --green: #2E8B57;
      --bg-color: #f8f6f6;
      --card-bg: #ffffff;
      --text-main: #1f2937;
      --text-muted: #6b7280;
      --border-color: #e5e7eb;
      --flexi-navy: #0f172a;
      --flexi-green: #10b981;
      --radius: 12px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: var(--bg-color);
      color: var(--text-main);
    }

    

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 0 16px 24px 16px;
    }

    .nav-bar {
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .back-btn {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-size: 1.2rem;
      color: var(--text-main);
      box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .nav-title {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-main);
    }

    /* Dropdown Selectors */
    .select-box-group {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-bottom: 16px;
    }

    .select-box {
      width: 100%;
    }

    .select-box label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text-muted);
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .select-box select {
      width: 100%;
      padding: 12px 16px;
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
      background-color: var(--card-bg);
      color: var(--text-main);
      font-size: 0.95rem;
      font-weight: 600;
      outline: none;
      box-shadow: 0 2px 5px rgba(0,0,0,0.02);
      cursor: pointer;
    }

    .select-box select:focus {
      border-color: var(--flexi-green);
    }

    /* Search Box */
    .search-box {
      position: relative;
      margin-bottom: 16px;
    }

    .search-box input {
      width: 100%;
      padding: 14px 16px 14px 46px;
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
      background-color: var(--card-bg);
      color: var(--text-main);
      font-size: 0.95rem;
      outline: none;
      box-shadow: 0 2px 5px rgba(0,0,0,0.02);
      transition: border-color 0.2s;
    }

    .search-box input:focus {
      border-color: var(--flexi-green);
    }

    .search-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 1.1rem;
    }

    .section-title {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-main);
      margin-bottom: 14px;
    }

    .course-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .course-card {
      background: var(--card-bg);
      border-radius: var(--radius);
      padding: 16px 20px;
      border: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      text-decoration: none;
      box-shadow: 0 2px 4px rgba(0,0,0,0.02);
      transition: transform 0.1s ease, border-color 0.2s;
    }

    .course-card:active {
      transform: scale(0.98);
    }

    .course-card:hover {
      border-color: var(--flexi-green);
    }

    .course-name {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--text-main);
    }

    .arrow-icon {
      color: var(--text-muted);
      font-size: 1rem;
      font-weight: bold;
    }

    .no-results {
      text-align: center;
      color: var(--text-muted);
      padding: 20px;
      font-size: 0.9rem;
      display: none;
    }

    /* MODERN FOOTER STYLES */
.footer {
    background: linear-gradient(135deg, #011627 0%, #032038 100%);
    color: #e2e8f0;
    padding: 60px 20px 30px 20px;
    margin-top: 60px;
    border-top: 4px solid var(--green, #22c55e);
    font-size: 14px;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.8fr 1.2fr 1.3fr 1fr;
    gap: 35px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Headings */
.footer h4 {
    color: var(--yellow, #fbbf24);
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
    background: var(--yellow, #fbbf24);
    opacity: 0.7;
    border-radius: 2px;
}

.footer-about {
    line-height: 1.7;
    color: #94a3b8;
    margin: 0;
}

/* Links List */
.footer-links-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links-list li {
    margin-bottom: 10px;
}

.footer a {
    color: #cbd5e0;
    text-decoration: none;
    transition: all 0.25s ease;
}

.footer-links-list a:hover {
    color: var(--yellow, #fbbf24);
    transform: translateX(4px);
    display: inline-block;
}

/* WhatsApp Channel Text Link */
.whatsapp-channel-link {
    color: #25D366 !important;
    font-weight: 600;
    display: inline-block;
    transition: transform 0.25s ease, opacity 0.25s ease;
}

.whatsapp-channel-link:hover {
    opacity: 0.85;
    transform: translateX(4px);
}

/* Contacts & Emails Subheadings */
.contact-group {
    margin-bottom: 16px;
}

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

.contact-group a:hover {
    color: #ffffff;
}

/* Bottom Bar */
.footer-bottom {
    max-width: 1200px;
    margin: 40px auto 0 auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
    font-size: 13px;
    color: #64748b;
}

/* Responsive Styles */
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
        
  /* Homepage header and navigation */
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


    /* ========================================================
       MENU
       ======================================================== */

    .menu-container {
        position: relative;
    }


    .menu-btn {
        width: 38px;
        height: 38px;

        cursor: pointer;

        background: rgba(255,255,255,.06);

        border: 1px solid rgba(255,255,255,.32);

        border-radius: 8px;

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        transition: background .2s ease, transform .2s ease;
    }


    .menu-btn:hover {
        background: rgba(255,255,255,.14);
        transform: translateY(-1px);
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

        background: linear-gradient(145deg, #003366 0%, #075a55 52%, #2E8B57 100%);

        border: 2px solid var(--green);

        border-radius: 10px;

        z-index: 2000;

        box-shadow:
            0 12px 32px rgba(0,0,0,.35);

        overflow: hidden;
    }


    .square-menu.menu-open {
        display: block;
        animation: menuDrop .18s ease-out;
        transform-origin: top right;
    }


    @keyframes menuDrop {
        from {
            opacity: 0;
            transform: translateY(-6px) scale(.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }


    .square-menu a {
        display: flex;

        align-items: center;

        gap: 12px;

        padding: 13px 15px;

        color: white;

        text-decoration: none;

        font-size: 14px;

        border-bottom:
            1px solid rgba(255,255,255,.1);

        transition: background 0.15s, padding-left 0.15s;
    }


    .square-menu a:hover {
        background: rgba(255,255,255,0.12);
        padding-left: 19px;
    }


    .square-menu a:last-child {
        border-bottom: none;
    }


    .menu-icon {
        width: 21px;
        height: 21px;
        flex: 0 0 21px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.96);
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


    .study-menu-toggle {
        width: 100%;
        min-height: 46px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 15px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: 0;
        border-bottom: 1px solid rgba(255,255,255,.1);
        background: transparent;
        font-family: inherit;
        cursor: pointer;
        text-align: left;
        transition: background .15s ease, padding-left .15s ease;
    }

    .study-menu-toggle:hover {
        background: rgba(255,255,255,0.12);
        padding-left: 19px;
    }

    .study-menu-toggle .menu-icon {
        width: 21px;
        height: 21px;
        flex: 0 0 21px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.96);
    }

    .study-menu-toggle .menu-icon svg {
        width: 20px;
        height: 20px;
        display: block;
    }

    .study-chevron {
        margin-left: auto;
        width: 17px;
        height: 17px;
        flex: 0 0 17px;
        transition: transform .2s ease;
    }

    .study-menu-toggle[aria-expanded="true"] .study-chevron {
        transform: rotate(180deg);
    }

    .study-submenu {
        display: none;
        margin: 0 8px 5px 34px;
        padding: 4px;
        border-left: 1px solid rgba(255,255,255,.20);
        background: rgba(0,0,0,.10);
        border-radius: 0 8px 8px 0;
    }

    .study-submenu.open {
        display: block;
    }

    .study-submenu a {
        min-height: 40px;
        padding: 9px 10px;
        font-size: 13px;
        font-weight: 500;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }

    .study-submenu a:last-child {
        border-bottom: none;
    }

    .study-submenu .menu-icon {
        width: 18px;
        height: 18px;
        flex-basis: 18px;
    }

    .study-submenu .menu-icon svg {
        width: 18px;
        height: 18px;
    }



        
</style>
</head>
<body>

  <!-- Flexi Navy Header -->
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

            <svg class="menu-svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M4 6h16M4 12h16M4 18h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>

        </button>


        <div
            class="square-menu"
            id="squareMenu">

            <a href="/index.php">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
                <span class="menu-label">Home</span>
            </a>

            <a href="/videos.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M10 7l5 3-5 3V7z" fill="currentColor"/></svg></span>
                <span class="menu-label">Watch Video Lessons</span>
            </a>

            <button
                type="button"
                class="study-menu-toggle"
                id="study-menu-toggle"
                onclick="toggleStudyMenu(event)"
                aria-expanded="false"
                aria-controls="study-submenu">
                <span class="menu-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M4 5.5V19M8 7h8M8 11h8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="menu-label">Study</span>
                <svg class="study-chevron" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="study-submenu" id="study-submenu">
                <a href="/study.php">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M4 5.5V19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="menu-label">Past Questions</span>
                </a>
                <a href="/novel.php">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H19v18H7.5A2.5 2.5 0 0 0 5 22V4.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M5 4.5V19M9 7h6M9 11h7M9 15h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="menu-label">Novels</span>
                </a>
                <a href="/scholarship.php">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 8.5 12 4l9 4.5-9 4.5-9-4.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M6 11.5V16c2.8 2.2 9.2 2.2 12 0v-4.5M21 9v5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="menu-label">Scholarships</span>
                </a>
            </div>

            <a href="/download-app.php">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3v11" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M7.5 10.5L12 15l4.5-4.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 20h14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Download Flexi</span>
            </a>

            <a href="/syllabus.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v18H6.5A2.5 2.5 0 0 0 4 22V4.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 7h8M8 11h8M8 15h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Access the JAMB and WAEC syllabus here</span>
            </a>

            <a href="/brochure.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3v5h5M8 12h8M8 16h6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Access JAMB Brochure</span>
            </a>

            <a href="/cbt.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="13" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 21h8M12 17v4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">CBT Simulator</span>
            </a>

            <a href="/groups.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="17" cy="9" r="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0M15 14.5a4.5 4.5 0 0 1 5 5.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Classroom (Groups and chats)</span>
            </a>

            <a href="/purchase.php">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3 10h18M7 15h4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Purchase Scratch Cards</span>
            </a>

            <a href="/pdf.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3v5h5M8 14h8M8 17h6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Get your PDFs from here</span>
            </a>

            <a href="/location.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-12A7 7 0 0 0 5 9c0 5.8 7 12 7 12z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="9" r="2.3" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></span>
                <span class="menu-label">Tutorial Centers Near You</span>
            </a>

            <a href="/profile.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M5 20a7 7 0 0 1 14 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">User Profile</span>
            </a>

            <a
                href="#"
                id="auth-menu-btn">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M4 12h10M10 8l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                <span class="menu-label">Login</span>
            </a>

        </div>

    </div>

</header>

  <div class="container">
    <!-- Back Button -->
    <div class="nav-bar">
      <a class="back-btn" href="index.php">&#8592;</a>
      <div class="nav-title">Back to Main</div>
    </div>

    <!-- Institution & Faculty Selectors -->
    <div class="select-box-group">
      <!-- Institution Category Switcher -->
      <div class="select-box">
        <label for="institutionSelect">Select Institution Type</label>
        <select id="institutionSelect" name="institution" onchange="updateBrochureSelection()"><?php foreach ($institutionOptions as $value=>$label): ?><option value="<?= flexiEsc($value) ?>" <?= $institution===$value?'selected':'' ?>><?= flexiEsc($label) ?></option><?php endforeach; ?></select>
      </div>

      <!-- Faculty Switcher -->
      <div class="select-box">
        <label for="facultySelect">Select Faculty / Category</label>
        <select id="facultySelect" name="faculty" onchange="updateBrochureSelection()"><?php foreach ($facultyOptions as $value=>$label): ?><option value="<?= flexiEsc($value) ?>" <?= $faculty===$value?'selected':'' ?>><?= flexiEsc($label) ?></option><?php endforeach; ?></select>
      </div>
    </div>

    <!-- Real-time Search Filter -->
    <div class="search-box">
      <span class="search-icon">&#128269;</span>
      <input type="text" id="searchInput" placeholder="Search Course..." />
    </div>

    <div class="section-title">Select a Course:</div>
    
    <div class="course-list" id="courseListContainer"><?php foreach($courses as $course): ?><a class="course-card" href="requirements.html?institution=<?= rawurlencode($institution) ?>&amp;faculty=<?= rawurlencode($faculty) ?>&amp;course=<?= rawurlencode($course) ?>"><span class="course-name"><?= flexiEsc($course) ?></span><span class="arrow-icon">&#10095;</span></a><?php endforeach; ?></div>
    <div class="no-results" id="noResultsMsg">No courses found matching your search.</div>
  </div>

  <footer class="footer">
    <div class="footer-grid">
        <!-- Column 1: About -->
        <div class="footer-col">
            <p class="footer-about">
               We empower Nigerian students with admission updates, CBT preparation, tutorials, past questions in PDF, and premium educational support.
            </p>
        </div>

        <!-- Column 2: Quick Links -->
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links-list">
                <li><a href="index.php">Home</a></li>
                <li><a href="/about-us.php">About Us</a></li>
                <li><a href="https://elearning.flexieduconsult.com.ng" target="_blank" rel="noopener">WhatsApp Masterclass (E-Learning)</a></li>
                <li><a href="syllabus.php">Access the JAMB/WAEC syllabus</a></li>
                <li><a href="brochure.php">Access JAMB Brochure</a></li>
                <li><a href="videos.php">Video Lessons</a></li>
                <li><a href="pdf.html">Past Questions & PDFs</a></li>
                <li><a href="cbt.php">CBT Simulator</a></li>
                <li><a href="classroom.html">Classroom</a></li>
                <li><a href="location.html">Tutorial Centres</a></li>
            </ul>
        </div>

        <!-- Column 3: Support & Community -->
        <div class="footer-col">
            <h4>Support & Community</h4>
            
            <div class="contact-group">
                <a href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u" 
                   target="_blank" 
                   class="whatsapp-channel-link">
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

        <!-- Column 4: Social Links -->
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
  
  
<script>
function updateBrochureSelection(){const i=document.getElementById('institutionSelect').value;const f=document.getElementById('facultySelect').value;window.location.href='brochure.php?institution='+encodeURIComponent(i)+'&faculty='+encodeURIComponent(f);}
const searchInput=document.getElementById('searchInput'),list=document.getElementById('courseListContainer'),noResults=document.getElementById('noResultsMsg');
function filterCourses(){const term=searchInput.value.toLowerCase().trim();let visible=0;list.querySelectorAll('.course-card').forEach(card=>{const match=card.textContent.toLowerCase().includes(term);card.style.display=match?'flex':'none';if(match)visible++;});noResults.style.display=visible===0?'block':'none';}
searchInput.addEventListener('input',filterCourses);document.getElementById('current-year').textContent=new Date().getFullYear();
</script>
</body>
</html>
