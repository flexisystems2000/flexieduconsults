<?php

$pageTitle = 'JAMB Brochure | Flexi Educational Consult';

$pageDescription = 'Access the JAMB Brochure to explore university, polytechnic, college and other institution courses, faculties and admission requirements.';

$pageKeywords = 'JAMB Brochure, JAMB courses, UTME courses, university courses, polytechnic courses, NCE courses, Flexi Educational Consult';

$pageImage = 'https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg';

$pageCanonical = 'https://www.flexieduconsult.com.ng/brochure.php';

$includeToastify = false;
$includeAdsense = false;

$currentPage = 'brochure.php';

require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';

?>

<style>
    /* =========================================================
       FLEXI BROCHURE PAGE
       Page-specific styles only
       ========================================================= */

    .flexi-brochure-page {
        background: #f8f6f6;
        color: #1f2937;
        min-height: calc(100vh - 80px);
        padding: 1.25rem 0 3rem;
    }

    .flexi-brochure-container {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* Back Navigation */

    .flexi-brochure-nav {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .flexi-brochure-back {
        width: 40px;
        height: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 50%;

        color: #1f2937;
        text-decoration: none;
        font-size: 1.2rem;

        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);

        transition:
            transform 0.15s ease,
            border-color 0.2s ease;
    }

    .flexi-brochure-back:hover {
        border-color: var(--flexi-secondary, #2e8b57);
        transform: translateX(-2px);
    }

    .flexi-brochure-nav-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* Selectors */

    .flexi-brochure-select-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
    }

    .flexi-brochure-select-box {
        width: 100%;
    }

    .flexi-brochure-select-box label {
        display: block;

        margin-bottom: 5px;

        font-size: 0.8rem;
        font-weight: 700;

        color: #6b7280;

        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .flexi-brochure-select-box select {
        width: 100%;

        padding: 12px 16px;

        border-radius: 12px;
        border: 1px solid #e5e7eb;

        background: #ffffff;
        color: #1f2937;

        font-size: 0.95rem;
        font-weight: 600;

        outline: none;

        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);

        cursor: pointer;

        transition: border-color 0.2s ease;
    }

    .flexi-brochure-select-box select:focus {
        border-color: var(--flexi-secondary, #2e8b57);
    }

    /* Search */

    .flexi-brochure-search {
        position: relative;
        margin-bottom: 18px;
    }

    .flexi-brochure-search input {
        width: 100%;

        padding: 14px 16px 14px 46px;

        border-radius: 12px;
        border: 1px solid #e5e7eb;

        background: #ffffff;
        color: #1f2937;

        font-size: 0.95rem;

        outline: none;

        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);

        transition: border-color 0.2s ease;
    }

    .flexi-brochure-search input:focus {
        border-color: var(--flexi-secondary, #2e8b57);
    }

    .flexi-brochure-search-icon {
        position: absolute;

        left: 16px;
        top: 50%;

        transform: translateY(-50%);

        color: #6b7280;
        font-size: 1.1rem;

        pointer-events: none;
    }

    /* Section Heading */

    .flexi-brochure-section-title {
        font-size: 1rem;
        font-weight: 700;

        color: #1f2937;

        margin-bottom: 14px;
    }

    /* Course List */

    .flexi-brochure-course-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .flexi-brochure-course-card {
        display: flex;
        justify-content: space-between;
        align-items: center;

        width: 100%;

        padding: 16px 20px;

        background: #ffffff;

        border: 1px solid #e5e7eb;
        border-radius: 12px;

        text-decoration: none;

        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);

        transition:
            transform 0.1s ease,
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .flexi-brochure-course-card:hover {
        border-color: var(--flexi-secondary, #2e8b57);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .flexi-brochure-course-card:active {
        transform: scale(0.98);
    }

    .flexi-brochure-course-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
    }

    .flexi-brochure-arrow {
        color: #6b7280;
        font-size: 1rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-left: 12px;
    }

    /* Empty State */

    .flexi-brochure-no-results {
        display: none;

        padding: 24px 16px;

        text-align: center;

        color: #6b7280;

        font-size: 0.9rem;

        background: #ffffff;

        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }

    /* Mobile */

    @media (max-width: 600px) {

        .flexi-brochure-page {
            padding-top: 1rem;
        }

        .flexi-brochure-container {
            padding-left: 12px;
            padding-right: 12px;
        }

        .flexi-brochure-course-card {
            padding: 15px 16px;
        }

        .flexi-brochure-course-name {
            font-size: 0.9rem;
        }

    }
</style>


<main class="flexi-brochure-page">

    <div class="flexi-brochure-container">

        <!-- Back Navigation -->

        <div class="flexi-brochure-nav">

            <a
                class="flexi-brochure-back"
                href="/index.php"
                aria-label="Back to main page"
            >
                &#8592;
            </a>

            <div class="flexi-brochure-nav-title">
                Back to Main
            </div>

        </div>


        <!-- Institution & Faculty Selectors -->

        <div class="flexi-brochure-select-group">

            <!-- Institution -->

            <div class="flexi-brochure-select-box">

                <label for="institutionSelect">
                    Select Institution Type
                </label>

                <select id="institutionSelect">

                    <option value="Universities">
                        Degree (Universities)
                    </option>

                    <option value="Polytechnics">
                        ND / HND (Polytechnics & Monotechnics)
                    </option>

                    <option value="Colleges">
                        NCE (Colleges of Education)
                    </option>

                    <option value="IEIs">
                        Innovation Enterprise Institutions (IEIs)
                    </option>

                </select>

            </div>


            <!-- Faculty -->

            <div class="flexi-brochure-select-box">

                <label for="facultySelect">
                    Select Faculty / Category
                </label>

                <select id="facultySelect">

                    <option value="Administration">
                        Administration
                    </option>

                    <option value="Agriculture">
                        Agriculture
                    </option>

                    <option value="Engineering">
                        Engineering, Environment & Technology
                    </option>

                    <option value="Education">
                        Education
                    </option>

                    <option value="Law">
                        Law
                    </option>

                    <option value="Medical">
                        Medical, Pharmaceutical & Health Services
                    </option>

                    <option value="Sciences">
                        Sciences
                    </option>

                    <option value="SocialSciences">
                        Social Sciences
                    </option>

                </select>

            </div>

        </div>


        <!-- Search -->

        <div class="flexi-brochure-search">

            <span class="flexi-brochure-search-icon">
                &#128269;
            </span>

            <input
                type="text"
                id="searchInput"
                placeholder="Search Course..."
                autocomplete="off"
            />

        </div>


        <!-- Courses -->

        <div class="flexi-brochure-section-title">
            Select a Course:
        </div>

        <div
            class="flexi-brochure-course-list"
            id="courseListContainer"
        ></div>

        <div
            class="flexi-brochure-no-results"
            id="noResultsMsg"
        >
            No courses found matching your search.
        </div>

    </div>

</main>


<script>
    /* =========================================================
       FLEXI BROCHURE DATA
       ========================================================= */

    const brochureData = {

        "Administration": [

            "Accounting",
            "Actuarial Science",
            "Banking and Finance",
            "Business Administration",
            "Business Management",
            "Cooperative and Rural Development",
            "Entrepreneurship",
            "Finance",
            "Human Resource Management",
            "Industrial Relations and Personnel Management",
            "Insurance",
            "International Relations",
            "Local Government and Development Studies",
            "Management",
            "Marketing",
            "Office and Information Management",
            "Public Administration",
            "Purchasing and Supply",
            "Secretarial Administration",
            "Taxation",
            "Transport Management"

        ],


        "Agriculture": [

            "Agricultural Economics",
            "Agricultural Economics and Extension",
            "Agricultural Extension",
            "Agricultural Extension and Rural Development",
            "Agronomy",
            "Animal Science",
            "Crop Science",
            "Crop Science and Horticulture",
            "Crop Protection",
            "Crop Protection and Environmental Biology",
            "Soil Science",
            "Soil Resources Management",
            "Food Science and Technology",
            "Fisheries",
            "Fisheries and Aquaculture",
            "Forestry",
            "Forestry and Wildlife Management",
            "Forestry and Environmental Management",
            "Home Science and Management",
            "Horticulture",
            "Water Resources and Agro-Meteorology",
            "Agricultural Engineering",
            "Agricultural Biochemistry",
            "Agricultural Technology",
            "Plant Science",
            "Plant Breeding and Seed Science"

        ],


        "Engineering": [

            "Agricultural Engineering",
            "Architecture",
            "Building",
            "Chemical Engineering",
            "Civil Engineering",
            "Computer Engineering",
            "Electrical Engineering",
            "Electrical and Electronics Engineering",
            "Electronics Engineering",
            "Environmental Management",
            "Estate Management",
            "Geomatics",
            "Industrial and Production Engineering",
            "Industrial Design",
            "Information and Communication Engineering",
            "Mechanical Engineering",
            "Mechatronics Engineering",
            "Metallurgical and Materials Engineering",
            "Mining Engineering",
            "Petroleum Engineering",
            "Polymer and Textile Engineering",
            "Quantity Surveying",
            "Surveying and Geoinformatics",
            "Telecommunication Engineering",
            "Urban and Regional Planning",
            "Water Resources Engineering",
            "Wood Products Engineering"

        ],


        "Education": [

            "Adult Education",
            "Adult and Continuing Education",
            "Arts Education",
            "Business Education",
            "Curriculum and Instruction",
            "Early Childhood Education",
            "Educational Administration",
            "Educational Foundations",
            "Educational Management",
            "Educational Psychology",
            "Educational Technology",
            "Guidance and Counselling",
            "Health Education",
            "Human Kinetics",
            "Library and Information Science",
            "Primary Education Studies",
            "Science Education",
            "Social Science Education",
            "Special Education",
            "Teacher Education",
            "Technical Education",
            "Vocational Education",
            "Agricultural Education",
            "Biology Education",
            "Chemistry Education",
            "Christian Religious Studies Education",
            "Computer Education",
            "Economics Education",
            "English Education",
            "French Education",
            "Geography Education",
            "History Education",
            "Integrated Science Education",
            "Islamic Studies Education",
            "Mathematics Education",
            "Music Education",
            "Physical and Health Education",
            "Physics Education",
            "Political Science Education",
            "Social Studies Education",
            "Yoruba Education",
            "Igbo Education",
            "Hausa Education",
            "Fine and Applied Arts Education"

        ],


        "Law": [

            "Common Law",
            "Civil Law",
            "Commercial and Industrial Law",
            "International Law and Jurisprudence",
            "Private and Property Law",
            "Public Law",
            "Islamic Law",
            "Customary Law",
            "Jurisprudence and International Law",
            "Business Law",
            "Constitutional Law",
            "Criminal Law",
            "Law"

        ],


        "Medical": [

            "Anatomy",
            "Audiology",
            "Dentistry",
            "Dental Surgery",
            "Dental Technology",
            "Dental Therapy",
            "Environmental Health Science",
            "Epidemiology",
            "Human Anatomy",
            "Human Nutrition and Dietetics",
            "Medical Biochemistry",
            "Medical Laboratory Science",
            "Medical Rehabilitation",
            "Medicine and Surgery",
            "Microbiology and Parasitology",
            "Nursing",
            "Nursing Science",
            "Nutrition and Dietetics",
            "Occupational Therapy",
            "Optometry",
            "Pharmaceutical Chemistry",
            "Pharmaceutical Technology",
            "Pharmacognosy",
            "Pharmacology",
            "Pharmacy",
            "Physiology",
            "Physiotherapy",
            "Prosthetics and Orthotics",
            "Public Health",
            "Radiography",
            "Radiography and Radiation Science",
            "Speech and Language Therapy",
            "Veterinary Anatomy",
            "Veterinary Medicine",
            "Veterinary Physiology",
            "Veterinary Public Health",
            "Veterinary Surgery"

        ],


        "Sciences": [

            "Actuarial Science",
            "Applied Biology",
            "Applied Biophysics",
            "Applied Geology",
            "Applied Geophysics",
            "Biochemistry",
            "Biological Science",
            "Biology",
            "Biotechnology",
            "Botany",
            "Cell Biology and Genetics",
            "Chemistry",
            "Computer Science",
            "Cyber Security",
            "Data Science",
            "Ecology",
            "Environmental Biology",
            "Environmental Science",
            "Forensic Science",
            "Geography",
            "Geology",
            "Geophysics",
            "Industrial Chemistry",
            "Industrial Mathematics",
            "Industrial Physics",
            "Information Technology",
            "Marine Biology",
            "Mathematics",
            "Microbiology",
            "Physics",
            "Plant Science",
            "Pure and Applied Biology",
            "Pure and Applied Chemistry",
            "Pure and Applied Mathematics",
            "Statistics",
            "Zoology"

        ],


        "SocialSciences": [

            "Anthropology",
            "Criminology and Security Studies",
            "Demography and Social Statistics",
            "Economics",
            "Geography",
            "Geography and Environmental Management",
            "International Relations",
            "Mass Communication",
            "Peace and Conflict Studies",
            "Political Science",
            "Psychology",
            "Public Administration",
            "Sociology",
            "Sociology and Anthropology",
            "Social Work",
            "Urban and Regional Planning",
            "Development Studies",
            "Intelligence and Security Studies",
            "Population Studies",
            "Gender Studies"

        ]

    };


    /* =========================================================
       DOM REFERENCES
       ========================================================= */

    const institutionSelect =
        document.getElementById(
            'institutionSelect'
        );

    const facultySelect =
        document.getElementById(
            'facultySelect'
        );

    const courseListContainer =
        document.getElementById(
            'courseListContainer'
        );

    const searchInput =
        document.getElementById(
            'searchInput'
        );

    const noResultsMsg =
        document.getElementById(
            'noResultsMsg'
        );


    /* =========================================================
       RENDER COURSES
       ========================================================= */

    function renderCourses() {

        const facultyKey =
            facultySelect.value;

        const institutionKey =
            institutionSelect.value;

        const courses =
            brochureData[facultyKey] || [];


        courseListContainer.innerHTML = '';


        courses.forEach(course => {

            const link =
                document.createElement('a');


            link.className =
                'flexi-brochure-course-card';


            link.href =
                `requirements.html?institution=${encodeURIComponent(institutionKey)}&faculty=${encodeURIComponent(facultyKey)}&course=${encodeURIComponent(course)}`;


            const courseName =
                document.createElement('span');

            courseName.className =
                'flexi-brochure-course-name';

            courseName.textContent =
                course;


            const arrow =
                document.createElement('span');

            arrow.className =
                'flexi-brochure-arrow';

            arrow.innerHTML =
                '&#10095;';


            link.appendChild(courseName);
            link.appendChild(arrow);


            courseListContainer.appendChild(
                link
            );

        });


        filterCourses();

    }


    /* =========================================================
       LIVE SEARCH
       ========================================================= */

    function filterCourses() {

        const term =
            searchInput.value
                .toLowerCase()
                .trim();


        const cards =
            courseListContainer
                .getElementsByClassName(
                    'flexi-brochure-course-card'
                );


        let visibleCount = 0;


        Array.from(cards).forEach(card => {

            const text =
                card.textContent
                    .toLowerCase();


            if (text.includes(term)) {

                card.style.display =
                    'flex';

                visibleCount++;

            } else {

                card.style.display =
                    'none';

            }

        });


        noResultsMsg.style.display =
            visibleCount === 0
                ? 'block'
                : 'none';

    }


    /* =========================================================
       URL PARAMETERS
       ========================================================= */

    const urlParams =
        new URLSearchParams(
            window.location.search
        );


    const institutionParam =
        urlParams.get(
            'institution'
        );


    const facultyParam =
        urlParams.get(
            'faculty'
        );


    if (institutionParam) {

        const matchingInstitution =
            Array.from(
                institutionSelect.options
            ).find(
                option =>
                    option.value.toLowerCase() ===
                    institutionParam.toLowerCase()
            );


        if (matchingInstitution) {

            institutionSelect.value =
                matchingInstitution.value;

        }

    }


    if (
        facultyParam &&
        brochureData[facultyParam]
    ) {

        facultySelect.value =
            facultyParam;

    }


    /* =========================================================
       EVENTS
       ========================================================= */

    institutionSelect.addEventListener(
        'change',
        renderCourses
    );


    facultySelect.addEventListener(
        'change',
        renderCourses
    );


    searchInput.addEventListener(
        'input',
        filterCourses
    );


    /* =========================================================
       INITIAL RENDER
       ========================================================= */

    renderCourses();

</script>


<?php

require_once __DIR__ . '/includes/footer.php';

?>
