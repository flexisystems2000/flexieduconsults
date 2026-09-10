<?php
/**
 * Flexi Educational Consult - Shared Header Component
 *
 * Provides the common Flexi website navigation.
 *
 * Optional variable:
 *   $currentPage
 *
 * Example:
 *
 * <?php
 * $currentPage = 'home';
 * include __DIR__ . '/includes/header.php';
 * ?>
 */

// ---------------------------------------------------------
// Safe current-page default
// ---------------------------------------------------------

if (!isset($currentPage)) {
    $currentPage = '';
}

// ---------------------------------------------------------
// Unique helper function
//
// IMPORTANT:
// Do NOT use a generic function name such as isActive()
// because another page may already define that function.
// ---------------------------------------------------------

if (!function_exists('flexiHeaderIsActive')) {
    function flexiHeaderIsActive($page, $currentPage)
    {
        return ($currentPage === $page) ? ' active' : '';
    }
}
?>

<header class="flexi-header">

    <div class="flexi-header-content">

        <!-- Brand -->
        <div class="flexi-header-left">

            <a
                href="/"
                class="flexi-logo-link"
                aria-label="Flexi Educational Consult Home"
            >

                <img
                    src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg"
                    alt="Flexi Educational Consult Official Logo"
                    class="flexi-logo-img"
                >

                <span class="flexi-brand-name">
                    Flexi Educational Consult
                </span>

            </a>

        </div>

        <!-- Navigation -->
        <div class="flexi-menu-container">

            <button
                type="button"
                class="flexi-menu-btn"
                id="flexiMenuBtn"
                aria-label="Toggle Navigation Menu"
                aria-controls="flexiMenu"
                aria-expanded="false"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav
                class="flexi-menu"
                id="flexiMenu"
                aria-label="Main Navigation"
            >

                <a
                    href="/index.php"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('home', $currentPage); ?>"
                >
                    Home
                </a>

                <a
                    href="/syllabus.php"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('syllabus', $currentPage); ?>"
                >
                    JAMB/WAEC Syllabus
                </a>

                <a
                    href="/brochure.php"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('brochure', $currentPage); ?>"
                >
                    JAMB Brochure
                </a>

                <a
                    href="/cbt.php"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('cbt', $currentPage); ?>"
                >
                    CBT Simulator
                </a>

                <a
                    href="/pdf.php"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('pdf', $currentPage); ?>"
                >
                    Past Questions &amp; PDFs
                </a>

                <a
                    href="/groups.html"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('groups', $currentPage); ?>"
                >
                    Classroom
                </a>

                <a
                    href="/purchase.html"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('purchase', $currentPage); ?>"
                >
                    Purchase Scratch Cards
                </a>

                <a
                    href="/profile.php"
                    class="flexi-menu-link<?php echo flexiHeaderIsActive('profile', $currentPage); ?>"
                >
                    User Profile
                </a>

                <!--
                    Login intentionally points to the actual login page.
                    JavaScript can still intercept this link later if the
                    authentication system requires dynamic behaviour.
                -->
                <a
                    href="/login.php"
                    id="flexiAuthBtn"
                    class="flexi-menu-link flexi-auth-link<?php echo flexiHeaderIsActive('login', $currentPage); ?>"
                >
                    Login
                </a>

            </nav>

        </div>

    </div>

</header>

<script>
(function () {

    const menu = document.getElementById('flexiMenu');
    const menuButton = document.getElementById('flexiMenuBtn');

    if (!menu || !menuButton) {
        return;
    }

    function closeFlexiMenu() {
        menu.classList.remove('open');
        menuButton.setAttribute('aria-expanded', 'false');
    }

    function toggleFlexiMenu() {

        const isOpen = menu.classList.toggle('open');

        menuButton.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );
    }

    menuButton.addEventListener('click', function (event) {

        event.stopPropagation();

        toggleFlexiMenu();

    });

    document.addEventListener('click', function (event) {

        if (
            menu.classList.contains('open') &&
            !menu.contains(event.target) &&
            !menuButton.contains(event.target)
        ) {
            closeFlexiMenu();
        }

    });

    // Close the menu after selecting a navigation item.
    menu.querySelectorAll('a').forEach(function (link) {

        link.addEventListener('click', function () {
            closeFlexiMenu();
        });

    });

    // Close the mobile menu when Escape is pressed.
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeFlexiMenu();
        }

    });

})();
</script>
