<?php
/**
 * Flexi Educational Consult - Shared Header Component
 * 
 * Provides the consistent Flexi website header with:
 * - Logo and brand name
 * - Main navigation
 * - Mobile hamburger menu
 * - Active page highlighting (optional, uses $currentPage)
 * 
 * Variables (optional):
 *  - $currentPage (string): Current page identifier for active highlighting
 * 
 * Usage:
 *  <?php $currentPage = 'home'; include __DIR__ . '/includes/header.php'; ?>
 */

if (!isset($currentPage)) {
    $currentPage = '';
}

// Determine active page
function isActive($page, $currentPage) {
    return ($currentPage === $page) ? ' active' : '';
}
?>

<header class="flexi-header">
    <div class="flexi-header-content">
        <div class="flexi-header-left">
            <a href="/" class="flexi-logo-link">
                <img src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg" 
                     alt="Flexi Educational Consult Official Logo" 
                     class="flexi-logo-img">
                <span class="flexi-brand-name">Flexi Educational Consult</span>
            </a>
        </div>

        <div class="flexi-menu-container">
            <button class="flexi-menu-btn" onclick="toggleFlexiMenu()" aria-label="Toggle Navigation Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="flexi-menu" id="flexiMenu">
                <a href="/index.php" class="flexi-menu-link<?php echo isActive('home', $currentPage); ?>">Home</a>
                <a href="/syllabus.php" class="flexi-menu-link<?php echo isActive('syllabus', $currentPage); ?>">JAMB/WAEC Syllabus</a>
                <a href="/brochure.php" class="flexi-menu-link<?php echo isActive('brochure', $currentPage); ?>">JAMB Brochure</a>
                <a href="/cbt.php" class="flexi-menu-link<?php echo isActive('cbt', $currentPage); ?>">CBT Simulator</a>
                <a href="/pdf.php" class="flexi-menu-link<?php echo isActive('pdf', $currentPage); ?>">Past Questions & PDFs</a>
                <a href="/groups.html" class="flexi-menu-link<?php echo isActive('groups', $currentPage); ?>">Classroom</a>
                <a href="/purchase.html" class="flexi-menu-link<?php echo isActive('purchase', $currentPage); ?>">Purchase Scratch Cards</a>
                <a href="/profile.php" class="flexi-menu-link<?php echo isActive('profile', $currentPage); ?>">User Profile</a>
                <a href="#" id="flexiAuthBtn" class="flexi-menu-link flexi-auth-link">Login</a>
            </div>
        </div>
    </div>
</header>

<script>
window.toggleFlexiMenu = function() {
    const menu = document.getElementById('flexiMenu');
    if (menu) {
        menu.classList.toggle('open');
    }
};

// Close menu when clicking outside
document.addEventListener('click', function(e) {
    const menu = document.getElementById('flexiMenu');
    const btn = document.querySelector('.flexi-menu-btn');
    if (menu && btn && menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove('open');
    }
});
</script>
