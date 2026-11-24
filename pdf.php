<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/firestore-server.php';
function flexi_pdf_e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
$resources = [];
$resourceLoadError = '';
try {
    $resourceDb = new FlexiFirestore();
    $resources = $resourceDb->listCollection('resources', 1000);
} catch (Throwable $exception) {
    error_log('[Flexi resource library] ' . $exception->getMessage());
    $resourceLoadError = 'The resource library is temporarily unavailable. Please refresh shortly.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Library | Flexi Educational Consult</title>
    <!-- Paystack Inline JS SDK V2 -->
    <script src="https://js.paystack.co/v2/inline.js"></script>
    
    <style>
        /* Base Reset & Typography */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #333333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        :root {
            --blue: #003366;
            --green: #2E8B57;
            --yellow: #FFD700;
            --bg: #f4f7f6;
            --radius: 12px;
            --shadow: 0 4px 18px rgba(0,0,0,0.06);
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



        /* Workspace & Filter Layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding: 30px 20px;
        }

        /* Dynamic Filter Ribbon */
        .filter-ribbon {
            background: #ffffff;
            border: 1px solid #eef2f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        .search-wrapper {
            flex: 2;
            min-width: 250px;
        }

        .search-wrapper input {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #dcdfe6;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-wrapper input:focus {
            border-color: #0B1B3D;
        }

        .filter-select {
            flex: 1;
            min-width: 140px;
            padding: 10px;
            border: 1px solid #dcdfe6;
            border-radius: 6px;
            background-color: #ffffff;
            font-size: 14px;
            outline: none;
            cursor: pointer;
        }

        .filter-select:focus {
            border-color: #00A859;
        }

        /* Resource Grid */
        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }

        /* Resource Card Styling */
        .resource-card {
            background: #ffffff;
            border: 1px solid #eef2f5;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .resource-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        /* Card Thumbnail Wrapper */
        .card-thumbnail-box {
            position: relative;
            width: 100%;
            padding-top: 75%;
            background-color: #f7f9fa;
            border-bottom: 1px solid #eef2f5;
        }

        .card-thumbnail-box img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top;
        }

        .card-thumbnail-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 28px;
            color: #dcdfe6;
            background: #fcfdfe;
        }

        /* Taxonomy Badges */
        .badge-container {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            gap: 6px;
        }

        .badge-exam {
            background-color: #0B1B3D;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-year {
            background-color: #00A859;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
        }

        /* Card Text Mechanics */
        .card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-subject {
            color: #00A859;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .card-title {
            color: #0B1B3D;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 15px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Card Action Strip */
        .card-footer {
            border-top: 1px solid #f7f9fa;
            padding-top: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .price-tag {
            font-size: 16px;
            font-weight: 700;
            color: #0B1B3D;
        }

        .status-free {
            font-size: 12px;
            font-weight: 600;
            color: #00A859;
            background-color: #e6f6ef;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* Action Buttons */
        .btn-action {
            border: none;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .btn-download {
            background-color: #0B1B3D;
            color: #ffffff;
        }

        .btn-download:hover {
            background-color: #122b5e;
        }

        .btn-unlock {
            background-color: #00A859;
            color: #ffffff;
        }

        .btn-unlock:hover {
            background-color: #008f4c;
        }

        .btn-action:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
    </style>
</head>
<body>

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
        <div class="filter-ribbon">
            <div class="search-wrapper">
                <input type="text" id="searchInput" placeholder="Search by title, subject or description...">
            </div>
            <select id="categoryFilter" class="filter-select">
                <option value="">All Categories</option>
                <option value="Textbooks">Textbooks</option>
                <option value="Past Questions">Past Questions</option>
                <option value="Lecture Notes">Lecture Notes</option>
                <option value="Formula Sheets">Formula Sheets</option>
            </select>
            <select id="examFilter" class="filter-select">
                <option value="">All Exams</option>
                <option value="JAMB">JAMB</option>
                <option value="WAEC">WAEC</option>
                <option value="NECO">NECO</option>
            </select>
            <select id="typeFilter" class="filter-select">
                <option value="">All Prices</option>
                <option value="free">Free</option>
                <option value="paid">Premium</option>
            </select>
        </div>

        <div class="resource-grid" id="resourceGrid">
<?php if ($resourceLoadError !== ''): ?>
    <div style="grid-column:1/-1;text-align:center;padding:40px;color:#909399;"><?= flexi_pdf_e($resourceLoadError) ?></div>
<?php elseif (!$resources): ?>
    <div id="emptyCatalogMessage" style="grid-column:1/-1;text-align:center;padding:40px;color:#909399;">No educational resources are available at the moment.</div>
<?php else: foreach ($resources as $resource):
    $data = $resource['fields'] ?? [];
    $id = (string)($resource['id'] ?? '');
    $title = (string)($data['title'] ?? 'Untitled Resource');
    $subject = (string)($data['subject'] ?? 'General Studies');
    $description = (string)($data['description'] ?? '');
    $category = (string)($data['category'] ?? '');
    $examType = (string)($data['examType'] ?? 'Exam');
    $year = (string)($data['year'] ?? 'General');
    $thumbnail = (string)($data['thumbnailUrl'] ?? '');
    $premium = (bool)($data['isPremium'] ?? false);
    $price = (int)($data['priceNGN'] ?? 0);
    $search = strtolower($title . ' ' . $subject . ' ' . $description);
?>
<article class="resource-card" data-id="<?= flexi_pdf_e($id) ?>" data-title="<?= flexi_pdf_e($title) ?>" data-subject="<?= flexi_pdf_e($subject) ?>" data-description="<?= flexi_pdf_e($description) ?>" data-category="<?= flexi_pdf_e($category) ?>" data-exam="<?= flexi_pdf_e($examType) ?>" data-premium="<?= $premium ? '1' : '0' ?>" data-price="<?= $price ?>" data-search="<?= flexi_pdf_e($search) ?>">
    <div class="card-thumbnail-box">
        <?php if ($thumbnail !== ''): ?><img src="<?= flexi_pdf_e($thumbnail) ?>" alt="<?= flexi_pdf_e($title) ?>" loading="lazy"><?php else: ?><div class="card-thumbnail-placeholder">PDF</div><?php endif; ?>
        <div class="badge-container"><span class="badge-exam"><?= flexi_pdf_e($examType) ?></span><span class="badge-year"><?= flexi_pdf_e($year) ?></span></div>
    </div>
    <div class="card-body">
        <span class="card-subject"><?= flexi_pdf_e($subject) ?></span>
        <h3 class="card-title"><?= flexi_pdf_e($title) ?></h3>
        <div class="card-footer">
            <span class="resource-status <?= $premium ? 'price-tag' : 'status-free' ?>"><?= $premium ? '₦' . number_format($price) : 'Free' ?></span>
            <button class="btn-action resource-action <?= $premium ? 'btn-unlock' : 'btn-download' ?>" type="button" data-id="<?= flexi_pdf_e($id) ?>" data-owned="<?= $premium ? '0' : '1' ?>"><?= $premium ? 'Unlock' : 'Download' ?></button>
        </div>
    </div>
</article>
<?php endforeach; endif; ?>
</div>
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
                <li><a href="pdf.php">Past Questions & PDFs</a></li>
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
        &copy; <?= date("Y") ?> Flexi Educational Consult. All Rights Reserved.
    </div>
    </footer>
    <script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { getFirestore, collection, doc, getDoc, getDocs, setDoc } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";
const firebaseConfig = {apiKey:"AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",authDomain:"waec2026jamb2027.firebaseapp.com",projectId:"waec2026jamb2027"};
const app=initializeApp(firebaseConfig), db=getFirestore(app), auth=getAuth(app);
const ownedResources=new Set(); let activeUser=null;

onAuthStateChanged(auth,async user=>{
 activeUser=user; ownedResources.clear();
 if(user){
  try{
   const purchases=await getDocs(collection(db,"purchases"));
   purchases.forEach(item=>{if(item.id.startsWith(user.uid+"_")) ownedResources.add(item.id.slice(user.uid.length+1));});
  }catch(error){console.error("Error mapping resource ownership:",error);}
 }
 refreshOwnership(); applyFilters();
});

function refreshOwnership(){
 document.querySelectorAll(".resource-card").forEach(card=>{
  const premium=card.dataset.premium==="1", owned=!premium||ownedResources.has(card.dataset.id);
  const button=card.querySelector(".resource-action"), status=card.querySelector(".resource-status");
  button.dataset.owned=owned?"1":"0"; button.textContent=owned?"Download":"Unlock";
  button.classList.toggle("btn-download",owned); button.classList.toggle("btn-unlock",!owned);
  status.className="resource-status "+(owned?"status-free":"price-tag");
  status.textContent=owned?(premium?"Unlocked":"Free"):"₦"+Number(card.dataset.price||0).toLocaleString("en-NG");
 });
}
function applyFilters(){
 const q=document.getElementById("searchInput").value.trim().toLowerCase();
 const category=document.getElementById("categoryFilter").value, exam=document.getElementById("examFilter").value, type=document.getElementById("typeFilter").value;
 let count=0;
 document.querySelectorAll(".resource-card").forEach(card=>{
  const premium=card.dataset.premium==="1";
  const show=(!q||card.dataset.search.includes(q))&&(!category||card.dataset.category===category)&&(!exam||card.dataset.exam===exam)&&(!type||(type==="free"?!premium:premium));
  card.hidden=!show;if(show)count++;
 });
 let empty=document.getElementById("noFilteredResources");
 if(!empty){empty=document.createElement("div");empty.id="noFilteredResources";empty.style.cssText="grid-column:1/-1;text-align:center;padding:40px;color:#909399";empty.textContent="No educational resources found matching your adjustments.";document.getElementById("resourceGrid").appendChild(empty);}
 empty.hidden=count!==0;
}
function normalizeDropboxUrl(url){
 if(!url)return ""; if(url.includes("dl=0"))return url.replace("dl=0","dl=1"); if(url.includes("dl=1"))return url;
 return url.includes("?")?url+"&dl=1":url+"?dl=1";
}
async function downloadResource(id,title,button){
 button.disabled=true;button.textContent="Streaming...";
 try{
  const snap=await getDoc(doc(db,"resource_links",id));
  if(!snap.exists())throw new Error("Resource link not found.");
  const url=normalizeDropboxUrl(snap.data().dropboxSecureUrl);if(!url)throw new Error("Resource link unavailable.");
  const a=document.createElement("a");a.href=url;a.download=title+".pdf";a.rel="noopener";document.body.appendChild(a);a.click();a.remove();
 }catch(error){console.error("Resource download failed:",error);alert("Could not start this resource download. Please try again.");}
 finally{button.disabled=false;button.textContent="Download";}
}
function purchaseResource(card,button){
 if(!activeUser){alert("Please log in to your Flexi student account before purchasing this resource.");window.location.href="/login.html";return;}
 button.disabled=true;button.textContent="Loading...";
 if(typeof window.PaystackPop!=="function"){alert("Paystack is still loading. Please try again.");button.disabled=false;button.textContent="Unlock";return;}
 const popup=new window.PaystackPop();
 popup.newTransaction({
  key:"pk_live_bb0c7b83bfdbd0c9990e2c7f7fd73a86d4821a00",email:activeUser.email,
  amount:Number(card.dataset.price)*100,currency:"NGN",
  reference:"flexi_"+Date.now()+"_"+Math.floor(Math.random()*1000),
  onSuccess:async transaction=>{
   try{
    await setDoc(doc(db,"purchases",activeUser.uid+"_"+card.dataset.id),{userId:activeUser.uid,resourceId:card.dataset.id,referenceCode:transaction.reference,settledAt:new Date().toISOString()});
    ownedResources.add(card.dataset.id);refreshOwnership();alert("Resource unlocked successfully!");
   }catch(error){console.error("Could not record resource ownership:",error);alert("Payment was received, but the ownership record could not be updated. Contact support with reference: "+transaction.reference);}
   finally{button.disabled=false;}
  },
  onCancel:()=>{button.disabled=false;button.textContent="Unlock";}
 });
}
document.getElementById("resourceGrid").addEventListener("click",event=>{
 const button=event.target.closest(".resource-action");if(!button||button.disabled)return;
 const card=button.closest(".resource-card");
 if(button.dataset.owned==="1")downloadResource(card.dataset.id,card.dataset.title,button);else purchaseResource(card,button);
});
["searchInput","categoryFilter","examFilter","typeFilter"].forEach(id=>document.getElementById(id).addEventListener(id==="searchInput"?"input":"change",applyFilters));
window.toggleMenu=()=>document.getElementById("squareMenu")?.classList.toggle("menu-open");
window.toggleStudyMenu=event=>{
 event?.stopPropagation();const submenu=document.getElementById("study-submenu"),button=document.getElementById("study-menu-toggle");if(!submenu||!button)return;
 const open=!submenu.classList.contains("open");submenu.classList.toggle("open",open);button.setAttribute("aria-expanded",String(open));
};
document.addEventListener("click",event=>{
 const menu=document.getElementById("squareMenu"),trigger=document.querySelector(".menu-btn");
 if(menu?.classList.contains("menu-open")&&!menu.contains(event.target)&&!trigger?.contains(event.target)){
  menu.classList.remove("menu-open");document.getElementById("study-submenu")?.classList.remove("open");document.getElementById("study-menu-toggle")?.setAttribute("aria-expanded","false");
 }
});
</script>
</body>
</html>