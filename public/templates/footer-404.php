<?php
require_once __DIR__ . '/../config/database.php'; 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<!---- FOOTER SECTION ---->
<footer>
    <div class="footer__socials">
        <a href="https://www.facebook.com" target="_blank" class="social__link">
            <i class="bi bi-facebook"></i> 
        </a>
        <a href="https://www.twitter.com" target="_blank" class="social__link">
            <i class="bi bi-twitter"></i> 
        </a>
        <a href="https://www.instagram.com" target="_blank" class="social__link">
            <i class="bi bi-instagram"></i> 
        </a>
        <a href="https://www.linkedin.com" target="_blank" class="social__link">
            <i class="bi bi-linkedin"></i> 
        </a>
        <a href="https://www.youtube.com" target="_blank" class="social__link">
            <i class="bi bi-youtube"></i> 
        </a>
    </div>

    <div class="container footer__container">
        <article>
            <h4>Support</h4>
            <ul>
                <li><a href="">Online Support</a></li>
                <li><a href="">Social Support</a></li>
                <li><a href="">Call Number</a></li>
                <li><a href="">Attention</a></li>
                <li><a href="">Location</a></li>
                <li><a href="">Emails</a></li>
            </ul>
        </article>
        <article>
            <h4>Permalinks</h4>
            <ul>
                <li><a href="">Services</a></li>
                <li><a href="">Contact</a></li>
                <li><a href="">About</a></li>
                <li><a href="">Home</a></li>
                <li><a href="">News</a></li>
            </ul>
        </article>
        <article>
            <h4>Blog</h4>
            <ul>
                <li><a href="">Safety</a></li>
                <li><a href="">Repair</a></li>
                <li><a href="">Recent</a></li>
                <li><a href="">Popular</a></li>
            </ul>
        </article>
    </div>
    <div class="footer__copyright">
        <p class="mt-3 mb-3 text-muted">&copy;2025 BLOG. All rights reserved. <?php echo date("Y"); ?></p>
    </div>
</footer>

<!------ BOOTSTRAP SCRIPTS ----->
<script src="<?= BOOTSTRAP_JS ?>"></script>

<!-- Custom JS -->
<script src="<?= JS_PATH ?>/main.js"></script>
</body>
</html>