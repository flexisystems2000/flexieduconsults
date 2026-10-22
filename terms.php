<?php
$currentYear = date('Y');
$pageTitle = 'Terms and Conditions | Flexi Educational Consult';
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
          content="Read the Terms and Conditions governing access to the website, educational resources, online services, and other offerings of Flexi Educational Consult.">

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

        .terms-layout {
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr);
            gap: 22px;
            align-items: start;
            margin-top: 25px;
        }

        .terms-nav,
        .terms-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .terms-nav {
            position: sticky;
            top: 20px;
            padding: 18px;
        }

        .terms-nav h2 {
            margin: 0 0 12px;
            color: var(--dark);
            font-size: 1rem;
        }

        .terms-nav a {
            display: block;
            padding: 7px 0;
            color: var(--text);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .terms-nav a:hover {
            color: var(--blue-light);
        }

        .terms-content {
            min-width: 0;
            padding: 28px;
        }

        .terms-content section {
            padding-bottom: 20px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border);
            scroll-margin-top: 20px;
        }

        .terms-content section:last-child {
            padding-bottom: 0;
            margin-bottom: 0;
            border-bottom: 0;
        }

        .terms-content h2 {
            margin: 0 0 12px;
            color: var(--blue);
            font-size: 1.25rem;
            line-height: 1.4;
        }

        .terms-content h3 {
            margin: 18px 0 8px;
            color: var(--dark);
            font-size: 1.02rem;
        }

        .terms-content p {
            margin: 0 0 12px;
        }

        .terms-content ul,
        .terms-content ol {
            padding-left: 22px;
            margin: 8px 0 14px;
        }

        .terms-content li {
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
            .terms-layout {
                grid-template-columns: 1fr;
            }

            .terms-nav {
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

            .terms-content {
                padding: 20px 17px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
        }

        @media print {
            header,
            .terms-nav,
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
            .terms-content {
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

            .terms-layout {
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
        <span class="eyebrow">Please read before using our services</span>

        <h1>Terms and Conditions</h1>

        <p>
            These Terms and Conditions govern your use of the
            Flexi Educational Consult website, educational
            resources, and related services.
        </p>

        <span class="updated-date">
            Last updated: <?= htmlspecialchars($lastUpdated, ENT_QUOTES, 'UTF-8') ?>
        </span>
    </div>

    <div class="terms-layout">

        <nav class="terms-nav" aria-label="Terms and Conditions contents">
            <h2>On this page</h2>

            <a href="#acceptance">1. Acceptance of Terms</a>
            <a href="#services">2. Our Services</a>
            <a href="#eligibility">3. Eligibility and Accounts</a>
            <a href="#subscriptions">4. Paid Services</a>
            <a href="#payments">5. Payments and Refund Requests</a>
            <a href="#educational">6. Educational Disclaimer</a>
            <a href="#acceptable-use">7. Acceptable Use</a>
            <a href="#intellectual-property">8. Intellectual Property</a>
            <a href="#third-party">9. Third-Party Services</a>
            <a href="#availability">10. Service Availability</a>
            <a href="#suspension">11. Suspension and Termination</a>
            <a href="#liability">12. Limitation of Liability</a>
            <a href="#indemnity">13. Responsibility for Misuse</a>
            <a href="#privacy">14. Privacy</a>
            <a href="#changes">15. Changes to These Terms</a>
            <a href="#law">16. Governing Law</a>
            <a href="#contact">17. Contact Us</a>
        </nav>

        <article class="terms-content">

            <section id="acceptance">
                <h2>1. Acceptance of Terms</h2>

                <p>
                    Welcome to Flexi Educational Consult. These
                    Terms and Conditions apply to your access to
                    and use of our website and the educational
                    services made available through it.
                </p>

                <p>
                    By accessing or using the website, you agree
                    to comply with these terms and any additional
                    conditions that apply to a particular service.
                    If you do not agree, you should discontinue
                    use of the affected service.
                </p>

                <p>
                    These terms should be read alongside our
                    Privacy Policy and any specific payment,
                    subscription, or service information displayed
                    when you use a relevant feature.
                </p>
            </section>

            <section id="services">
                <h2>2. Our Services</h2>

                <p>
                    Flexi Educational Consult provides educational
                    information and resources that may include:
                </p>

                <ul>
                    <li>JAMB, WAEC, NECO, and other examination preparation resources.</li>
                    <li>CBT practice and learning materials.</li>
                    <li>Past questions, PDF resources, and tutorials.</li>
                    <li>Admission-related information and educational updates.</li>
                    <li>Scholarship, syllabus, and brochure information.</li>
                    <li>Online learning, support, and other educational services.</li>
                </ul>

                <p>
                    The features available may change over time.
                    Some resources may be free, while other
                    resources or services may require payment,
                    registration, or a valid subscription.
                </p>

                <p>
                    We may improve, replace, suspend, or discontinue
                    features when reasonably necessary, subject
                    to applicable law and any specific commitments
                    made to users.
                </p>
            </section>

            <section id="eligibility">
                <h2>3. Eligibility and Accounts</h2>

                <p>
                    You must provide accurate information when
                    registering for an account or using a service
                    that requests personal details.
                </p>

                <p>
                    You are responsible for keeping your account
                    credentials confidential and for taking
                    reasonable steps to prevent unauthorised use
                    of your account.
                </p>

                <ul>
                    <li>Do not impersonate another person.</li>
                    <li>Do not submit information you know to be false or misleading.</li>
                    <li>Do not use another person's account without authorisation.</li>
                    <li>Notify us if you believe your account has been compromised.</li>
                    <li>Follow any additional rules provided for a particular learning service.</li>
                </ul>

                <p>
                    Users who are minors should use the services
                    with any parental or guardian involvement
                    required by applicable law.
                </p>
            </section>

            <section id="subscriptions">
                <h2>4. Paid Services and Subscriptions</h2>

                <p>
                    Certain learning services may be available
                    only after payment or activation of a
                    subscription.
                </p>

                <p>
                    The applicable price, subscription duration,
                    included features, and payment instructions
                    should be displayed during the relevant
                    purchase process.
                </p>

                <p>
                    A subscription provides access to the
                    specified service for the applicable period
                    and subject to the conditions communicated
                    for that service.
                </p>

                <p>
                    Unless the applicable service explicitly
                    provides otherwise, purchasing a subscription
                    does not transfer ownership of educational
                    content or grant permission to redistribute
                    it commercially.
                </p>

                <p>
                    If your subscription expires, access to
                    subscription-restricted features may be
                    suspended until a valid renewal or other
                    authorised extension is recorded.
                </p>

                <p>
                    If a payment appears successful but access
                    has not been activated, contact support with
                    the relevant transaction reference. Do not
                    send your card PIN, banking password, or
                    one-time banking verification code.
                </p>
            </section>

            <section id="payments">
                <h2>5. Payments and Refund Requests</h2>

                <p>
                    Payments for paid services may be processed
                    through a third-party payment provider,
                    including Paystack where enabled.
                </p>

                <p>
                    You should check the selected service,
                    amount, and applicable duration before
                    authorising payment.
                </p>

                <p>
                    Payment processing and verification may
                    depend on the payment provider, banking
                    networks, and the availability of the
                    relevant systems.
                </p>

                <h3>5.1 Payment verification</h3>

                <p>
                    A payment notification or debit alert alone
                    may not establish that a purchase has been
                    successfully verified by our system.
                    If there is a discrepancy, contact support
                    with your transaction reference so that
                    the payment can be investigated.
                </p>

                <h3>5.2 Refund requests</h3>

                <p>
                    If you believe you were charged incorrectly,
                    paid more than once for the same transaction,
                    or did not receive a service for which you
                    paid, contact support and provide the
                    relevant transaction details.
                </p>

                <p>
                    We will review the circumstances and any
                    applicable payment-provider rules and legal
                    requirements. A refund is not guaranteed
                    merely because a request is submitted.
                    Any refund decision will be subject to
                    applicable law and the circumstances of
                    the transaction.
                </p>

                <p>
                    Nothing in these terms excludes a refund
                    or other remedy that must be provided
                    under applicable law.
                </p>

                <div class="notice-box">
                    Keep your payment receipt or transaction
                    reference until the purchase has been
                    confirmed and any payment issue has been
                    resolved.
                </div>
            </section>

            <section id="educational">
                <h2>6. Educational Information and Disclaimer</h2>

                <p>
                    Our website provides educational information
                    and learning resources intended to assist
                    students in preparing for examinations and
                    improving their understanding of academic
                    subjects.
                </p>

                <p>
                    Educational content, examination updates,
                    admission information, scholarship notices,
                    and other published materials may change.
                    You should independently confirm important
                    dates, eligibility conditions, examination
                    requirements, and official decisions with
                    the relevant examination body or institution.
                </p>

                <p>
                    We do not guarantee a particular examination
                    score, admission decision, scholarship,
                    qualification, or academic outcome.
                    Individual results depend on many factors
                    outside our control.
                </p>

                <p>
                    Unless explicitly stated otherwise, our
                    website is an independent educational
                    resource and should not be understood as
                    an official government or examination-body
                    website.
                </p>
            </section>

            <section id="acceptable-use">
                <h2>7. Acceptable Use</h2>

                <p>
                    You agree to use our website and services
                    lawfully and in a manner that does not
                    interfere with other users or the operation
                    of the website.
                </p>

                <p>You must not:</p>

                <ul>
                    <li>Attempt to gain unauthorised access to accounts, systems, or restricted resources.</li>
                    <li>Interfere with the security, availability, or proper operation of the website.</li>
                    <li>Upload malicious code or use the website to distribute harmful software.</li>
                    <li>Misrepresent your identity or engage in fraudulent activity.</li>
                    <li>Copy, redistribute, resell, or commercially exploit protected materials without permission.</li>
                    <li>Use automated methods to collect information in ways that violate applicable law or our reasonable service restrictions.</li>
                    <li>Use the website for unlawful harassment, abuse, or infringement of another person's rights.</li>
                </ul>

                <p>
                    We may investigate suspected misuse and
                    take proportionate action where reasonably
                    necessary and legally permitted.
                </p>
            </section>

            <section id="intellectual-property">
                <h2>8. Intellectual Property</h2>

                <p>
                    Unless otherwise stated, website branding,
                    logos, original text, designs, graphics,
                    and other original materials made available
                    by Flexi Educational Consult are protected
                    by applicable intellectual property laws.
                </p>

                <p>
                    Third-party materials remain the property
                    of their respective owners and may be
                    subject to separate licence terms.
                </p>

                <p>
                    You may access and use materials for their
                    intended educational or personal purpose,
                    subject to any specific conditions displayed
                    with those materials.
                </p>

                <p>
                    You must not reproduce, distribute, publish,
                    sell, or commercially exploit protected
                    content without the permission of the
                    relevant rights holder, except where
                    permitted by applicable law.
                </p>

                <p>
                    Nothing in these terms transfers ownership
                    of intellectual property to you.
                </p>
            </section>

            <section id="third-party">
                <h2>9. Third-Party Services and Links</h2>

                <p>
                    The website may provide links to or integrate
                    with external platforms, including payment
                    services, social media, online learning
                    platforms, hosting services, and other
                    third-party resources.
                </p>

                <p>
                    Those services may have their own terms,
                    privacy policies, eligibility rules, and
                    availability requirements.
                </p>

                <p>
                    We are not responsible for independent
                    third-party websites or services that we
                    do not operate. Any transaction or
                    interaction with a third party may be
                    subject to the terms agreed between you
                    and that provider.
                </p>
            </section>

            <section id="availability">
                <h2>10. Website Availability and Changes</h2>

                <p>
                    We aim to keep our website and services
                    useful and accessible, but uninterrupted
                    availability cannot be guaranteed.
                </p>

                <p>
                    Access may occasionally be affected by
                    maintenance, network problems, hosting
                    interruptions, technical failures, security
                    measures, or circumstances beyond our
                    reasonable control.
                </p>

                <p>
                    We may correct errors, update content,
                    improve security, or modify features
                    when necessary.
                </p>

                <p>
                    Where a change materially affects a paid
                    service, we will handle the matter in
                    accordance with applicable law and any
                    specific commitments that apply to that
                    service.
                </p>
            </section>

            <section id="suspension">
                <h2>11. Suspension and Termination</h2>

                <p>
                    We may restrict or suspend access to a
                    service where reasonably necessary to
                    investigate suspected fraud, protect
                    users, respond to a security incident,
                    comply with law, address serious misuse,
                    or enforce applicable service conditions.
                </p>

                <p>
                    Where appropriate and legally permitted,
                    we will consider the circumstances and
                    provide relevant information or an
                    opportunity to resolve the issue.
                </p>

                <p>
                    Nothing in this section removes any
                    rights you may have under applicable law
                    or any specific paid-service commitment.
                </p>
            </section>

            <section id="liability">
                <h2>12. Limitation of Liability</h2>

                <p>
                    To the extent permitted by applicable law,
                    Flexi Educational Consult is not responsible
                    for losses caused by circumstances outside
                    our reasonable control, inaccurate
                    information supplied by third parties,
                    interruptions in external services, or
                    a user's misuse of the website.
                </p>

                <p>
                    We do not warrant that every item of
                    educational information will always be
                    complete, current, error-free, or suitable
                    for every individual purpose.
                </p>

                <p>
                    You should verify important examination,
                    admission, and scholarship information
                    with the relevant official source.
                </p>

                <p>
                    Nothing in these terms excludes or limits
                    liability where such exclusion or limitation
                    is prohibited by applicable law.
                </p>
            </section>

            <section id="indemnity">
                <h2>13. Responsibility for Misuse</h2>

                <p>
                    You are responsible for your use of the
                    website and for complying with applicable
                    laws and these terms.
                </p>

                <p>
                    Where permitted by law, you may be
                    responsible for losses or claims arising
                    directly from your unlawful conduct,
                    deliberate misuse of the service, or
                    infringement of another person's rights.
                </p>

                <p>
                    This section does not create liability
                    where applicable law does not permit it.
                </p>
            </section>

            <section id="privacy">
                <h2>14. Privacy and Personal Information</h2>

                <p>
                    Our handling of personal information is
                    explained in our
                    <a href="/policy.php">Privacy Policy</a>.
                </p>

                <p>
                    By using features that request information,
                    you should review the relevant privacy
                    information and provide accurate details
                    where necessary.
                </p>

                <p>
                    You should not submit information belonging
                    to another person unless you have a lawful
                    basis or appropriate authority to do so.
                </p>
            </section>

            <section id="changes">
                <h2>15. Changes to These Terms</h2>

                <p>
                    We may update these Terms and Conditions
                    to reflect changes in our services,
                    operational requirements, or applicable law.
                </p>

                <p>
                    The latest version will be published on
                    this page with an updated revision date.
                    Where legally required, we will provide
                    additional notice or obtain any necessary
                    agreement.
                </p>

                <p>
                    Your use of the website after updated
                    terms take effect will be subject to
                    those terms to the extent permitted by law.
                    If you do not agree to a change, you
                    should discontinue use of the affected
                    service.
                </p>
            </section>

            <section id="law">
                <h2>16. Governing Law and Disputes</h2>

                <p>
                    These terms are intended to be interpreted
                    consistently with the applicable laws of
                    the Federal Republic of Nigeria.
                </p>

                <p>
                    If you have a concern about a service,
                    we encourage you to contact us first so
                    that we can attempt to resolve the matter.
                </p>

                <p>
                    Nothing in these terms prevents a party
                    from exercising rights or remedies
                    available under applicable law.
                </p>
            </section>

            <section id="contact">
                <h2>17. Contact Us</h2>

                <p>
                    If you have questions about these Terms
                    and Conditions, a payment, or a service
                    provided by Flexi Educational Consult,
                    please contact us:
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
