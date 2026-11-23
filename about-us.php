<?php
/**
 * Flexi Educational Consult
 * About Us
 */

$pageTitle = 'About Us | Flexi Educational Consult';
$pageDescription = 'Learn about Flexi Educational Consult, our educational mission, learning resources, student support services and commitment to helping Nigerian learners prepare for their next academic step.';
$currentPage = 'about';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="theme-color" content="#003366">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>

    <link rel="canonical" href="https://flexieduconsult.com.ng/about-us.php">
    <link rel="stylesheet" href="/assets/css/flexi-brand.css">

    <style>
        :root {
            --about-blue: #003366;
            --about-green: #2E8B57;
            --about-gold: #FFD700;
            --about-ink: #172033;
            --about-muted: #64748b;
            --about-soft: #f3f7fb;
            --about-line: #e5edf4;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--about-ink);
            background: #fff;
            font-family: Inter, Arial, Helvetica, sans-serif;
            line-height: 1.7;
        }

        .about-page { overflow: hidden; }
        .about-container { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }

        .about-hero {
            position: relative;
            padding: 86px 20px 82px;
            color: #fff;
            text-align: center;
            background:
                radial-gradient(circle at 12% 15%, rgba(46,139,87,.28), transparent 30%),
                radial-gradient(circle at 90% 85%, rgba(255,215,0,.13), transparent 26%),
                linear-gradient(135deg, #00264d, #003366 58%, #064a42);
        }

        .about-hero-inner { max-width: 850px; margin: 0 auto; }
        .about-eyebrow {
            display: inline-block;
            margin-bottom: 16px;
            padding: 7px 14px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 30px;
            color: #e7f4ed;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }
        .about-hero h1 {
            margin: 0 auto 18px;
            max-width: 800px;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: 1.12;
            letter-spacing: -1.5px;
        }
        .about-hero h1 span { color: var(--about-gold); }
        .about-hero p {
            max-width: 720px;
            margin: 0 auto;
            color: #e3edf7;
            font-size: clamp(1rem, 2vw, 1.18rem);
        }
        .about-hero-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 30px;
        }
        .about-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 21px;
            border: 2px solid transparent;
            border-radius: 8px;
            background: var(--about-green);
            color: #fff;
            font-weight: 800;
            text-decoration: none;
            transition: transform .2s ease, opacity .2s ease;
        }
        .about-btn:hover { transform: translateY(-2px); opacity: .94; }
        .about-btn-outline { border-color: rgba(255,255,255,.7); background: transparent; }

        .about-section { padding: 76px 0; }
        .about-section-soft { background: var(--about-soft); }
        .about-section-heading { max-width: 760px; margin: 0 auto 38px; text-align: center; }
        .about-kicker {
            display: block;
            margin-bottom: 9px;
            color: var(--about-green);
            font-size: .78rem;
            font-weight: 900;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .about-section-heading h2 {
            margin: 0 0 13px;
            color: var(--about-blue);
            font-size: clamp(1.8rem, 3.5vw, 2.65rem);
            line-height: 1.2;
        }
        .about-section-heading p { margin: 0; color: var(--about-muted); }

        .about-story-grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            align-items: center;
            gap: 48px;
        }
        .about-story-copy h2 {
            margin: 0 0 16px;
            color: var(--about-blue);
            font-size: clamp(1.8rem, 3vw, 2.45rem);
            line-height: 1.2;
        }
        .about-story-copy p { color: #526174; }
        .about-story-panel {
            padding: 34px;
            border: 1px solid var(--about-line);
            border-left: 5px solid var(--about-green);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 16px 45px rgba(0,38,77,.07);
        }
        .about-story-panel strong {
            display: block;
            margin-bottom: 12px;
            color: var(--about-blue);
            font-size: 1.2rem;
        }
        .about-story-panel p { margin: 0; color: var(--about-muted); }

        .about-pillars {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
        .about-pillar {
            padding: 26px;
            border: 1px solid var(--about-line);
            border-radius: 13px;
            background: #fff;
        }
        .about-pillar-number {
            display: inline-flex;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            border-radius: 11px;
            background: #e8f3ed;
            color: var(--about-green);
            font-weight: 900;
        }
        .about-pillar h3 { margin: 0 0 8px; color: var(--about-blue); font-size: 1.12rem; }
        .about-pillar p { margin: 0; color: var(--about-muted); font-size: .95rem; }

        .about-services {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .about-service {
            display: flex;
            gap: 14px;
            padding: 20px;
            border: 1px solid var(--about-line);
            border-radius: 12px;
            background: #fff;
        }
        .about-service-mark {
            display: flex;
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #eaf2f9;
            color: var(--about-blue);
            font-weight: 900;
        }
        .about-service h3 { margin: 0 0 5px; color: var(--about-blue); font-size: 1rem; }
        .about-service p { margin: 0; color: var(--about-muted); font-size: .92rem; }

        .about-journey {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            counter-reset: journey;
        }
        .about-journey-step {
            position: relative;
            padding: 24px 19px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid var(--about-line);
        }
        .about-journey-step span {
            display: block;
            margin-bottom: 9px;
            color: var(--about-green);
            font-size: .76rem;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .about-journey-step h3 { margin: 0 0 7px; color: var(--about-blue); font-size: 1rem; }
        .about-journey-step p { margin: 0; color: var(--about-muted); font-size: .9rem; }

        .about-audience {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .about-audience article {
            padding: 24px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid var(--about-line);
        }
        .about-audience h3 { margin: 0 0 8px; color: var(--about-blue); }
        .about-audience p { margin: 0; color: var(--about-muted); font-size: .94rem; }

        .about-note {
            margin-top: 26px;
            padding: 18px 20px;
            border-radius: 10px;
            background: #fff8dc;
            color: #65521a;
            font-size: .9rem;
        }

        .about-cta {
            padding: 66px 20px;
            color: #fff;
            text-align: center;
            background: linear-gradient(120deg, #003366, #064a42);
        }
        .about-cta h2 { margin: 0 0 12px; font-size: clamp(1.8rem, 4vw, 2.7rem); line-height: 1.2; }
        .about-cta p { max-width: 680px; margin: 0 auto; color: #e0ebf3; }
        .about-contact-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px 24px;
            margin-top: 24px;
        }
        .about-contact-links a { color: #fff; font-weight: 700; text-decoration: none; }
        .about-contact-links a:hover { text-decoration: underline; }

        @media (max-width: 850px) {
            .about-story-grid { grid-template-columns: 1fr; gap: 24px; }
            .about-pillars { grid-template-columns: repeat(2, 1fr); }
            .about-journey { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .about-container { width: min(100% - 30px, 1120px); }
            .about-hero { padding: 65px 16px; }
            .about-section { padding: 56px 0; }
            .about-pillars, .about-services, .about-audience, .about-journey { grid-template-columns: 1fr; }
            .about-story-panel { padding: 24px; }
            .about-service { padding: 17px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .about-btn { transition: none; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<main class="about-page">

    <section class="about-hero">
        <div class="about-hero-inner">
            <span class="about-eyebrow">About Flexi Educational Consult</span>
            <h1>Your Education Journey, <span>Simplified.</span></h1>
            <p>
                We bring learning resources, examination preparation, admission information
                and student support together to help learners make informed progress towards
                their academic goals.
            </p>
            <div class="about-hero-actions">
                <a class="about-btn" href="/index.php">Explore Learning Resources</a>
                <a class="about-btn about-btn-outline" href="#our-work">Discover What We Do</a>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="about-container about-story-grid">
            <div class="about-story-copy">
                <span class="about-kicker">Who We Are</span>
                <h2>Making useful educational support easier to access</h2>
                <p>
                    Flexi Educational Consult is an education-focused learning and information
                    platform serving students and other learners, with a strong focus on the
                    Nigerian education system. We understand that preparing for an examination,
                    choosing a course, finding reliable study materials and keeping up with
                    admission information can feel like several different tasks.
                </p>
                <p>
                    Our work brings these resources closer together. Through our website,
                    learning channels and student-support services, we help learners find
                    relevant information, practise what they have studied and take more
                    confident steps in their educational journey.
                </p>
                <p>
                    We aim to make educational information clearer and learning support more
                    accessible, whether a student is preparing for a major examination,
                    exploring admission options or strengthening their understanding of a subject.
                </p>
            </div>

            <aside class="about-story-panel">
                <strong>Our guiding idea</strong>
                <p>
                    Education should be easier to navigate. Students deserve access to useful
                    learning materials, clear guidance and practical opportunities to prepare.
                    Flexi exists to bring these forms of support into one accessible experience.
                </p>
            </aside>
        </div>
    </section>

    <section class="about-section about-section-soft">
        <div class="about-container">
            <div class="about-section-heading">
                <span class="about-kicker">Our Foundation</span>
                <h2>What guides our work</h2>
                <p>
                    The principles behind the resources we provide and the way we support learners.
                </p>
            </div>

            <div class="about-pillars">
                <article class="about-pillar">
                    <span class="about-pillar-number">01</span>
                    <h3>Accessible Learning</h3>
                    <p>We organise educational resources in formats that learners can access and use across supported devices.</p>
                </article>
                <article class="about-pillar">
                    <span class="about-pillar-number">02</span>
                    <h3>Useful Information</h3>
                    <p>We make examination, course and admission-related information easier to find and understand.</p>
                </article>
                <article class="about-pillar">
                    <span class="about-pillar-number">03</span>
                    <h3>Practical Preparation</h3>
                    <p>We encourage active learning through practice questions, CBT activities and lesson resources.</p>
                </article>
                <article class="about-pillar">
                    <span class="about-pillar-number">04</span>
                    <h3>Student Support</h3>
                    <p>We provide channels through which learners can seek information and find relevant educational support.</p>
                </article>
                <article class="about-pillar">
                    <span class="about-pillar-number">05</span>
                    <h3>Continuous Improvement</h3>
                    <p>We keep developing our digital learning experience and improving how educational resources are presented.</p>
                </article>
                <article class="about-pillar">
                    <span class="about-pillar-number">06</span>
                    <h3>Informed Decisions</h3>
                    <p>We want students to have helpful information when considering examinations, courses and next steps.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="about-section" id="our-work">
        <div class="about-container">
            <div class="about-section-heading">
                <span class="about-kicker">What We Do</span>
                <h2>Educational resources and services in one place</h2>
                <p>
                    Flexi Educational Consult supports different stages of the learner's journey
                    with resources and services that include:
                </p>
            </div>

            <div class="about-services">
                <article class="about-service">
                    <span class="about-service-mark">01</span>
                    <div>
                        <h3>JAMB, WAEC and NECO Preparation</h3>
                        <p>Study support and examination-focused resources to help candidates prepare for important secondary-school and tertiary-entry examinations.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">02</span>
                    <div>
                        <h3>Computer-Based Test (CBT) Practice</h3>
                        <p>Online CBT practice and simulator experiences that help learners become familiar with computer-based examination formats.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">03</span>
                    <div>
                        <h3>Past Questions and Study Materials</h3>
                        <p>Educational materials, downloadable PDFs and practice resources for revision and independent study.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">04</span>
                    <div>
                        <h3>Video Lessons and E-Learning</h3>
                        <p>Digital lesson resources and online learning opportunities that complement personal study and classroom learning.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">05</span>
                    <div>
                        <h3>Admission and Course Guidance</h3>
                        <p>Information to help candidates explore admission updates, course requirements and subject-related entry information.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">06</span>
                    <div>
                        <h3>JAMB Brochure and Syllabus Resources</h3>
                        <p>Access to syllabus and brochure information that can help candidates understand what to study and the requirements associated with courses.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">07</span>
                    <div>
                        <h3>Scholarship and Education Updates</h3>
                        <p>Education-related news, scholarship opportunities and updates intended to help learners stay informed.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">08</span>
                    <div>
                        <h3>Literature and Subject Resources</h3>
                        <p>Learning support and reading resources, including materials for prescribed Literature texts and other subjects.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">09</span>
                    <div>
                        <h3>Learning Communities</h3>
                        <p>Classroom groups and online communities that help learners connect with educational activities and shared learning support.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">10</span>
                    <div>
                        <h3>Tutorial Centre Information</h3>
                        <p>A directory-style resource to help learners discover tutorial and educational support centres.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">11</span>
                    <div>
                        <h3>Digital Student Services</h3>
                        <p>Online tools and services, including access to supported learning features, student accounts and relevant educational products.</p>
                    </div>
                </article>
                <article class="about-service">
                    <span class="about-service-mark">12</span>
                    <div>
                        <h3>Educational Guidance and Support</h3>
                        <p>Information and support channels for learners who need help navigating the resources and services available through Flexi.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="about-section about-section-soft">
        <div class="about-container">
            <div class="about-section-heading">
                <span class="about-kicker">The Learner's Journey</span>
                <h2>Support at different stages</h2>
                <p>
                    Our resources are designed to be useful from the point a learner starts
                    preparing to the stage where they consider their next academic move.
                </p>
            </div>

            <div class="about-journey">
                <article class="about-journey-step">
                    <span>Step 01</span>
                    <h3>Discover</h3>
                    <p>Find relevant syllabuses, course information, admission updates and learning resources.</p>
                </article>
                <article class="about-journey-step">
                    <span>Step 02</span>
                    <h3>Learn</h3>
                    <p>Use study materials, video lessons and other learning resources to build understanding.</p>
                </article>
                <article class="about-journey-step">
                    <span>Step 03</span>
                    <h3>Practise</h3>
                    <p>Test your knowledge with practice questions and available CBT learning tools.</p>
                </article>
                <article class="about-journey-step">
                    <span>Step 04</span>
                    <h3>Plan Ahead</h3>
                    <p>Use relevant course and admission information as you consider your next step.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="about-container">
            <div class="about-section-heading">
                <span class="about-kicker">Who We Serve</span>
                <h2>Learning support for different goals</h2>
                <p>
                    Our resources are intended to support a broad range of learners and people
                    involved in their education.
                </p>
            </div>

            <div class="about-audience">
                <article>
                    <h3>Secondary School Students</h3>
                    <p>Learners building subject knowledge, preparing for school examinations and developing good study habits.</p>
                </article>
                <article>
                    <h3>JAMB and Other Exam Candidates</h3>
                    <p>Candidates looking for practice materials, CBT preparation, syllabus information and admission-related guidance.</p>
                </article>
                <article>
                    <h3>Parents, Guardians and Educators</h3>
                    <p>People supporting learners who may find our educational information and learning resources useful.</p>
                </article>
            </div>

            <div class="about-note">
                <strong>Important:</strong> Flexi Educational Consult is an independent educational
                support platform. We are not JAMB, WAEC, NECO, a university or a government
                admissions office. Examination bodies and institutions remain the official
                sources for their rules, results, deadlines and admission decisions. Always
                confirm critical information with the relevant official organisation.
            </div>
        </div>
    </section>

    <section class="about-cta">
        <div class="about-container">
            <h2>Take the next step in your education journey</h2>
            <p>
                Explore the resources available on Flexi Educational Consult, keep learning,
                practise consistently and stay informed about opportunities that matter to you.
            </p>
            <div class="about-hero-actions">
                <a class="about-btn" href="/index.php">Visit Our Homepage</a>
                <a class="about-btn about-btn-outline" href="/contactsupport.html">Contact Support</a>
            </div>
            <div class="about-contact-links">
                <a href="tel:+2349034159839">+234 903 415 9839</a>
                <a href="tel:+2347033855206">+234 703 385 5206</a>
                <a href="mailto:info@flexieduconsult.com.ng">info@flexieduconsult.com.ng</a>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

</body>
</html>
