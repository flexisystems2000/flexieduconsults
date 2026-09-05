<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" sizes="32x32" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="icon" type="image/png" sizes="16x16" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Flexi CBT | Select Exam</title>

    <style>
        :root {
            --blue: #003366;
            --green: #2E8B57;
            --gray: #f8f9fa;
            --text: #333;
            --yellow: #FFD700;
        }

        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--gray);
            color: var(--text);
        }

        header {
            background: var(--blue);
            color: white;
            padding: 12px 15px;
            border-bottom: 4px solid var(--green);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 600px;
            margin: 0 auto;
            gap: 10px;
        }

        .header-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        header h1 {
            margin: 0;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .container {
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            padding: 15px;
        }

        @media (min-width: 768px) {
            .container { max-width: 600px; margin-top: 12px; }
        }

        .ad-banner {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }

        .ad-banner img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            border-radius: 6px;
        }

        .welcome-msg {
            text-align: center;
            margin-bottom: 25px;
        }

        .welcome-msg h2 {
            margin: 0;
            color: var(--blue);
            font-size: 1.5rem;
        }

        .welcome-msg p {
            color: #666;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .exam-card {
            display: flex;
            align-items: center;
            background: white;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 12px;
            cursor: pointer;
            border: 1px solid #eee;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            transition: transform 0.2s ease, background 0.15s;
        }

        .exam-card:hover { background: #f8faf9; }
        .exam-card:active {
            transform: scale(0.97);
            background: #f0f7ff;
        }

        .img-placeholder {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            margin-right: 15px;
            object-fit: contain;
            background: #f0f0f0;
            flex-shrink: 0;
        }

        .card-info { flex-grow: 1; }

        .card-info b {
            display: block;
            font-size: 0.95rem;
            color: var(--blue);
        }

        .card-info span {
            font-size: 0.75rem;
            color: #777;
            line-height: 1.3;
            display: block;
            margin-top: 2px;
        }

        .arrow {
            color: #ddd;
            font-size: 1.1rem;
        }

        .footer {
            background: linear-gradient(135deg, #011627 0%, #032038 100%);
            color: #e2e8f0;
            padding: 60px 20px 30px 20px;
            margin-top: 60px;
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
            margin: 40px auto 0 auto;
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
    <div class="header-container">
        <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" alt="Logo" class="header-logo">
        <h1>FLEXI EDUCATIONAL CONSULT</h1>
    </div>
</header>

<div class="container">

    <div class="ad-banner">
        <img src="https://i.postimg.cc/XvkqQc3F/20260418-185555-2.jpg" alt="Advertisement">
    </div>

    <div class="ad-banner">
        <img src="https://i.postimg.cc/pXBjLFpj/20260418-190953-2.jpg" alt="Advertisement">
    </div>

    <div class="welcome-msg">
        <h2>Select Examination</h2>
        <p>Choose your target simulator to begin</p>
    </div>

    <div class="exam-card" onclick="selectExam('JAMB')">
        <img src="https://i.postimg.cc/651CKsfF/images-(2).jpg" alt="JAMB Logo" class="img-placeholder">
        <div class="card-info">
            <b>JAMB CBT Simulator</b>
            <span>Unified Tertiary Matriculation Examination Mode</span>
        </div>
        <div class="arrow">›</div>
    </div>

    <div class="exam-card" onclick="selectExam('POST_UTME')">
        <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" alt="Post UTME Logo" class="img-placeholder">
        <div class="card-info">
            <b>Post UTME Simulator</b>
            <span>University Screening & Aptitude Practice Mode</span>
        </div>
        <div class="arrow">›</div>
    </div>

    <div class="exam-card" onclick="selectExam('WAEC')">
        <img src="https://i.postimg.cc/PxZSpX2Q/images-2.png" alt="WAEC Logo" class="img-placeholder">
        <div class="card-info">
            <b>WAEC CBT Simulator</b>
            <span>West African Senior School Certificate Mode</span>
        </div>
        <div class="arrow">›</div>
    </div>

    <div class="exam-card" onclick="selectExam('NECO')">
        <img src="https://i.postimg.cc/9Xr6RywM/681e17db-dde3-4b21-846b-0c25d3da9ee8.jpg" alt="NECO Logo" class="img-placeholder">
        <div class="card-info">
            <b>NECO CBT Simulator</b>
            <span>National Examination Council Practice Mode</span>
        </div>
        <div class="arrow">›</div>
    </div>

    <div class="exam-card" onclick="selectExam('FLEXI')">
        <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" alt="Flexi Logo" class="img-placeholder">
        <div class="card-info">
            <b>Custom CBT Simulator</b>
            <span>Internal School Mock & Special Assignments</span>
        </div>
        <div class="arrow">›</div>
    </div>

    <div class="exam-card" onclick="selectExam('CHALLENGE')">
        <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" alt="Flexi Challenge Logo" class="img-placeholder">
        <div class="card-info">
            <b>Flexi CBT Challenge</b>
            <span>Compete live with peers in national mock tests</span>
        </div>
        <div class="arrow">›</div>
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
                <li><a href="syllabus.php">Access the JAMB/WAEC syllabus</a></li>
                <li><a href="brochure.php">Access JAMB Brochure</a></li>
                <li><a href="videos.php">Video Lessons</a></li>
                <li><a href="pdf.html">Past Questions & PDFs</a></li>
                <li><a href="cbt.php">CBT Simulator</a></li>
                <li><a href="groups.html">Classroom</a></li>
                <li><a href="location.html">Tutorial Centres</a></li>
            </ul>
        </div>

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

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { getAuth } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

    const firebaseConfig = {
        apiKey: "AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",
        authDomain: "auth.flexieduconsult.com.ng",
        projectId: "waec2026jamb2027",
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    window.selectExam = (type) => {
        if (type === 'CHALLENGE') {
            window.location.href = "https://flexisystems2000.github.io/Weekly-CBT-Mock-/";
            return;
        }

        let folderType = type;

        if (type === 'NECO' || type === 'FLEXI') {
            folderType = 'WAEC';
        }

        localStorage.setItem("selectedExamType", folderType);
        localStorage.setItem("displayExamName", type);

        if (type === 'JAMB') {
            window.location.href = "page2.html";
        } else if (type === 'POST_UTME') {
            window.location.href = "page2_putme.html";
        } else {
            window.location.href = "page2_waec.html";
        }
    };

    document.getElementById('current-year').textContent = new Date().getFullYear();
</script>

</body>
</html>
