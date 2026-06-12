/* ═══════════════════════════════════════════
   AUTONEXA — app.js
═══════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

    /* ─────────────────────────────────────
       1. AOS — Animate On Scroll
    ───────────────────────────────────── */
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,       // lebih cepat dari 800
            easing: 'ease-out-quart',
            once: false,
            offset: 60,
            delay: 0,
        });
    }


    /* ─────────────────────────────────────
       2. NAVBAR — Shrink on Scroll
    ───────────────────────────────────── */
    const navbar = document.getElementById('navbar');

    if (navbar) {
        const handleNavbarScroll = () => {
            if (window.scrollY > 80) {
                navbar.classList.add('shrink');
            } else {
                navbar.classList.remove('shrink');
            }
        };

        // Run on load in case page is already scrolled
        handleNavbarScroll();
        window.addEventListener('scroll', handleNavbarScroll, { passive: true });
    }


    /* ─────────────────────────────────────
       3. HAMBURGER — Mobile Menu Toggle
    ───────────────────────────────────── */
    const menuToggle = document.getElementById('menuToggle');
    const navMenu    = document.getElementById('navMenu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function () {
            navMenu.classList.toggle('active');
            menuToggle.classList.toggle('open');
            menuToggle.setAttribute(
                'aria-expanded',
                navMenu.classList.contains('active') ? 'true' : 'false'
            );
        });

        // Close menu when a link inside is clicked
        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('open');
                menuToggle.setAttribute('aria-expanded', 'false');
            });
        });

        // Close menu on click outside
        document.addEventListener('click', function (e) {
            if (!navbar.contains(e.target)) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }


    /* ─────────────────────────────────────
       4. SMOOTH SCROLL — Anchor links (#)
    ───────────────────────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                const navHeight = navbar ? navbar.offsetHeight + 24 : 80;
                const top = target.getBoundingClientRect().top + window.scrollY - navHeight;

                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });


    /* ─────────────────────────────────────
       5. ACTIVE NAV LINK — Scroll Spy
    ───────────────────────────────────── */
    const sections   = document.querySelectorAll('section[id]');
    const navLinks   = document.querySelectorAll('.nav-menu a[href^="#"]');

    if (sections.length && navLinks.length) {
        const updateActiveLink = () => {
            const scrollY = window.scrollY;

            sections.forEach(section => {
                const sectionTop    = section.offsetTop - 120;
                const sectionBottom = sectionTop + section.offsetHeight;

                if (scrollY >= sectionTop && scrollY < sectionBottom) {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${section.id}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        };

        window.addEventListener('scroll', updateActiveLink, { passive: true });
    }


    /* ─────────────────────────────────────
       6. FLASH MESSAGE — Auto Dismiss
    ───────────────────────────────────── */
    const flashMessages = document.querySelectorAll('.alert-dismissible');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(msg);
            if (bsAlert) bsAlert.close();
        }, 4000);
    });


    /* ─────────────────────────────────────
       7. TOOLTIP — Bootstrap init
    ───────────────────────────────────── */
    const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipEls.forEach(el => new bootstrap.Tooltip(el, { trigger: 'hover' }));


    /* ─────────────────────────────────────
       8. POPOVER — Bootstrap init
    ───────────────────────────────────── */
    const popoverEls = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverEls.forEach(el => new bootstrap.Popover(el));

    /* ─────────────────────────────────────
       9. BENGKEL GRID — Horizontal Scroll with Arrows
    ───────────────────────────────────── */    
    const bengkelGrid = document.getElementById('bengkelGrid');
    const prevBtn = document.getElementById('bengkelPrev');
    const nextBtn = document.getElementById('bengkelNext');

    if (bengkelGrid && prevBtn && nextBtn) {
        const scrollAmount = () => bengkelGrid.querySelector('.an-bcard')?.offsetWidth + 12 || 300;

        prevBtn.addEventListener('click', () => {
            bengkelGrid.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            bengkelGrid.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        });

        // Disable/enable button berdasarkan posisi scroll
        function updateArrowState() {
            const maxScroll = bengkelGrid.scrollWidth - bengkelGrid.clientWidth;
            prevBtn.disabled = bengkelGrid.scrollLeft <= 5;
            nextBtn.disabled = bengkelGrid.scrollLeft >= maxScroll - 5;
            prevBtn.style.opacity = prevBtn.disabled ? '0.4' : '1';
            nextBtn.style.opacity = nextBtn.disabled ? '0.4' : '1';
        }

        bengkelGrid.addEventListener('scroll', updateArrowState);
        updateArrowState();
    }    

});