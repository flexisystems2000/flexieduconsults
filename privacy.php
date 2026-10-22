<?php
$currentYear = date('Y');
$pageTitle = 'Privacy Policy | Flexi Educational Consult';
$lastUpdated = '22 October 2026';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#003366">

    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <meta name="description"
          content="Read the Privacy Policy of Flexi Educational Consult to understand how we handle personal information when you use our educational website and services.">

    <meta name="robots" content="index, follow">

    <link rel="icon"
          type="image/png"
          href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">

    <link rel="apple-touch-icon"
          href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">

    <style>
        :root {
            --blue: #003366;
            --blue-light: #1e40af;
            --green: #84cc16;
            --dark: #0f172a;
            --text: #334155;
            --muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --background: #f8fafc;
            --radius: 14px;
            --shadow: 0 8px 28px rgba(15, 23, 42, 0.07);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 24px;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: var(--background);
            line-height: 1.75;
        }

        a {
            color: var(--blue-light);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* WEBSITE HEADER */

        header {
            position: relative;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 5%;
            background: var(--blue);
            color: #ffffff;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .logo-img {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            object-fit: cover;
            border-radius: 50%;
            background: #ffffff;
        }

        .brand-name {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.05rem;
            line-height: 1.3;
        }

        .menu-container {
            position: relative;
            flex-shrink: 0;
        }

        .menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 10px;
            cursor: pointer;
        }

        .menu-btn:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .menu-svg {
            width: 25px;
            height: 25px;
        }

        .square-menu {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            display: none;
            width: min(340px, calc(100vw - 28px));
            max-height: 75vh;
            overflow-y: auto;
            padding: 8px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        }

        .square-menu.menu-open {
            display: block;
        }

        .square-menu > a,
        .study-menu-toggle,
        .study-submenu > a {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px;
            color: var(--dark);
            background: transparent;
            border: 0;
            border-radius: 8px;
            text-align: left;
            font: inherit;
            font-size: 0.91rem;
            cursor: pointer;
        }

        .square-menu > a:hover,
        .study-menu-toggle:hover,
        .study-submenu > a:hover {
            background: #eff6ff;
            text-decoration: none;
        }

        .menu-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            color: var(--blue-light);
        }

        .menu-icon svg,
        .study-chevron {
            width: 21px;
            height: 21px;
        }

        .menu-label {
            flex: 1;
        }

        .study-chevron {
            margin-left: auto;
            transition: transform 0.2s ease;
        }

        .study-menu-toggle[aria-expanded="true"] .study-chevron {
            transform: rotate(180deg);
        }

        .study-submenu {
            display: none;
            padding-left: 15px;
        }

        .study-submenu.open {
            display: block;
        }

        /* PAGE CONTENT */

        .page-container {
            width: min(100% - 32px, 1000px);
            margin: 35px auto 55px;
        }

        .page-heading {
            padding: 35px 28px;
            color: #ffffff;
            background: linear-gradient(135deg, #003366, #1e40af);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .page-heading .eyebrow {
            display: inline-block;
            margin-bottom: 8px;
            padding: 5px 11px;
            color: #ecfccb;
            background: rgba(132, 204, 22, 0.16);
            border: 1px solid rgba(190, 242, 100, 0.35);
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .page-heading h1 {
            margin: 0 0 12px;
            font-size: clamp(1.8rem, 5vw, 2.6rem);
            line-height: 1.2;
        }

        .page-heading p {
            max-width: 750px;
            margin: 0;
            color: #e2e8f0;
        }

        .updated-date {
            display: block;
            margin-top: 16px;
            color: #dbeafe;
            font-size: 0.9rem;
        }

        .policy-layout {
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr);
            gap: 22px;
            align-items: start;
            margin-top: 25px;
        }

        .policy-nav,
        .policy-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .policy-nav {
            position: sticky;
            top: 20px;
            padding: 18px;
        }

        .policy-nav h2 {
            margin: 0 0 12px;
            color: var(--dark);
            font-size: 1rem;
        }

        .policy-nav a {
            display: block;
            padding: 7px 0;
            color: var(--text);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .policy-nav a:hover {
            color: var(--blue-light);
        }

        .policy-content {
            min-width: 0;
            padding: 28px;
        }

        .policy-content section {
            padding-bottom: 20px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border);
            scroll-margin-top: 20px;
        }

        .policy-content section:last-child {
            padding-bottom: 0;
            margin-bottom: 0;
            border-bottom: 0;
        }

        .policy-content h2 {
            margin: 0 0 12px;
            color: var(--blue);
            font-size: 1.25rem;
            line-height: 1.4;
        }

        .policy-content h3 {
            margin: 18px 0 8px;
            color: var(--dark);
            font-size: 1.02rem;
        }

        .policy-content p {
            margin: 0 0 12px;
        }

        .policy-content ul {
            padding-left: 22px;
            margin: 8px 0 14px;
        }

        .policy-content li {
            margin-bottom: 7px;
        }

        .notice-box {
            margin: 18px 0;
            padding: 16px;
            background: #f0fdf4;
            border-left: 4px solid var(--green);
            border-radius: 8px;
        }

        /* WEBSITE FOOTER */

        .footer {
            margin-top: 50px;
            padding: 45px 5% 22px;
            color: #cbd5e1;
            background: #0f172a;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 28px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-about {
            margin: 0;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .footer-col h4 {
            margin: 0 0 16px;
            color: #ffffff;
            font-size: 1rem;
        }

        .footer-links-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .footer-links-list li {
            margin-bottom: 10px;
        }

        .footer-links-list a,
        .contact-group a {
            color: #94a3b8;
            font-size: 0.88rem;
            overflow-wrap: anywhere;
        }

        .footer-links-list a:hover,
        .contact-group a:hover {
            color: #ffffff;
        }

        .contact-group {
            margin-bottom: 16px;
        }

        .contact-group h5 {
            margin: 0 0 6px;
            color: #ffffff;
            font-size: 0.82rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .contact-group a {
            display: block;
            margin-bottom: 5px;
        }

        .whatsapp-channel-link {
            display: inline-block;
            margin-bottom: 18px;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 35px auto 0;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 760px) {
            .policy-layout {
                grid-template-columns: 1fr;
            }

            .policy-nav {
                position: static;
            }

            .footer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 12px 4%;
            }

            .brand-name {
                font-size: 0.92rem;
            }

            .logo-img {
                width: 42px;
                height: 42px;
            }

            .page-container {
                width: calc(100% - 22px);
                margin-top: 20px;
            }

            .page-heading {
                padding: 25px 20px;
            }

            .policy-content {
                padding: 20px 17px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
        }

        @media print {
            header,
            .policy-nav,
            .footer {
                display: none !important;
            }

            body {
                background: #ffffff;
            }

            .page-container {
                width: 100%;
                margin: 0;
            }

            .page-heading,
            .policy-content {
                color: #000000;
                background: #ffffff;
                box-shadow: none;
                border: 1px solid #dddddd;
            }

            .page-heading h1,
            .page-heading p,
            .page-heading .updated-date {
                color: #000000;
            }

            .policy-layout {
                display: block;
            }
        }

      /* Compact layout for Privacy Policy and Terms pages */
main {
    font-size: 14px;
    line-height: 1.65;
}

main h1 {
    font-size: clamp(24px, 3vw, 32px);
    line-height: 1.25;
    margin-bottom: 12px;
}

main h2 {
    font-size: 19px;
    line-height: 1.4;
    margin-top: 24px;
    margin-bottom: 9px;
}

main h3 {
    font-size: 16px;
    line-height: 1.4;
    margin-top: 18px;
    margin-bottom: 7px;
}

main p,
main li {
    font-size: 14px;
    line-height: 1.65;
}

main p {
    margin-bottom: 12px;
}

main ul,
main ol {
    padding-left: 22px;
    margin-top: 8px;
    margin-bottom: 14px;
}

main li {
    margin-bottom: 5px;
}

main section {
    margin-bottom: 20px;
}

main .container,
main .content,
main .legal-content {
    max-width: 1000px;
    padding-left: 20px;
    padding-right: 20px;
}

main button,
main .btn,
main a.button {
    font-size: 13px;
    padding: 9px 15px;
    border-radius: 6px;
}

@media (max-width: 600px) {
    main {
        font-size: 13px;
        line-height: 1.6;
    }

    main h1 {
        font-size: 23px;
    }

    main h2 {
        font-size: 17px;
        margin-top: 20px;
    }

    main h3 {
        font-size: 15px;
    }

    main p,
    main li {
        font-size: 13px;
    }

    main .container,
    main .content,
    main .legal-content {
        padding-left: 15px;
        padding-right: 15px;
    }

    main button,
    main .btn,
    main a.button {
        font-size: 12px;
        padding: 8px 12px;
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
            type="button"
            onclick="toggleMenu()"
            aria-label="Toggle Navigation Menu"
            aria-expanded="false"
            aria-controls="squareMenu">

            <svg class="menu-svg" viewBox="0 0 24 24"
                 aria-hidden="true" focusable="false">
                <path d="M4 6h16M4 12h16M4 18h16"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"/>
            </svg>
        </button>

        <div class="square-menu" id="squareMenu">

            <a href="/index.php">
                <span class="menu-icon" aria-hidden="true">⌂</span>
                <span class="menu-label">Home</span>
            </a>

            <a href="/videos.html">
                <span class="menu-icon" aria-hidden="true">▤</span>
                <span class="menu-label">Watch Video Lessons</span>
            </a>

            <button
                type="button"
                class="study-menu-toggle"
                id="study-menu-toggle"
                onclick="toggleStudyMenu(event)"
                aria-expanded="false"
                aria-controls="study-submenu">

                <span class="menu-icon" aria-hidden="true">▣</span>
                <span class="menu-label">Study</span>
                <span class="study-chevron" aria-hidden="true">⌄</span>
            </button>

            <div class="study-submenu" id="study-submenu">
                <a href="/study.php">
                    <span class="menu-icon" aria-hidden="true">▤</span>
                    <span class="menu-label">Past Questions</span>
                </a>

                <a href="/novel.php">
                    <span class="menu-icon" aria-hidden="true">▤</span>
                    <span class="menu-label">Novels</span>
                </a>

                <a href="/scholarship.php">
                    <span class="menu-icon" aria-hidden="true">☆</span>
                    <span class="menu-label">Scholarships</span>
                </a>
            </div>

            <a href="/download-app.php">
                <span class="menu-icon" aria-hidden="true">↓</span>
                <span class="menu-label">Download Flexi</span>
            </a>

            <a href="/syllabus.html">
                <span class="menu-icon" aria-hidden="true">▤</span>
                <span class="menu-label">Access the JAMB and WAEC syllabus here</span>
            </a>

            <a href="/brochure.html">
                <span class="menu-icon" aria-hidden="true">▧</span>
                <span class="menu-label">Access JAMB Brochure</span>
            </a>

            <a href="/cbt.html">
                <span class="menu-icon" aria-hidden="true">▣</span>
                <span class="menu-label">CBT Simulator</span>
            </a>

            <a href="/groups.html">
                <span class="menu-icon" aria-hidden="true">♧</span>
                <span class="menu-label">Classroom (Groups and chats)</span>
            </a>

            <a href="/purchase.html">
                <span class="menu-icon" aria-hidden="true">▤</span>
                <span class="menu-label">Purchase Scratch Cards</span>
            </a>

            <a href="/pdf.html">
                <span class="menu-icon" aria-hidden="true">▧</span>
                <span class="menu-label">Get your PDFs from here</span>
            </a>

            <a href="/location.html">
                <span class="menu-icon" aria-hidden="true">⌖</span>
                <span class="menu-label">Tutorial Centers Near You</span>
            </a>

            <a href="/profile.html">
                <span class="menu-icon" aria-hidden="true">♙</span>
                <span class="menu-label">User Profile</span>
            </a>

            <a href="/login.html" id="auth-menu-btn">
                <span class="menu-icon" aria-hidden="true">⇥</span>
                <span class="menu-label">Login</span>
            </a>

        </div>
    </div>
</header>

<main class="page-container">

    <div class="page-heading">
        <span class="eyebrow">Your information matters</span>

        <h1>Privacy Policy</h1>

        <p>
            This Privacy Policy explains how Flexi Educational Consult
            handles information when you visit our website or use our
            educational resources and services.
        </p>

        <span class="updated-date">
            Last updated: <?= htmlspecialchars($lastUpdated, ENT_QUOTES, 'UTF-8') ?>
        </span>
    </div>

    <div class="policy-layout">

        <nav class="policy-nav" aria-label="Privacy Policy contents">
            <h2>On this page</h2>

            <a href="#introduction">1. Introduction</a>
            <a href="#information">2. Information We Collect</a>
            <a href="#usage">3. How We Use Information</a>
            <a href="#cookies">4. Cookies and Advertising</a>
            <a href="#third-parties">5. Third-Party Services</a>
            <a href="#sharing">6. Sharing Information</a>
            <a href="#security">7. Data Security</a>
            <a href="#retention">8. Data Retention</a>
            <a href="#rights">9. Your Privacy Rights</a>
            <a href="#children">10. Children and Students</a>
            <a href="#external-links">11. External Websites</a>
            <a href="#changes">12. Policy Changes</a>
            <a href="#contact">13. Contact Us</a>
        </nav>

        <article class="policy-content">

            <section id="introduction">
                <h2>1. Introduction</h2>

                <p>
                    Flexi Educational Consult operates an educational
                    website that provides educational information,
                    examination preparation resources, tutorials,
                    past questions, CBT practice, admission-related
                    updates, and other learning services.
                </p>

                <p>
                    In this policy, "Flexi Educational Consult",
                    "Flexi", "we", "our", and "us" refer to the
                    organisation operating this website.
                    "You" refers to a visitor, student, user, or
                    other person accessing our services.
                </p>

                <p>
                    We aim to handle personal information responsibly
                    and in accordance with applicable data protection
                    laws, including the Nigeria Data Protection Act
                    2023 where applicable.
                </p>

                <p>
                    By using our website, you acknowledge that you
                    have had the opportunity to read this policy.
                    Where the law requires consent for a particular
                    activity, we will seek that consent as required.
                </p>
            </section>

            <section id="information">
                <h2>2. Information We Collect</h2>

                <p>
                    The information we handle depends on which parts
                    of our website and services you choose to use.
                </p>

                <h3>2.1 Information you provide</h3>

                <p>Depending on the service, you may provide:</p>

                <ul>
                    <li>Your name and contact details.</li>
                    <li>Your email address and telephone number.</li>
                    <li>Information included in messages or enquiries.</li>
                    <li>Account or profile information you submit.</li>
                    <li>Educational interests or information necessary to provide a requested service.</li>
                    <li>Payment references or transaction details needed to confirm a purchase or subscription.</li>
                </ul>

                <h3>2.2 Technical and usage information</h3>

                <p>
                    When you visit the website, certain information
                    may be processed by the website, hosting provider,
                    security services, or embedded third-party tools.
                    Depending on the service, this may include your
                    IP address, browser type, device information,
                    pages visited, timestamps, and diagnostic logs.
                </p>

                <h3>2.3 Information handled by external services</h3>

                <p>
                    Some website features rely on third-party
                    platforms. Information submitted through those
                    features may be processed by the relevant provider
                    under its own privacy terms.
                </p>

                <div class="notice-box">
                    We do not ask you to send passwords, bank PINs,
                    card PINs, or one-time banking verification codes
                    to us by email or through ordinary contact forms.
                </div>
            </section>

            <section id="usage">
                <h2>3. How We Use Information</h2>

                <p>Information may be used where necessary to:</p>

                <ul>
                    <li>Provide educational resources and requested services.</li>
                    <li>Respond to questions, complaints, and support requests.</li>
                    <li>Manage accounts and provide account-related features.</li>
                    <li>Process or confirm payments through the relevant payment provider.</li>
                    <li>Maintain website functionality, security, and reliability.</li>
                    <li>Publish educational announcements, news, and learning updates.</li>
                    <li>Understand website performance and improve the user experience.</li>
                    <li>Prevent fraud, misuse, unauthorised access, and technical abuse.</li>
                    <li>Meet applicable legal obligations.</li>
                </ul>

                <p>
                    We aim to use information only for purposes that
                    are relevant to the service or otherwise permitted
                    by applicable law.
                </p>
            </section>

            <section id="cookies">
                <h2>4. Cookies and Advertising</h2>

                <p>
                    Our website or third-party services may use
                    cookies, browser storage, or similar technologies
                    to support functionality, remember preferences,
                    protect services, measure performance, or display
                    advertisements.
                </p>

                <h3>4.1 Advertising</h3>

                <p>
                    This website may display advertisements served by
                    Google AdSense or other advertising partners.
                    These providers may use cookies or similar
                    technologies to deliver, limit, measure, or
                    personalise advertisements, subject to their
                    settings and applicable law.
                </p>

                <p>
                    Google's use of advertising cookies and related
                    information is governed by Google's own privacy
                    documentation and advertising controls.
                </p>

                <h3>4.2 Managing cookies</h3>

                <p>
                    You can review the privacy and cookie settings
                    available in your browser and, where offered,
                    in advertising or consent controls. Blocking
                    certain technologies may affect some website
                    features.
                </p>
            </section>

            <section id="third-parties">
                <h2>5. Third-Party Services</h2>

                <p>
                    Certain features of our website may rely on
                    external service providers. Depending on the
                    feature being used, these may include:
                </p>

                <ul>
                    <li>
                        <strong>Google Firebase:</strong>
                        for website authentication or related
                        application functionality where enabled.
                    </li>

                    <li>
                        <strong>Google Firestore:</strong>
                        for certain website content and data
                        functions.
                    </li>

                    <li>
                        <strong>Supabase:</strong>
                        for website news or related content
                        functionality.
                    </li>

                    <li>
                        <strong>Paystack:</strong>
                        where its payment services are used to
                        process transactions.
                    </li>

                    <li>
                        <strong>Formspree:</strong>
                        where its service is used to submit contact
                        or enquiry forms.
                    </li>

                    <li>
                        <strong>Google AdSense:</strong>
                        where advertising is displayed.
                    </li>

                    <li>
                        <strong>Hosting and infrastructure providers:</strong>
                        to deliver, secure, and maintain the website.
                    </li>
                </ul>

                <p>
                    The services used may change as our website
                    develops. Each provider may process information
                    under its own terms, privacy policy, and security
                    practices.
                </p>

                <p>
                    We do not control the independent privacy
                    practices of third-party providers. Please
                    consult the relevant provider's documentation
                    before using a third-party feature where you
                    require more information.
                </p>
            </section>

            <section id="sharing">
                <h2>6. Sharing Information</h2>

                <p>
                    We do not intend to sell personal information
                    submitted to us as part of our ordinary website
                    operations.
                </p>

                <p>
                    Information may be made available to service
                    providers where reasonably necessary to operate
                    the website, deliver requested services, process
                    payments, maintain security, or provide support.
                </p>

                <p>
                    We may also disclose information where required
                    by applicable law, a valid legal process, or
                    where disclosure is reasonably necessary to
                    protect our rights, users, or services.
                </p>

                <p>
                    We aim to limit disclosures to information
                    relevant to the applicable purpose.
                </p>
            </section>

            <section id="security">
                <h2>7. Data Security</h2>

                <p>
                    We take reasonable steps intended to protect
                    information against unauthorised access,
                    alteration, disclosure, loss, and misuse.
                    Depending on the service, these steps may include
                    access controls, security configurations, and
                    the use of established service providers.
                </p>

                <p>
                    No website, internet transmission, or electronic
                    storage system can be guaranteed to be completely
                    secure. We therefore cannot promise absolute
                    security.
                </p>

                <p>
                    You should protect your account credentials,
                    use a secure device, and contact us if you
                    believe your account or information has been
                    accessed without authorisation.
                </p>
            </section>

            <section id="retention">
                <h2>8. Data Retention</h2>

                <p>
                    We retain information for as long as reasonably
                    necessary for the purpose for which it was
                    collected, including providing services,
                    maintaining records, resolving disputes,
                    enforcing applicable terms, and meeting legal
                    or accounting obligations.
                </p>

                <p>
                    Retention periods may vary depending on the
                    nature of the information, the service involved,
                    and applicable legal requirements.
                </p>

                <p>
                    Where information is no longer required, we
                    will take appropriate steps to delete it or
                    otherwise handle it in accordance with
                    applicable requirements, subject to legitimate
                    retention needs.
                </p>
            </section>

            <section id="rights">
                <h2>9. Your Privacy Rights</h2>

                <p>
                    Subject to applicable law and any relevant
                    exceptions, you may have rights to:
                </p>

                <ul>
                    <li>Request access to personal information we hold about you.</li>
                    <li>Request correction of inaccurate or incomplete information.</li>
                    <li>Request deletion of information where legally applicable.</li>
                    <li>Object to, or request restriction of, certain processing activities.</li>
                    <li>Withdraw consent where processing relies on consent.</li>
                    <li>Request information about certain processing activities.</li>
                    <li>Submit a complaint to the relevant data protection authority.</li>
                </ul>

                <p>
                    To make a privacy request, contact us using the
                    details at the end of this policy. We may need
                    to verify your identity before responding.
                </p>

                <p>
                    We will consider requests in accordance with
                    applicable law. Some information may need to
                    be retained where a lawful exception or
                    obligation applies.
                </p>
            </section>

            <section id="children">
                <h2>10. Children and Students</h2>

                <p>
                    Our website provides educational resources that
                    may be used by secondary-school students and
                    other learners, including minors.
                </p>

                <p>
                    Students should provide only information
                    necessary for the service they are using.
                    Where applicable law requires parental or
                    guardian involvement or consent, that
                    requirement should be followed.
                </p>

                <p>
                    Parents or guardians who have questions about
                    information relating to a child may contact us
                    through the contact details provided below.
                </p>
            </section>

            <section id="external-links">
                <h2>11. External Websites and Links</h2>

                <p>
                    Our website may link to external websites,
                    social media platforms, payment services,
                    learning platforms, and other resources.
                </p>

                <p>
                    Once you leave our website, the privacy
                    practices of the destination website apply.
                    We are not responsible for the content,
                    security, or privacy practices of independent
                    websites that we do not operate.
                </p>

                <p>
                    Review the applicable terms and privacy policy
                    of an external service before submitting
                    personal information to it.
                </p>
            </section>

            <section id="changes">
                <h2>12. Changes to This Privacy Policy</h2>

                <p>
                    We may update this Privacy Policy when our
                    services, technology, or legal obligations
                    change.
                </p>

                <p>
                    The latest version will be published on this
                    page with an updated revision date. Where
                    required by law, we will provide additional
                    notice or seek consent for material changes.
                </p>

                <p>
                    We encourage visitors to review this page
                    periodically.
                </p>
            </section>

            <section id="contact">
                <h2>13. Contact Us</h2>

                <p>
                    If you have questions about this Privacy
                    Policy or wish to make a privacy request,
                    contact Flexi Educational Consult:
                </p>

                <p>
                    <strong>Email:</strong>
                    <a href="mailto:info@flexieduconsult.com.ng">
                        info@flexieduconsult.com.ng
                    </a>
                </p>

                <p>
                    <strong>Support:</strong>
                    <a href="mailto:support@flexieduconsult.com.ng">
                        support@flexieduconsult.com.ng
                    </a>
                </p>

                <p>
                    <strong>Telephone:</strong><br>
                    <a href="tel:+2349034159839">(+234) 903 415 9839</a><br>
                    <a href="tel:+2347033855206">(+234) 703 385 5206</a>
                </p>

                <div class="notice-box">
                    Please do not include passwords, card PINs,
                    or other highly sensitive credentials in
                    privacy enquiries.
                </div>
            </section>

        </article>
    </div>
</main>

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
            <h4>Quick Links</h4>

            <ul class="footer-links-list">
                <li><a href="/index.php">Home</a></li>

                <li>
                    <a href="https://elearning.flexieduconsult.com.ng"
                       target="_blank"
                       rel="noopener noreferrer">
                        WhatsApp Masterclass (E-Learning)
                    </a>
                </li>

                <li><a href="/syllabus.html">Access the JAMB/WAEC syllabus</a></li>
                <li><a href="/brochure.html">Access JAMB Brochure</a></li>
                <li><a href="/videos.html">Video Lessons</a></li>
                <li><a href="/pdf.html">Past Questions &amp; PDFs</a></li>
                <li><a href="/cbt.html">CBT Simulator</a></li>
                <li><a href="/groups.html">Classroom Groups and chats</a></li>
                <li><a href="/location.html">Tutorial Centres</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Support &amp; Community</h4>

            <div class="contact-group">
                <a href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u"
                   target="_blank"
                   rel="noopener noreferrer"
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

                <a href="mailto:support@flexieduconsult.com.ng">
                    support@flexieduconsult.com.ng
                </a>

                <a href="mailto:info@flexieduconsult.com.ng">
                    info@flexieduconsult.com.ng
                </a>
            </div>
        </div>

        <div class="footer-col social-links">
            <h4>Follow Us</h4>

            <ul class="footer-links-list">
                <li>
                    <a href="https://www.facebook.com/profile.php?id=61589793118693"
                       target="_blank"
                       rel="noopener noreferrer">
                        Facebook @flexieduconsult
                    </a>
                </li>

                <li>
                    <a href="https://instagram.com/flexieduconsult2000"
                       target="_blank"
                       rel="noopener noreferrer">
                        Instagram @flexieduconsult2000
                    </a>
                </li>

                <li>
                    <a href="https://www.tiktok.com/@flexieduconsult"
                       target="_blank"
                       rel="noopener noreferrer">
                        TikTok @flexieduconsult
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        &copy; <?= htmlspecialchars($currentYear, ENT_QUOTES, 'UTF-8') ?>
        Flexi Educational Consult. All Rights Reserved.
    </div>

</footer>

<script>
function toggleMenu() {
    const menu = document.getElementById('squareMenu');
    const button = document.querySelector('.menu-btn');

    if (!menu || !button) return;

    const opening = !menu.classList.contains('menu-open');

    menu.classList.toggle('menu-open', opening);
    button.setAttribute('aria-expanded', String(opening));

    if (!opening) {
        closeStudyMenu();
    }
}

function closeStudyMenu() {
    const submenu = document.getElementById('study-submenu');
    const button = document.getElementById('study-menu-toggle');

    if (submenu) submenu.classList.remove('open');

    if (button) {
        button.setAttribute('aria-expanded', 'false');
    }
}

function toggleStudyMenu(event) {
    if (event) event.stopPropagation();

    const submenu = document.getElementById('study-submenu');
    const button = document.getElementById('study-menu-toggle');

    if (!submenu || !button) return;

    const opening = !submenu.classList.contains('open');

    submenu.classList.toggle('open', opening);
    button.setAttribute('aria-expanded', String(opening));
}

document.addEventListener('click', function(event) {
    const menu = document.getElementById('squareMenu');
    const container = document.querySelector('.menu-container');

    if (menu && container &&
        menu.classList.contains('menu-open') &&
        !container.contains(event.target)) {
        menu.classList.remove('menu-open');

        const button = document.querySelector('.menu-btn');
        if (button) button.setAttribute('aria-expanded', 'false');

        closeStudyMenu();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const menu = document.getElementById('squareMenu');
        const button = document.querySelector('.menu-btn');

        if (menu) menu.classList.remove('menu-open');
        if (button) button.setAttribute('aria-expanded', 'false');

        closeStudyMenu();
    }
});
</script>

</body>
</html>
