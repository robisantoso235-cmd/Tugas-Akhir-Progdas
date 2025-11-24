    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-box">
                <div class="footer-content">
                    <h3>Jastipdies</h3>
                    <p>Solusi Jasa Titip Terpercaya untuk Kebutuhan Anda</p>
                </div>
                <div class="footer-content">
                    <h3>Kontak Kami</h3>
                    <p>Email: info@jastipdies.com</p>
                    <p>Telepon: +62 123 4567 890</p>
                </div>
                <div class="footer-content">
                    <h3>Ikuti Kami</h3>
                    <div class="social-links">
                        <a href="https://www.instagram.com/jastipdies/" target="_blank"><i class="ri-instagram-line"></i></a>
                        <a href="#"><i class="ri-facebook-box-line"></i></a>
                        <a href="#"><i class="ri-twitter-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> Jastipdies. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    </script>
</body>
</html>
