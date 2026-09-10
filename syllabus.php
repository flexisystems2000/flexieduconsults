<?php
$pageTitle = 'JAMB & WAEC Syllabus | Flexi Educational Consult';

$pageDescription = 'Access JAMB UTME and WAEC WASSCE examination syllabuses for different subjects on Flexi Educational Consult.';

$pageKeywords = 'JAMB syllabus, UTME syllabus, WAEC syllabus, WASSCE syllabus, JAMB subjects, WAEC subjects, Flexi Educational Consult';

$pageImage = 'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';

$pageCanonical = 'https://www.flexieduconsult.com.ng/syllabus.php';

$includeToastify = false;
$includeAdsense = false;

$currentPage = 'syllabus.php';

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
?>

<style>
    /* =========================================================
       FLEXI SYLLABUS PORTAL
       Page-specific styles only
       ========================================================= */

    .flexi-syllabus-page {
        background: #F8FAFC;
        color: #1E293B;
        min-height: calc(100vh - 80px);
        line-height: 1.6;
    }

    .flexi-syllabus-container {
        max-width: 950px;
        margin: 0 auto;
        padding: 1.25rem 1.5rem 3rem;
    }

    /* Portal Controls */

    .flexi-syllabus-controls {
        background: #FFFFFF;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .flexi-syllabus-exam-buttons {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }

    .flexi-syllabus-toggle {
        flex: 1;
        padding: 0.65rem 1rem;
        border: 2px solid var(--flexi-primary, #003366);
        background: transparent;
        color: var(--flexi-primary, #003366);
        font-weight: 700;
        font-size: 0.95rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .flexi-syllabus-toggle:hover {
        background: rgba(0, 51, 102, 0.05);
    }

    .flexi-syllabus-toggle.active {
        background: var(--flexi-primary, #003366);
        color: #FFFFFF;
    }

    /* Subject Selector */

    .flexi-syllabus-subject-wrap {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.4rem;
        width: 100%;
    }

    .flexi-syllabus-subject-wrap label {
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .flexi-syllabus-subject-select {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;

        padding: 0.65rem 0.85rem;
        border-radius: 6px;
        border: 1px solid #E2E8F0;

        font-size: 0.95rem;
        background: #F8FAFC;
        color: #1E293B;

        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;

        outline: none;
        cursor: pointer;
    }

    .flexi-syllabus-subject-select:focus {
        border-color: var(--flexi-secondary, #2E8B57);
        box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.08);
    }

    /* Loading */

    .flexi-syllabus-loading {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        color: #64748B;
    }

    /* Subject Title */

    .flexi-syllabus-subject-card {
        background: #FFFFFF;
        border-left: 5px solid var(--flexi-secondary, #2E8B57);

        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.25rem;

        border-top: 1px solid #E2E8F0;
        border-right: 1px solid #E2E8F0;
        border-bottom: 1px solid #E2E8F0;
    }

    .flexi-syllabus-subject-card h2 {
        margin: 0 0 0.25rem;
        color: var(--flexi-primary, #003366);
        font-size: 1.4rem;
        line-height: 1.3;
    }

    .flexi-syllabus-description {
        margin: 0.25rem 0 0;
        font-size: 0.9rem;
        color: #64748B;
    }

    .flexi-syllabus-exam-badge {
        display: inline-block;
        background: var(--flexi-secondary, #2E8B57);
        color: #FFFFFF;

        padding: 0.15rem 0.5rem;
        border-radius: 4px;

        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;

        margin-bottom: 0.4rem;
    }

    /* Section Cards */

    .flexi-syllabus-section-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 10px;

        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .flexi-syllabus-section-title {
        color: var(--flexi-primary, #003366);
        font-size: 1.2rem;

        margin-top: 0;
        margin-bottom: 1rem;

        border-bottom: 2px solid #F8FAFC;
        padding-bottom: 0.4rem;
    }

    .flexi-syllabus-list {
        padding-left: 1.25rem;
        margin: 0.5rem 0;
    }

    .flexi-syllabus-list li {
        margin-bottom: 0.4rem;
    }

    /* Tables */

    .flexi-syllabus-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .flexi-syllabus-table {
        width: 100%;
        min-width: 600px;

        border-collapse: collapse;
        margin-top: 0.75rem;
    }

    .flexi-syllabus-table th,
    .flexi-syllabus-table td {
        border: 1px solid #E2E8F0;
        padding: 0.65rem;
        text-align: left;
        vertical-align: top;
    }

    .flexi-syllabus-table th {
        background: #F1F5F9;
        color: var(--flexi-primary, #003366);
    }

    /* Topic Blocks */

    .flexi-syllabus-topic {
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        margin-bottom: 0.85rem;
        overflow: hidden;
    }

    .flexi-syllabus-topic-header {
        background: #F8FAFC;
        padding: 0.65rem 0.85rem;

        font-weight: 600;
        cursor: pointer;
    }

    .flexi-syllabus-topic-body {
        padding: 0.85rem;
        border-top: 1px solid #E2E8F0;
    }

    .flexi-syllabus-subsection {
        margin-top: 0.85rem;
        padding-left: 0.85rem;
        border-left: 3px solid var(--flexi-secondary, #2E8B57);
    }

    .flexi-syllabus-subsection-title {
        font-weight: 600;
        color: var(--flexi-primary, #003366);
        margin-bottom: 0.25rem;
    }

    .flexi-syllabus-error {
        background: #FFFFFF;
        border: 1px solid #fecaca;
        border-left: 4px solid #dc2626;

        border-radius: 8px;
        padding: 1rem;
    }

    .flexi-syllabus-error-title {
        color: #dc2626;
        font-weight: 700;
        margin: 0;
    }

    .flexi-syllabus-error-text {
        margin: 0.5rem 0 0;
        font-size: 0.85rem;
        color: #475569;
    }

    .flexi-syllabus-code {
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .flexi-syllabus-capitalize {
        text-transform: capitalize;
    }

    /* Mobile */

    @media (min-width: 600px) {
        .flexi-syllabus-subject-wrap {
            flex-direction: row;
            align-items: center;
        }

        .flexi-syllabus-subject-wrap label {
            flex: 0 0 auto;
        }
    }

    @media (max-width: 600px) {
        .flexi-syllabus-container {
            padding: 1rem 0.85rem 2.5rem;
        }

        .flexi-syllabus-exam-buttons {
            flex-direction: column;
        }

        .flexi-syllabus-toggle {
            width: 100%;
        }

        .flexi-syllabus-subject-card h2 {
            font-size: 1.2rem;
        }

        .flexi-syllabus-section-card {
            padding: 1rem;
        }
    }
</style>

<main class="flexi-syllabus-page">

    <div class="flexi-syllabus-container">

        <!-- Portal Controls -->
        <section class="flexi-syllabus-controls">

            <div class="flexi-syllabus-exam-buttons">

                <button
                    id="btn-jamb"
                    type="button"
                    class="flexi-syllabus-toggle active"
                    onclick="switchPortal('jamb')"
                >
                    Check JAMB Syllabus
                </button>

                <button
                    id="btn-waec"
                    type="button"
                    class="flexi-syllabus-toggle"
                    onclick="switchPortal('waec')"
                >
                    Check WAEC Syllabus
                </button>

            </div>

            <div class="flexi-syllabus-subject-wrap">

                <label for="subjectSelect">
                    <strong>Select Subject:</strong>
                </label>

                <select
                    id="subjectSelect"
                    class="flexi-syllabus-subject-select"
                    onchange="onSubjectChanged(this.value)"
                ></select>

            </div>

        </section>


        <!-- Main Syllabus View -->
        <div id="syllabus-app">

            <div class="flexi-syllabus-loading">
                Loading syllabus data...
            </div>

        </div>

    </div>

</main>


<script>
    /* =========================================================
       FLEXI SYLLABUS PORTAL
       ========================================================= */

    let currentExam = 'jamb';
    let activeSubjectMeta = null;


    /* =========================================================
       SUBJECT REGISTRY
       ========================================================= */

    const subjectRegistry = {

        jamb: [

            {
                id: "accounts",
                name: "Principles of Accounts",
                desc: "Bookkeeping, financial statements, and auditing basics.",
                path: "data/account.json"
            },

            {
                id: "arabic",
                name: "Arabic",
                desc: "Language studies and literary texts.",
                path: "data/arabic.json"
            },

            {
                id: "biology",
                name: "Biology",
                desc: "Diversity of organisms, Ecology, Genetics, and Physiology.",
                path: "data/biology.json"
            },

            {
                id: "chemistry",
                name: "Chemistry",
                desc: "Physical, Inorganic, and Organic Chemistry.",
                path: "data/chemistry.json"
            },

            {
                id: "commerce",
                name: "Commerce",
                desc: "Trade, business organizations, and financial systems.",
                path: "data/commerce.json"
            },

            {
                id: "computer",
                name: "Computer Studies",
                desc: "Computing systems, software development, and information networks.",
                path: "data/computer.json"
            },

            {
                id: "crk",
                name: "CRK",
                desc: "Christian Religious Knowledge: Study of the Bible and Christian ethics.",
                path: "data/crk.json"
            },

            {
                id: "economics",
                name: "Economics",
                desc: "Basic principles, consumer behavior, and national income.",
                path: "data/economics.json"
            },

            {
                id: "english",
                name: "Use of English",
                desc: "Compulsory for all candidates. Covers comprehension and structure.",
                path: "data/english.json"
            },

            {
                id: "art",
                name: "Art",
                desc: "History and theory of art, and design principles.",
                path: "data/art.json"
            },

            {
                id: "french",
                name: "French",
                desc: "Language structure, comprehension, and expression.",
                path: "data/french.json"
            },

            {
                id: "geography",
                name: "Geography",
                desc: "Physical geography, map reading, and economic geography.",
                path: "data/geography.json"
            },

            {
                id: "government",
                name: "Government",
                desc: "Political concepts, systems, and Nigerian government history.",
                path: "data/government.json"
            },

            {
                id: "hausa",
                name: "Hausa",
                desc: "Language study, literature, and culture.",
                path: "data/hausa.json"
            },

            {
                id: "history",
                name: "History",
                desc: "World history and key events in Nigerian history.",
                path: "data/history.json"
            },

            {
                id: "igbo",
                name: "Igbo",
                desc: "Language study, literature, and culture.",
                path: "data/igbo.json"
            },

            {
                id: "home-economics",
                name: "Home Economics",
                desc: "Family ecology, nutrition, food science, and consumer resource management.",
                path: "data/home-economics.json"
            },

            {
                id: "irk",
                name: "IRS",
                desc: "Islamic Religious Studies: Study of the Quran, Hadith, and jurisprudence.",
                path: "data/irk.json"
            },

            {
                id: "lit",
                name: "Literature in English",
                desc: "Analysis of African and non-African prose, drama, and poetry.",
                path: "data/lit.json"
            },

            {
                id: "math",
                name: "Mathematics",
                desc: "Algebra, Calculus, Geometry, Trigonometry, and Statistics.",
                path: "data/math.json"
            },

            {
                id: "music",
                name: "Music",
                desc: "Theory, history, and practice of music.",
                path: "data/music.json"
            },

            {
                id: "phe",
                name: "Physical and Health Education",
                desc: "Kinesiology, sports science, human anatomy, wellness, and community health systems.",
                path: "data/phe.json"
            },

            {
                id: "physics",
                name: "Physics",
                desc: "Mechanics, Heat, Optics, Waves, and Electromagnetism.",
                path: "data/physics.json"
            },

            {
                id: "yoruba",
                name: "Yoruba",
                desc: "Language study, literature, and culture.",
                path: "data/yoruba.json"
            }

        ],


        waec: [

            {
                id: "agric",
                name: "Agricultural Science",
                path: "data/waec/agric.json"
            },

            {
                id: "animal_husbandry",
                name: "Animal Husbandry",
                path: "data/waec/animal_husbandry.json"
            },

            {
                id: "arabic",
                name: "Arabic",
                path: "data/waec/arabic.json"
            },

            {
                id: "biology",
                name: "Biology",
                path: "data/waec/biology.json"
            },

            {
                id: "book_keeping",
                name: "Book Keeping",
                path: "data/waec/book_keeping.json"
            },

            {
                id: "building",
                name: "Building Construction",
                path: "data/waec/building.json"
            },

            {
                id: "business",
                name: "Business Management",
                path: "data/waec/business.json"
            },

            {
                id: "catering",
                name: "Catering Craft Practice",
                path: "data/waec/catering.json"
            },

            {
                id: "chemistry",
                name: "Chemistry",
                path: "data/waec/chemistry.json"
            },

            {
                id: "civic",
                name: "Civic Education",
                path: "data/waec/civic.json"
            },

            {
                id: "clerical",
                name: "Clerical Office Duties",
                path: "data/waec/clerical.json"
            },

            {
                id: "commerce",
                name: "Commerce",
                path: "data/waec/commerce.json"
            },

            {
                id: "computer",
                name: "Computer Studies",
                path: "data/waec/computer.json"
            },

            {
                id: "crk",
                name: "Christian Religious Studies (CRS)",
                path: "data/waec/crk.json"
            },

            {
                id: "data_processing",
                name: "Data Processing",
                path: "data/waec/data_processing.json"
            },

            {
                id: "economics",
                name: "Economics",
                path: "data/waec/economics.json"
            },

            {
                id: "edo",
                name: "Edo",
                path: "data/waec/edo.json"
            },

            {
                id: "english",
                name: "English Language",
                path: "data/waec/english.json"
            },

            {
                id: "financial_accounting",
                name: "Financial Accounting",
                path: "data/waec/financial_accounting.json"
            },

            {
                id: "fishery",
                name: "Fisheries",
                path: "data/waec/fishery.json"
            },

            {
                id: "food_and_nut",
                name: "Foods and Nutrition",
                path: "data/waec/food_and_nut.json"
            },

            {
                id: "forestry",
                name: "Forestry",
                path: "data/waec/forestry.json"
            },

            {
                id: "french",
                name: "French",
                path: "data/waec/french.json"
            },

            {
                id: "further_maths",
                name: "Further Mathematics",
                path: "data/waec/further_maths.json"
            },

            {
                id: "geography",
                name: "Geography",
                path: "data/waec/geography.json"
            },

            {
                id: "government",
                name: "Government",
                path: "data/waec/government.json"
            },

            {
                id: "hausa",
                name: "Hausa",
                path: "data/waec/hausa.json"
            },

            {
                id: "health_education",
                name: "Health Education",
                path: "data/waec/health_education.json"
            },

            {
                id: "history",
                name: "History",
                path: "data/waec/history.json"
            },

            {
                id: "home_management",
                name: "Home Management",
                path: "data/waec/home_management.json"
            },

            {
                id: "ict",
                name: "ICT",
                path: "data/waec/ict.json"
            },

            {
                id: "igbo",
                name: "Igbo",
                path: "data/waec/igbo.json"
            },

            {
                id: "insurance",
                name: "Insurance",
                path: "data/waec/insurance.json"
            },

            {
                id: "islam",
                name: "Islamic Studies (IRS)",
                path: "data/waec/islam.json"
            },

            {
                id: "literature",
                name: "Literature in English",
                path: "data/waec/literature.json"
            },

            {
                id: "marketing",
                name: "Marketing",
                path: "data/waec/marketing.json"
            },

            {
                id: "maths",
                name: "Mathematics",
                path: "data/waec/maths.json"
            },

            {
                id: "music",
                name: "Music",
                path: "data/waec/music.json"
            },

            {
                id: "office_practice",
                name: "Office Practice",
                path: "data/waec/office_practice.json"
            },

            {
                id: "physical_education",
                name: "Physical Education",
                path: "data/waec/physical_education.json"
            },

            {
                id: "physics",
                name: "Physics",
                path: "data/waec/physics.json"
            },

            {
                id: "technical_drawing",
                name: "Technical Drawing",
                path: "data/waec/technical_drawing.json"
            },

            {
                id: "visual_art",
                name: "Visual Arts",
                path: "data/waec/visual_art.json"
            },

            {
                id: "yoruba",
                name: "Yoruba",
                path: "data/waec/yoruba.json"
            }

        ]

    };


    /* =========================================================
       URL PARAMETERS
       ========================================================= */

    const urlParams =
        new URLSearchParams(window.location.search);

    const urlSubject =
        urlParams.get('subject');

    const urlExam =
        (urlParams.get('exam') || 'jamb').toLowerCase();


    /* =========================================================
       HTML ESCAPE HELPER
       ========================================================= */

    function escapeHTML(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* =========================================================
       GENERIC VALUE FORMATTER
       ========================================================= */

    function formatValue(value) {

        if (value === null || value === undefined) {
            return '';
        }

        if (Array.isArray(value)) {
            return value.join(', ');
        }

        if (typeof value === 'object') {
            return Object.values(value).join(', ');
        }

        return String(value);

    }


    /* =========================================================
       SWITCH JAMB / WAEC
       ========================================================= */

    function switchPortal(examType) {

        currentExam =
            examType === 'waec'
                ? 'waec'
                : 'jamb';


        document
            .getElementById('btn-jamb')
            .classList.toggle(
                'active',
                currentExam === 'jamb'
            );


        document
            .getElementById('btn-waec')
            .classList.toggle(
                'active',
                currentExam === 'waec'
            );


        const select =
            document.getElementById(
                'subjectSelect'
            );


        select.innerHTML = '';


        const fileList =
            subjectRegistry[currentExam] || [];


        fileList.forEach(item => {

            const option =
                document.createElement('option');

            option.value =
                item.path;

            option.textContent =
                item.name;

            select.appendChild(option);

        });


        if (fileList.length > 0) {

            const match =
                fileList.find(
                    f => f.id === urlSubject
                );


            const targetItem =
                match || fileList[0];


            select.value =
                targetItem.path;


            activeSubjectMeta =
                targetItem;


            loadSyllabus(
                targetItem.path
            );

        } else {

            document.getElementById(
                'syllabus-app'
            ).innerHTML = `
                <div class="flexi-syllabus-section-card">
                    <p>
                        No ${escapeHTML(currentExam.toUpperCase())}
                        syllabus files are configured.
                    </p>
                </div>
            `;

        }

    }


    /* =========================================================
       SUBJECT CHANGE
       ========================================================= */

    function onSubjectChanged(filePath) {

        const fileList =
            subjectRegistry[currentExam] || [];


        activeSubjectMeta =
            fileList.find(
                f => f.path === filePath
            ) || null;


        loadSyllabus(filePath);

    }


    /* =========================================================
       LOAD SYLLABUS JSON
       ========================================================= */

    async function loadSyllabus(filePath) {

        const app =
            document.getElementById(
                'syllabus-app'
            );


        app.innerHTML = `
            <div class="flexi-syllabus-loading">
                Loading syllabus data...
            </div>
        `;


        try {

            const response =
                await fetch(filePath, {
                    cache: 'no-cache'
                });


            if (!response.ok) {
                throw new Error(
                    `HTTP ${response.status}`
                );
            }


            const rawData =
                await response.json();


            if (currentExam === 'jamb') {

                renderJAMBSyllabus(rawData);

            } else {

                renderWAECSyllabus(rawData);

            }


        } catch (err) {

            console.error(
                'Syllabus renderer error:',
                err
            );


            app.innerHTML = `
                <div class="flexi-syllabus-error">

                    <p class="flexi-syllabus-error-title">
                        Unable to load syllabus
                    </p>

                    <p class="flexi-syllabus-error-text">
                        Could not load:
                        <code class="flexi-syllabus-code">
                            ${escapeHTML(filePath)}
                        </code>
                    </p>

                    <p class="flexi-syllabus-error-text">
                        If you are testing locally, make sure
                        you are running the site through a web
                        server such as Live Server rather than
                        opening the PHP/HTML file directly.
                    </p>

                </div>
            `;

        }

    }


    /* =========================================================
       JAMB RENDERER
       ========================================================= */

    function renderJAMBSyllabus(fullData) {

        const data =
            fullData && fullData.syllabus
                ? fullData.syllabus
                : fullData;


        const app =
            document.getElementById(
                'syllabus-app'
            );


        let descHTML =
            activeSubjectMeta &&
            activeSubjectMeta.desc

                ? `
                    <p class="flexi-syllabus-description">
                        ${escapeHTML(
                            activeSubjectMeta.desc
                        )}
                    </p>
                  `

                : '';


        let html = `

            <div class="flexi-syllabus-subject-card">

                <span class="flexi-syllabus-exam-badge">
                    JAMB / UTME
                </span>

                <h2>
                    ${escapeHTML(
                        data.subject ||
                        (
                            activeSubjectMeta
                                ? activeSubjectMeta.name
                                : 'Subject'
                        )
                    )}
                    Syllabus
                </h2>

                ${descHTML}

            </div>

        `;


        /* -----------------------------------------------------
           General Objectives
           ----------------------------------------------------- */

        const objectives =
            data.general_objectives ||
            data.objectives ||
            data.aims;


        if (
            Array.isArray(objectives) &&
            objectives.length
        ) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        General Objectives
                    </h3>

                    <ul class="flexi-syllabus-list">

                        ${objectives
                            .map(
                                o =>
                                    `<li>${o}</li>`
                            )
                            .join('')}

                    </ul>

                </div>

            `;

        }


        /* -----------------------------------------------------
           Syllabus Structure / Topics
           ----------------------------------------------------- */

        const structureData =
            data.structure ||
            data.topics ||
            data.sections ||
            data.syllabus_content;


        if (
            Array.isArray(structureData) &&
            structureData.length
        ) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Syllabus Structure & Topics
                    </h3>

            `;


            structureData.forEach(section => {

                const title =
                    section.section ||
                    section.topic ||
                    section.title ||
                    'Section';


                const notes =
                    section.contents_notes ||
                    section.notes ||
                    section.content ||
                    section.details;


                const subObjs =
                    section.objectives ||
                    section.learning_objectives;


                html += `

                    <div
                        style="
                            margin-bottom:1.5rem;
                            padding-bottom:1rem;
                            border-bottom:1px solid #E2E8F0;
                        "
                    >

                        <h4
                            style="
                                color:var(--flexi-primary,#003366);
                                margin-bottom:0.4rem;
                                font-size:1.05rem;
                            "
                        >
                            ${title}
                        </h4>

                `;


                if (notes) {

                    html += `

                        <p
                            style="
                                margin:0.4rem 0;
                                color:#334155;
                                font-size:0.95rem;
                            "
                        >
                            <strong>
                                Contents & Notes:
                            </strong>

                            ${
                                Array.isArray(notes)
                                    ? notes.join(', ')
                                    : notes
                            }

                        </p>

                    `;

                }


                if (
                    Array.isArray(subObjs) &&
                    subObjs.length
                ) {

                    html += `

                        <div style="margin-top:0.6rem;">

                            <strong
                                style="
                                    font-size:0.85rem;
                                    color:#475569;
                                "
                            >
                                Learning Objectives:
                            </strong>

                            <ul class="flexi-syllabus-list">

                                ${subObjs
                                    .map(
                                        o =>
                                            `<li>${o}</li>`
                                    )
                                    .join('')}

                            </ul>

                        </div>

                    `;

                }


                html += `</div>`;

            });


            html += `</div>`;

        }


        /* -----------------------------------------------------
           Selected / Prescribed Texts
           ----------------------------------------------------- */

        const selectedTexts =
            data.selected_texts ||
            data.prescribed_texts ||
            data.literature_texts;


        if (
            selectedTexts &&
            typeof selectedTexts === 'object'
        ) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Prescribed / Selected Texts
                    </h3>

            `;


            Object.keys(selectedTexts)
                .forEach(genre => {

                    html += `

                        <h4
                            class="flexi-syllabus-capitalize"
                            style="
                                color:var(--flexi-primary,#003366);
                                margin-bottom:0.25rem;
                            "
                        >
                            ${escapeHTML(
                                genre.replace(
                                    /_/g,
                                    ' '
                                )
                            )}
                        </h4>

                    `;


                    const categories =
                        selectedTexts[genre];


                    if (
                        Array.isArray(categories)
                    ) {

                        html += `

                            <ul class="flexi-syllabus-list">

                                ${categories
                                    .map(
                                        b =>
                                            `<li>${b}</li>`
                                    )
                                    .join('')}

                            </ul>

                        `;

                    } else if (
                        typeof categories === 'object' &&
                        categories !== null
                    ) {

                        Object.keys(categories)
                            .forEach(cat => {

                                const books =
                                    categories[cat];


                                if (
                                    Array.isArray(books) &&
                                    books.length
                                ) {

                                    html += `

                                        <strong
                                            class="flexi-syllabus-capitalize"
                                            style="
                                                font-size:0.85rem;
                                                color:#64748B;
                                            "
                                        >
                                            ${escapeHTML(
                                                cat.replace(
                                                    /_/g,
                                                    ' '
                                                )
                                            )}:
                                        </strong>

                                        <ul class="flexi-syllabus-list">

                                            ${books
                                                .map(
                                                    b =>
                                                        `<li>${b}</li>`
                                                )
                                                .join('')}

                                        </ul>

                                    `;

                                }

                            });

                    }

                });


            html += `</div>`;

        }


        /* -----------------------------------------------------
           Recommended Reading
           ----------------------------------------------------- */

        const readingList =
            data.recommended_texts ||
            data.reading_list ||
            data.recommended_books;


        if (readingList) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Recommended Reading List
                    </h3>

                    <ol class="flexi-syllabus-list">

            `;


            if (Array.isArray(readingList)) {

                html += readingList
                    .map(
                        text =>
                            `<li>${text}</li>`
                    )
                    .join('');


            } else if (
                typeof readingList === 'object'
            ) {

                Object.values(readingList)
                    .forEach(value => {

                        if (Array.isArray(value)) {

                            value.forEach(item => {

                                html +=
                                    `<li>${item}</li>`;

                            });

                        } else if (
                            typeof value === 'object' &&
                            value !== null
                        ) {

                            Object.values(value)
                                .forEach(item => {

                                    html +=
                                        `<li>${item}</li>`;

                                });

                        } else {

                            html +=
                                `<li>${value}</li>`;

                        }

                    });

            }


            html += `

                    </ol>

                </div>

            `;

        }


        app.innerHTML = html;

    }


    /* =========================================================
       WAEC RENDERER
       ========================================================= */

    function renderWAECSyllabus(fullData) {

        const data =
            fullData && fullData.syllabus
                ? fullData.syllabus
                : fullData;


        const app =
            document.getElementById(
                'syllabus-app'
            );


        let html = `

            <div class="flexi-syllabus-subject-card">

                <span class="flexi-syllabus-exam-badge">
                    WAEC / WASSCE
                </span>

                <h2>
                    ${escapeHTML(
                        data.subject ||
                        data.document_title ||
                        (
                            activeSubjectMeta
                                ? activeSubjectMeta.name
                                : 'Subject Syllabus'
                        )
                    )}
                </h2>

        `;


        if (data.preamble) {

            html += `

                <p style="margin-top:0.4rem;">
                    <strong>Preamble:</strong>
                    ${data.preamble}
                </p>

            `;

        }


        html += `</div>`;


        /* -----------------------------------------------------
           Objectives / Aims
           ----------------------------------------------------- */

        const objectives =
            data.objectives ||
            data.aims;


        if (
            Array.isArray(objectives) &&
            objectives.length
        ) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Course Objectives & Aims
                    </h3>

                    <ul class="flexi-syllabus-list">

                        ${objectives
                            .map(
                                obj =>
                                    `<li>${obj}</li>`
                            )
                            .join('')}

                    </ul>

                </div>

            `;

        }


        /* -----------------------------------------------------
           Assessment Objectives
           ----------------------------------------------------- */

        if (
            data.assessment_objectives &&
            typeof data.assessment_objectives === 'object'
        ) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Assessment Objectives
                    </h3>

            `;


            Object.keys(
                data.assessment_objectives
            ).forEach(key => {

                const formattedTitle =
                    key.replace(
                        /_/g,
                        ' '
                    );


                const items =
                    data.assessment_objectives[key];


                html += `

                    <h4
                        class="flexi-syllabus-capitalize"
                        style="
                            color:var(--flexi-primary,#003366);
                            margin-bottom:0.25rem;
                        "
                    >
                        ${escapeHTML(formattedTitle)}
                    </h4>

                `;


                if (Array.isArray(items)) {

                    html += `

                        <ul class="flexi-syllabus-list">

                            ${items
                                .map(
                                    item =>
                                        `<li>${item}</li>`
                                )
                                .join('')}

                        </ul>

                    `;

                }

            });


            html += `</div>`;

        }


        /* -----------------------------------------------------
           Examination Scheme
           ----------------------------------------------------- */

        if (
            data.examination_scheme ||
            data.structure_of_the_examination
        ) {

            const scheme =
                data.examination_scheme ||
                data.structure_of_the_examination;


            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Examination Scheme
                    </h3>

            `;


            if (typeof scheme === 'string') {

                html += `
                    <p>${scheme}</p>
                `;

            } else if (
                typeof scheme === 'object' &&
                scheme !== null
            ) {

                if (scheme.summary) {

                    html += `
                        <p>${scheme.summary}</p>
                    `;

                }


                /* Papers Array */

                if (
                    Array.isArray(scheme.papers) &&
                    scheme.papers.length
                ) {

                    html += `

                        <div class="flexi-syllabus-table-wrapper">

                            <table class="flexi-syllabus-table">

                                <thead>

                                    <tr>
                                        <th>Paper</th>
                                        <th>Title</th>
                                        <th>Duration</th>
                                        <th>Marks</th>
                                        <th>Details</th>
                                    </tr>

                                </thead>

                                <tbody>

                    `;


                    scheme.papers.forEach(paper => {

                        html += `

                            <tr>

                                <td>
                                    <strong>
                                        ${paper.paper || ''}
                                    </strong>
                                </td>

                                <td>
                                    ${paper.title || ''}
                                </td>

                                <td>
                                    ${paper.duration || ''}
                                </td>

                                <td>
                                    ${paper.marks || ''}
                                </td>

                                <td>
                                    ${paper.details || ''}
                                </td>

                            </tr>

                        `;

                    });


                    html += `

                                </tbody>

                            </table>

                        </div>

                    `;


                } else {

                    /* Dynamic Scheme */

                    Object.keys(scheme)
                        .forEach(key => {

                            if (
                                key === 'summary'
                            ) {
                                return;
                            }


                            const paper =
                                scheme[key];


                            const paperTitle =
                                key
                                    .replace(
                                        /_/g,
                                        ' '
                                    )
                                    .toUpperCase();


                            if (
                                typeof paper === 'string'
                            ) {

                                html += `

                                    <div style="margin-bottom:1rem;">

                                        <strong>
                                            ${escapeHTML(
                                                paperTitle
                                            )}:
                                        </strong>

                                        ${paper}

                                    </div>

                                `;

                            } else if (
                                typeof paper === 'object' &&
                                paper !== null
                            ) {

                                html += `

                                    <div
                                        class="flexi-syllabus-topic"
                                        style="padding:0.85rem;"
                                    >

                                        <h4
                                            style="
                                                margin:0 0 0.5rem;
                                                color:var(--flexi-primary,#003366);
                                            "
                                        >
                                            ${escapeHTML(
                                                paperTitle
                                            )}
                                        </h4>

                                `;


                                if (paper.duration) {

                                    html += `
                                        <p style="margin:0.2rem 0;">
                                            <strong>Duration:</strong>
                                            ${paper.duration}
                                        </p>
                                    `;

                                }


                                if (paper.marks) {

                                    html += `
                                        <p style="margin:0.2rem 0;">
                                            <strong>Marks:</strong>
                                            ${paper.marks}
                                        </p>
                                    `;

                                }


                                if (paper.description) {

                                    html += `
                                        <p style="margin:0.2rem 0;">
                                            <strong>Description:</strong>
                                            ${paper.description}
                                        </p>
                                    `;

                                }


                                if (
                                    Array.isArray(
                                        paper.sections
                                    )
                                ) {

                                    html += `

                                        <ul class="flexi-syllabus-list">

                                            ${paper.sections
                                                .map(
                                                    section =>
                                                        `<li>${section}</li>`
                                                )
                                                .join('')}

                                        </ul>

                                    `;

                                }


                                html += `</div>`;

                            }

                        });

                }

            }


            html += `</div>`;

        }


        /* -----------------------------------------------------
           Detailed Syllabus Content
           ----------------------------------------------------- */

        const detailed =
            data.detailed_syllabus ||
            data.syllabus_content ||
            data.topics;


        if (detailed) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Detailed Syllabus Content
                    </h3>

            `;


            if (Array.isArray(detailed)) {

                detailed.forEach(section => {

                    const secTitle =
                        section.section_name ||
                        section.section_title ||
                        section.title ||
                        section.topic ||
                        'Section';


                    html += `

                        <h4
                            style="
                                color:var(--flexi-primary,#003366);
                                margin-top:1rem;
                            "
                        >
                            ${secTitle}
                        </h4>

                    `;


                    if (
                        Array.isArray(
                            section.contents
                        )
                    ) {

                        html += `

                            <ul class="flexi-syllabus-list">

                                ${section.contents
                                    .map(
                                        content =>
                                            `<li>${content}</li>`
                                    )
                                    .join('')}

                            </ul>

                        `;

                    }


                    if (
                        Array.isArray(
                            section.topics
                        )
                    ) {

                        section.topics
                            .forEach(topic => {

                                html += `

                                    <details
                                        class="flexi-syllabus-topic"
                                        open
                                    >

                                        <summary
                                            class="flexi-syllabus-topic-header"
                                        >
                                            ${topic.title ||
                                            topic.topics ||
                                            'Topic'}
                                        </summary>

                                        <div
                                            class="flexi-syllabus-topic-body"
                                        >

                                `;


                                if (topic.notes) {

                                    html += `
                                        <p>
                                            ${topic.notes}
                                        </p>
                                    `;

                                }


                                if (
                                    topic.details &&
                                    topic.details.length
                                ) {

                                    html += `

                                        <ul class="flexi-syllabus-list">

                                            ${topic.details
                                                .map(
                                                    detail =>
                                                        `<li>${detail}</li>`
                                                )
                                                .join('')}

                                        </ul>

                                    `;

                                }


                                html += `

                                        </div>

                                    </details>

                                `;

                            });

                    }

                });


            } else if (
                typeof detailed === 'object'
            ) {

                Object.keys(detailed)
                    .forEach(partKey => {

                        const partTitle =
                            partKey
                                .replace(
                                    /_/g,
                                    ' '
                                )
                                .toUpperCase();


                        html += `

                            <h4
                                style="
                                    color:var(--flexi-primary,#003366);
                                    margin-top:1.2rem;
                                    border-bottom:1px solid #E2E8F0;
                                    padding-bottom:0.25rem;
                                "
                            >
                                ${escapeHTML(
                                    partTitle
                                )}
                            </h4>

                        `;


                        const topicList =
                            detailed[partKey];


                        if (
                            Array.isArray(topicList)
                        ) {

                            topicList
                                .forEach(item => {

                                    html += `

                                        <div
                                            class="flexi-syllabus-topic"
                                            style="padding:0.75rem;"
                                        >

                                            <div
                                                style="
                                                    font-weight:bold;
                                                    color:var(--flexi-primary,#003366);
                                                "
                                            >
                                                ${
                                                    item.topics ||
                                                    item.content ||
                                                    item.title ||
                                                    ''
                                                }
                                            </div>

                                    `;


                                    if (item.notes) {

                                        html += `

                                            <div
                                                style="
                                                    font-size:0.9rem;
                                                    color:#475569;
                                                    margin-top:0.25rem;
                                                "
                                            >
                                                <strong>
                                                    Notes:
                                                </strong>

                                                ${item.notes}

                                            </div>

                                        `;

                                    }


                                    html += `</div>`;

                                });

                        }

                    });

            }


            html += `</div>`;

        }


        /* -----------------------------------------------------
           Reading List
           ----------------------------------------------------- */

        const reading =
            data.reading_list ||
            data.recommended_texts ||
            data.prescribed_texts;


        if (
            Array.isArray(reading) &&
            reading.length
        ) {

            html += `

                <div class="flexi-syllabus-section-card">

                    <h3 class="flexi-syllabus-section-title">
                        Recommended Reading List
                    </h3>

                    <ol class="flexi-syllabus-list">

                        ${reading
                            .map(
                                book =>
                                    `<li>${book}</li>`
                            )
                            .join('')}

                    </ol>

                </div>

            `;

        }


        app.innerHTML = html;

    }


    /* =========================================================
       BOOT
       ========================================================= */

    document.addEventListener(
        'DOMContentLoaded',
        () => {

            const initialExam =
                urlExam === 'waec'
                    ? 'waec'
                    : 'jamb';

            switchPortal(
                initialExam
            );

        }
    );
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
