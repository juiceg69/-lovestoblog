<?php
require_once __DIR__ . '/../config/database.php'; 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!---- CATEGORIES SECTION ---->
<hr>
<section class="category__buttons"> 
    <div class="container category__buttons-container">
        <a href="#" class="category__button">Art</a>
        <a href="#" class="category__button">Science </a>
        <a href="#" class="category__button">Food</a>
        <a href="#" class="category__button">Travel</a>
        <a href="#" class="category__button">Wild Life</a>
        <a href="#" class="category__button">Tech</a>
    </div>
</section>
<!---- END OF CATEGORIES SECTION ----->

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
            <h4>Permalinks</h4>
            <ul>
                <li class="<?= ($current_url == BASE_URL . '/index') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/index">Home</a>
                </li>
                <li class="<?= ($current_url == BASE_URL . '/blog') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/blog">News</a>
                </li>
                <li class="<?= ($current_url == BASE_URL . '/about') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/about">About</a>
                </li>
                <li class="<?= ($current_url == BASE_URL . '/services') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/services">Services</a>
                </li>
                <li class="<?= ($current_url == BASE_URL . '/contact') ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/contact">Contact</a>
                </li>
            </ul>
        </article>
        <article>
            <h4>Support</h4>
            <ul>
                <li><a href="">Support</a></li>
                <li><a href="">Social</a></li>
                <li><a href="">Attention</a></li>
                <li><a href="">Location</a></li>
                <li><a href="">Emails</a></li>
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
        <p class="mt-3 mb-3 ">&copy;2025 BLOG. All rights reserved. <?php echo date("Y"); ?></p>
    </div>
</footer>

    <!-- SweetAlert2 JS -->
   <script src="<?= SWEETALERT_JS; ?>"></script>

    <!------ BOOTSTRAP SCRIPTS ----->
    <script src="<?= BOOTSTRAP_JS ?>"></script>

    <!-- jQuery (opcional, solo si lo necesitas) -->
    <script src="<?= JQUERY_JS ?>"></script>

    <!-- Custom JS -->
    <script src="<?= JS_PATH ?>/main.js"></script>
</body>
</html>