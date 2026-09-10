<?php
/**
 * Flexi Educational Consult - Shared Footer Component
 * 
 * Provides the consistent Flexi website footer with:
 * - About section
 * - Quick links
 * - Support and community info
 * - Social media links
 * - Copyright
 * 
 * Usage:
 *  <?php include __DIR__ . '/includes/footer.php'; ?>
 */
?>

<footer class="flexi-footer">
    <div class="flexi-footer-grid">
        <div class="flexi-footer-col">
            <p class="flexi-footer-about">
                We empower Nigerian students with admission updates, CBT preparation, tutorials, past questions in PDF, and premium educational support.
            </p>
        </div>

        <div class="flexi-footer-col">
            <h4>Quick Links</h4>
            <ul class="flexi-footer-links">
                <li><a href="/index.php">Home</a></li>
                <li><a href="https://elearning.flexieduconsult.com.ng" target="_blank" rel="noopener">WhatsApp Masterclass (E-Learning)</a></li>
                <li><a href="/syllabus.php">Access the JAMB/WAEC Syllabus</a></li>
                <li><a href="/brochure.php">Access JAMB Brochure</a></li>
                <li><a href="/pdf.php">Past Questions & PDFs</a></li>
                <li><a href="/cbt.php">CBT Simulator</a></li>
                <li><a href="/groups.html">Classroom</a></li>
                <li><a href="/location.html">Tutorial Centres</a></li>
            </ul>
        </div>

        <div class="flexi-footer-col">
            <h4>Support & Community</h4>
            <div class="flexi-contact-group">
                <a href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u" 
                   target="_blank" 
                   rel="noopener"
                   class="flexi-whatsapp-link">
                   Join our WhatsApp Channel
                </a>
            </div>
            <div class="flexi-contact-group">
                <h5>Contact Us</h5>
                <a href="tel:+2349034159839">(+234) 903 415 9839</a>
                <a href="tel:+2347033855206">(+234) 703 385 5206</a>
            </div>
            <div class="flexi-contact-group">
                <h5>Email Us</h5>
                <a href="mailto:support@flexieduconsult.com.ng">support@flexieduconsult.com.ng</a>
                <a href="mailto:info@flexieduconsult.com.ng">info@flexieduconsult.com.ng</a>
            </div>
        </div>

        <div class="flexi-footer-col">
            <h4>Follow Us</h4>
            <ul class="flexi-footer-links">
                <li><a href="https://www.facebook.com/profile.php?id=61589793118693" target="_blank" rel="noopener">Facebook @flexieduconsult</a></li>
                <li><a href="https://instagram.com/flexieduconsult2000" target="_blank" rel="noopener">Instagram @flexieduconsult2000</a></li>
                <li><a href="https://www.tiktok.com/@flexieduconsult" target="_blank" rel="noopener">TikTok @flexieduconsult</a></li>
            </ul>
        </div>
    </div>

    <div class="flexi-footer-bottom">
        &copy; <span id="flexiCurrentYear"></span> Flexi Educational Consult. All Rights Reserved.
    </div>
</footer>

<script>
document.getElementById('flexiCurrentYear').textContent = new Date().getFullYear();
</script>
