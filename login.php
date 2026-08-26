<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="32x32" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="icon" type="image/png" sizes="16x16" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexi Tutors | Secure Portal</title>
    <style>
        :root {
            --blue: #003366;
            --green: #2E8B57;
            --white: #ffffff;
            --error-bg: #fdf2f2;
            --error-text: #d9534f;
            --success-bg: #e8f5e9;
            --success-text: #2e7d32;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f4f7f6; margin: 0; overflow-x: hidden; }

        header {
            background: var(--blue); color: white; height: 52px;
            display: flex; align-items: center; padding: 0 20px;
            border-bottom: 4px solid var(--green);
        }
        .brand { font-size: 0.9rem; font-weight: bold; letter-spacing: 1px; }

        .auth-container { max-width: 420px; margin: 40px auto; width: 90%; }
        .auth-form-view { width: 100%; box-sizing: border-box; }

        h2 { color: var(--blue); margin-top: 0; text-align: center; font-size: 1.4rem; font-weight: 700; }

        .input-group { margin-bottom: 15px; position: relative; }
        label { display: block; font-size: 0.85rem; color: #333; margin-bottom: 5px; font-weight: 600; }
        input { width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 8px; box-sizing: border-box; outline: none; transition: 0.3s; font-size: 1rem; color: #333; }
        input:focus { border-color: var(--blue); }

        .otp-input { letter-spacing: 6px; font-size: 18px; text-align: center; font-weight: bold; }

        .password-wrapper { position: relative; }
        .toggle-password {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #888; display: flex; align-items: center;
        }
        .toggle-password svg { width: 20px; height: 20px; }

        .strength-container { margin-top: 8px; margin-bottom: 10px; display: none; }
        .strength-bar { height: 4px; width: 100%; background: #eee; border-radius: 2px; overflow: hidden; margin-bottom: 4px; }
        .strength-fill { height: 100%; width: 0%; transition: 0.3s ease; }
        .strength-text { font-size: 0.7rem; font-weight: bold; text-transform: uppercase; text-align: right; display: block; }

        .requirements-box { display: none; margin-bottom: 15px; text-align: left; }
        .criteria { font-size: 0.78rem; color: #777; margin: 4px 0; display: flex; align-items: center; }
        .criteria::before { content: "•"; margin-right: 6px; font-size: 14px; }
        .criteria.valid { color: var(--green); font-weight: 600; }
        .criteria.invalid { color: var(--error-text); }

        #status-box {
            font-size: 0.85rem; text-align: center; margin-top: 15px;
            display: none; padding: 12px; border-radius: 8px;
            animation: slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            word-wrap: break-word;
        }
        @keyframes slideIn { from { transform: translateY(-10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .error { background: var(--error-bg); color: var(--error-text); border: 1px solid #f5c6cb; }
        .success { background: var(--success-bg); color: var(--success-text); border: 1px solid #c8e6c9; }
        .info { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

        .btn-auth { width: 100%; padding: 14px; background: var(--blue); color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.2s; text-transform: uppercase; }
        .btn-auth:disabled { background: #ccc; cursor: not-allowed; }

        .btn-google {
            width: 100%; padding: 12px; background: white; color: #444;
            border: 1px solid #ddd; border-radius: 8px; font-weight: 600;
            cursor: pointer; margin-top: 15px; display: flex; align-items: center; justify-content: center;
        }
        .btn-google img { width: 18px; margin-right: 10px; }

        .divider { text-align: center; margin: 20px 0; color: #aaa; font-size: 0.8rem; position: relative; }
        .divider::before, .divider::after { content: ""; position: absolute; top: 50%; width: 35%; height: 1px; background: #eee; }
        .divider::before { left: 0; } .divider::after { right: 0; }

        .toggle-text { text-align: center; margin-top: 20px; font-size: 0.9rem; color: #555; }
        .toggle-text span { color: var(--green); cursor: pointer; font-weight: bold; }

        .step-indicator { font-size: 0.75rem; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; text-align: center; }

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
            color: var(--yellow, #fbbf24);
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

        @media (min-width: 768px) {
            .auth-container { margin: 56px auto; }
            header { height: 56px; padding: 0 28px; }
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
    <div class="brand">FLEXI EDUCATIONAL CONSULT</div>
</header>

<div class="auth-container">
    <div class="auth-form-view">
        <h2 id="auth-title">Welcome Back</h2>

        <div id="otp-step-indicator" class="step-indicator" style="display: none;">Step 1 of 2: Enter Email</div>

        <div id="name-field-group" style="display: none;">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" id="auth-name" placeholder="Enter your name">
            </div>
        </div>

        <div class="input-group" id="email-field-group">
            <label>Email Address</label>
            <input type="email" id="auth-email" placeholder="student@example.com">
        </div>

        <div id="otp-field-group" style="display: none;">
            <div class="input-group">
                <label>6-Digit OTP Code</label>
                <input type="text" id="auth-otp" class="otp-input" placeholder="123456" maxlength="6">
            </div>
        </div>

        <div id="password-field-group">
            <div class="input-group">
                <label id="pass-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="auth-pass" placeholder="••••••••">
                    <div class="toggle-password" id="eye-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 5px;" id="forgot-link-container">
                    <span id="forgot-pass-link" style="font-size: 0.75rem; color: var(--blue); cursor: pointer; font-weight: 600;">Forgot Password?</span>
                </div>

                <div id="strength-meter" class="strength-container">
                    <div class="strength-bar"><div id="strength-fill" class="strength-fill"></div></div>
                    <div id="strength-text" class="strength-text"></div>
                </div>

                <div id="requirements-box" class="requirements-box">
                    <div id="req-length" class="criteria">At least 6 characters long</div>
                    <div id="req-letter" class="criteria">Contains a lowercase letter (a-z)</div>
                    <div id="req-uppercase" class="criteria">Contains an uppercase letter (A-Z)</div>
                    <div id="req-number" class="criteria">Contains a number (0-9)</div>
                </div>
            </div>
        </div>

        <div id="confirm-password-field-group" style="display: none;">
            <div class="input-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="auth-confirm-pass" placeholder="••••••••">
                    <div class="toggle-password" id="confirm-eye-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn-auth" id="main-auth-btn">LOGIN</button>

        <div id="google-block">
            <div class="divider">OR</div>
            <button class="btn-google" id="google-auth-btn">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="G">
                Continue with Google
            </button>
        </div>

        <div id="status-box"></div>

        <p class="toggle-text" id="toggle-area">
            Don't have an account? <span id="toggle-action" style="color: var(--green); cursor: pointer; font-weight: bold;">Sign Up</span>
        </p>
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
    import {
        getAuth, signInWithEmailAndPassword, createUserWithEmailAndPassword,
        updateProfile, GoogleAuthProvider, signInWithPopup,
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

    import {
        getFirestore,
        doc,
        setDoc,
        serverTimestamp
    } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

    const RENDER_BASE_URL = "https://flexieduconsult-email-server.onrender.com";

    const firebaseConfig = {
        apiKey: "AIzaSyA0bM6pk1T1peGSS7quvFPEMOMuplnNRNM",
        authDomain: "auth.flexieduconsult.com.ng",
        projectId: "waec2026jamb2027",
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getFirestore(app);
    const googleProvider = new GoogleAuthProvider();

    async function ensureUserProfile(user) {
        if (!user || !user.uid) return;

        const profileRef = doc(db, "profiles", user.uid);
        const displayName = (user.displayName || "").trim();
        const email = user.email || "";
        const photoURL = user.photoURL || "";

        await setDoc(profileRef, {
            uid: user.uid,
            displayName: displayName,
            name: displayName,
            userName: displayName,
            username: displayName,
            fullName: displayName,
            email: email,
            photoURL: photoURL,
            unreadCount: 0,
            updatedAt: serverTimestamp(),
            createdAt: serverTimestamp()
        }, { merge: true });
    }

    onAuthStateChanged(auth, async (user) => {
        if (!user) return;

        try {
            await ensureUserProfile(user);

            const urlParams = new URLSearchParams(window.location.search);
            const redirect = urlParams.get("redirect");

            if (redirect) {
                window.location.replace(decodeURIComponent(redirect));
            } else {
                window.location.replace("index.php");
            }

        } catch (error) {
            console.error("Unable to create/update user profile:", error);

            const urlParams = new URLSearchParams(window.location.search);
            const redirect = urlParams.get("redirect");

            if (redirect) {
                window.location.replace(decodeURIComponent(redirect));
            } else {
                window.location.replace("index.php");
            }
        }
    });

    const statusBox = document.getElementById('status-box');
    const mainBtn = document.getElementById('main-auth-btn');
    const passInput = document.getElementById('auth-pass');
    const passLabel = document.getElementById('pass-label');
    const confirmPassInput = document.getElementById('auth-confirm-pass');
    const emailInput = document.getElementById('auth-email');
    const nameInput = document.getElementById('auth-name');
    const otpInput = document.getElementById('auth-otp');

    const eyeIcon = document.getElementById('eye-icon');
    const confirmEyeIcon = document.getElementById('confirm-eye-icon');

    const nameFieldGroup = document.getElementById('name-field-group');
    const passwordFieldGroup = document.getElementById('password-field-group');
    const confirmPasswordFieldGroup = document.getElementById('confirm-password-field-group');
    const emailFieldGroup = document.getElementById('email-field-group');
    const otpFieldGroup = document.getElementById('otp-field-group');
    const otpStepIndicator = document.getElementById('otp-step-indicator');

    const forgotLinkContainer = document.getElementById('forgot-link-container');
    const googleBlock = document.getElementById('google-block');
    const authTitle = document.getElementById('auth-title');
    const toggleArea = document.getElementById('toggle-area');
    const forgotPassLink = document.getElementById('forgot-pass-link');

    const strengthMeter = document.getElementById('strength-meter');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    const reqBox = document.getElementById('requirements-box');

    let currentView = "login";
    let resetStep = 1;

    const switchView = (view) => {
        currentView = view;
        resetStep = 1;
        statusBox.style.display = "none";
        passInput.value = "";
        confirmPassInput.value = "";
        otpInput.value = "";
        passLabel.innerText = "Password";

        strengthMeter.style.display = "none";
        reqBox.style.display = "none";
        otpStepIndicator.style.display = "none";
        otpFieldGroup.style.display = "none";
        emailFieldGroup.style.display = "block";

        if (view === "login") {
            authTitle.innerText = "Welcome Back";
            nameFieldGroup.style.display = "none";
            passwordFieldGroup.style.display = "block";
            confirmPasswordFieldGroup.style.display = "none";
            forgotLinkContainer.style.display = "block";
            googleBlock.style.display = "block";
            mainBtn.innerText = "LOGIN";
            toggleArea.innerHTML = `Don't have an account? <span id="toggle-action" style="color:var(--green); font-weight:bold; cursor:pointer;">Sign Up</span>`;
        }
        else if (view === "signup") {
            authTitle.innerText = "Create Account";
            nameFieldGroup.style.display = "block";
            passwordFieldGroup.style.display = "block";
            confirmPasswordFieldGroup.style.display = "block";
            forgotLinkContainer.style.display = "none";
            googleBlock.style.display = "block";
            mainBtn.innerText = "SIGN UP";
            toggleArea.innerHTML = `Already have an account? <span id="toggle-action" style="color:var(--green); font-weight:bold; cursor:pointer;">Login</span>`;
        }
        else if (view === "forgot") {
            authTitle.innerText = "Reset Password";
            otpStepIndicator.innerText = "Step 1 of 2: Enter Email";
            otpStepIndicator.style.display = "block";
            nameFieldGroup.style.display = "none";
            passwordFieldGroup.style.display = "none";
            confirmPasswordFieldGroup.style.display = "none";
            forgotLinkContainer.style.display = "none";
            googleBlock.style.display = "none";
            mainBtn.innerText = "SEND OTP CODE";
            toggleArea.innerHTML = `Back to <span id="toggle-action" style="color:var(--green); font-weight:bold; cursor:pointer;">Login</span>`;
        }

        document.getElementById('toggle-action').onclick = () => {
            if (currentView === "login") switchView("signup");
            else switchView("login");
        };
    };

    forgotPassLink.onclick = () => switchView("forgot");
    document.getElementById('toggle-action').onclick = () => switchView("signup");

    eyeIcon.onclick = () => {
        const isProtected = passInput.type === "password";
        passInput.type = isProtected ? "text" : "password";
        eyeIcon.style.color = isProtected ? "var(--blue)" : "#888";
    };

    confirmEyeIcon.onclick = () => {
        const isProtected = confirmPassInput.type === "password";
        confirmPassInput.type = isProtected ? "text" : "password";
        confirmEyeIcon.style.color = isProtected ? "var(--blue)" : "#888";
    };

    const showStatus = (msg, type) => {
        statusBox.innerText = msg;
        statusBox.className = type;
        statusBox.style.display = "block";
    };

    document.getElementById('google-auth-btn').onclick = async () => {
        try {
            await signInWithPopup(auth, googleProvider);
        } catch (e) {
            showStatus(e.message.replace('auth/', '').replace(/-/g, ' '), "error");
        }
    };

    passInput.addEventListener('input', () => {
        if (currentView !== "signup") {
            strengthMeter.style.display = "none";
            reqBox.style.display = "none";
            return;
        }

        const value = passInput.value;
        if (!value) {
            strengthMeter.style.display = "none";
            reqBox.style.display = "none";
            return;
        }

        strengthMeter.style.display = "block";
        reqBox.style.display = "block";

        let score = 0;
        const checks = {
            length: value.length >= 6,
            letter: /[a-z]/.test(value),
            uppercase: /[A-Z]/.test(value),
            number: /[0-9]/.test(value)
        };

        document.getElementById('req-length').className = checks.length ? "criteria valid" : "criteria invalid";
        document.getElementById('req-letter').className = checks.letter ? "criteria valid" : "criteria invalid";
        document.getElementById('req-uppercase').className = checks.uppercase ? "criteria valid" : "criteria invalid";
        document.getElementById('req-number').className = checks.number ? "criteria valid" : "criteria invalid";

        if (checks.length) score++;
        if (checks.letter) score++;
        if (checks.uppercase) score++;
        if (checks.number) score++;

        switch (score) {
            case 1:
                strengthFill.style.width = "25%"; strengthFill.style.background = "#d9534f";
                strengthText.innerText = "Weak"; strengthText.style.color = "#d9534f";
                break;
            case 2:
                strengthFill.style.width = "50%"; strengthFill.style.background = "#f0ad4e";
                strengthText.innerText = "Medium"; strengthText.style.color = "#f0ad4e";
                break;
            case 3:
                strengthFill.style.width = "75%"; strengthFill.style.background = "#5bc0de";
                strengthText.innerText = "Strong"; strengthText.style.color = "#5bc0de";
                break;
            case 4:
                strengthFill.style.width = "100%"; strengthFill.style.background = "var(--green)";
                strengthText.innerText = "Very Secure"; strengthText.style.color = "var(--green)";
                break;
        }
    });

    mainBtn.onclick = async () => {
        const email = emailInput.value.trim();
        const pass = passInput.value;
        const confirmPass = confirmPassInput.value;
        const otp = otpInput.value.trim();
        statusBox.style.display = "none";

        if (!email) return showStatus("Please enter your email address.", "error");

        try {
            if (currentView === "login") {
                if (!pass) return showStatus("Please enter your password.", "error");
                mainBtn.disabled = true;
                showStatus("Logging in...", "info");
                await signInWithEmailAndPassword(auth, email, pass);
                window.location.href = "index.php";
            }
            else if (currentView === "signup") {
                if (!pass) return showStatus("Please enter your password.", "error");
                const name = nameInput.value.trim();
                if (!name) return showStatus("Please enter your full name.", "error");

                const passesAllRules = pass.length >= 6 && /[a-z]/.test(pass) && /[A-Z]/.test(pass) && /[0-9]/.test(pass);
                if (!passesAllRules) return showStatus("Please fulfill all password criteria rules.", "error");

                if (pass !== confirmPass) return showStatus("Passwords do not match.", "error");

                mainBtn.disabled = true;
                showStatus("Creating account...", "info");
                const res = await createUserWithEmailAndPassword(auth, email, pass);

                await updateProfile(res.user, { displayName: name });
                await ensureUserProfile(res.user);

                window.location.href = "index.php";
            }
            else if (currentView === "forgot") {
                if (resetStep === 1) {
                    mainBtn.disabled = true;
                    showStatus("Sending OTP code to your email...", "info");

                    const response = await fetch(`${RENDER_BASE_URL}/api/send-otp`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ email })
                    });

                    const data = await response.json();
                    mainBtn.disabled = false;

                    if (response.ok && data.success) {
                        resetStep = 2;
                        otpStepIndicator.innerText = "Step 2 of 2: Verify Code & Update Password";
                        otpFieldGroup.style.display = "block";
                        passwordFieldGroup.style.display = "block";
                        passLabel.innerText = "New Password";
                        mainBtn.innerText = "VERIFY & UPDATE PASSWORD";
                        showStatus("OTP code sent! Please check your email inbox or spam folder.", "success");
                    } else {
                        showStatus(data.message || "Failed to send OTP.", "error");
                    }
                }
                else if (resetStep === 2) {
                    if (otp.length !== 6) return showStatus("Please enter the 6-digit OTP code.", "error");
                    if (!pass || pass.length < 6) return showStatus("New password must be at least 6 characters.", "error");

                    mainBtn.disabled = true;
                    showStatus("Verifying code and updating password...", "info");

                    const response = await fetch(`${RENDER_BASE_URL}/api/verify-otp`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ email, otp, newPassword: pass })
                    });

                    const data = await response.json();
                    mainBtn.disabled = false;

                    if (response.ok && data.success) {
                        showStatus("Password updated successfully! Redirecting to login...", "success");
                        setTimeout(() => { switchView("login"); }, 2500);
                    } else {
                        showStatus(data.message || "Failed to reset password.", "error");
                    }
                }
            }
        } catch (e) {
            mainBtn.disabled = false;
            showStatus(e.message.replace('auth/', '').replace(/-/g, ' '), "error");
        }
    };

    document.getElementById('current-year').textContent = new Date().getFullYear();
</script>
</body>
</html>
