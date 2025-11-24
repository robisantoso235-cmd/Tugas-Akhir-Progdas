console.log('script.js loaded');

document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.querySelector(".hamburger");
    const navMenu = document.querySelector(".nav-menu");

    if (!hamburger) {
        console.error("Tombol menu tidak ditemukan!");
        return;
    }
    if (!navMenu) {
        console.error("Elemen .nav-menu tidak ditemukan!");
        return;
    }

    // ARIA + keyboard
    hamburger.setAttribute('role', 'button');
    hamburger.setAttribute('tabindex', '0');
    hamburger.setAttribute('aria-expanded', 'false');

    const toggleMenu = (e) => {
        e && e.stopPropagation();
        const isOpen = hamburger.classList.toggle("open");
        navMenu.classList.toggle("active", isOpen);
        navMenu.classList.remove("menu-active");
        hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    hamburger.addEventListener("click", toggleMenu);

    // keyboard (Enter / Space)
    hamburger.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            toggleMenu(e);
        }
    });

    // Close menu when clicking on a link
    navMenu.querySelectorAll("a").forEach(a => {
        a.addEventListener("click", () => {
            navMenu.classList.remove("active", "menu-active");
            hamburger.classList.remove("open");
            hamburger.setAttribute('aria-expanded', 'false');
        });
    });

    // Close menu when clicking outside
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".navbar")) {
            navMenu.classList.remove("active", "menu-active");
            hamburger.classList.remove("open");
            hamburger.setAttribute('aria-expanded', 'false');
        }
    });

    // Close menu on scroll
    window.addEventListener("scroll", () => {
        navMenu.classList.remove("active", "menu-active");
        hamburger.classList.remove("open");
        hamburger.setAttribute('aria-expanded', 'false');
    });

    // Fade-in on scroll for services section
    const layanan = document.querySelector('.layanan');
    const boxes = document.querySelectorAll('.layanan-box .box');

    if (layanan) {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        layanan.classList.add('show');
                        boxes.forEach((b, i) => {
                            b.style.setProperty('--delay', `${i * 120}ms`);
                            setTimeout(() => b.classList.add('show'), i * 120);
                        });
                    } else {
                        const rev = Array.from(boxes).reverse();
                        rev.forEach((b, i) => {
                            setTimeout(() => b.classList.remove('show'), i * 80);
                        });
                        const totalHideDelay = rev.length * 80 + 60;
                        setTimeout(() => layanan.classList.remove('show'), totalHideDelay);
                    }
                });
            }, { threshold: 0.15 });
            observer.observe(layanan);
        } else {
            // Fallback for browsers that don't support IntersectionObserver
            layanan.classList.add('show');
            boxes.forEach((b, i) => {
                b.style.setProperty('--delay', `${i * 120}ms`);
                setTimeout(() => b.classList.add('show'), i * 120);
            });
        }
    }

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Contact modal
    const kontakBtn = document.getElementById('kontak-btn');
    const kontakModal = document.getElementById('kontak-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');

    if (kontakBtn && kontakModal && closeModalBtn) {
        kontakBtn.addEventListener('click', (e) => {
            e.preventDefault();
            kontakModal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            kontakModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === kontakModal) {
                kontakModal.style.display = 'none';
            }
        });
    }
});
