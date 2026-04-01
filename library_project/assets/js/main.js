/**
 * LibManage v8.0 — Unified Elite SaaS Script
 * Handles Transitions, Theme Switching, and Page Interactivity
 */

document.addEventListener('DOMContentLoaded', () => {
    const html = document.documentElement;
    const body = document.body;
    
    // ── Theme Management ──
    const initTheme = () => {
        const savedTheme = localStorage.getItem('lib-theme') || 'dark';
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcons(savedTheme);
    };

    const updateThemeIcons = (theme) => {
        const icons = document.querySelectorAll('.theme-icon');
        icons.forEach(icon => {
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });
    };

    window.toggleTheme = () => {
        const current = html.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('lib-theme', next);
        updateThemeIcons(next);
        console.log(`Theme shifted to: ${next}`);
    };

    initTheme();

    // ── Global Preloader ──
    const preloader = document.getElementById('preloader');
    const loaderIcon = document.getElementById('loader-icon');
    
    window.addEventListener('load', () => {
        if (preloader) preloader.classList.add('hidden');
    });

    // ── Transition Out Engine ──
    const transitLinks = document.querySelectorAll('a.sidebar-link, a.btn-v8, a.nav-link, a.btn-primary, a.btn-secondary, a.btn');
    transitLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const destination = this.href;
            if (destination && !destination.includes('#') && !destination.startsWith('javascript') && !this.target) {
                e.preventDefault();
                const targetIcon = this.getAttribute('data-icon') || 'fa-spinner';
                if (loaderIcon) loaderIcon.className = `fas ${targetIcon} fa-spin-pulse`;
                if (preloader) preloader.classList.remove('hidden');
                setTimeout(() => { window.location.href = destination; }, 300);
            }
        });
    });

    // ── Form Submission Cinematic ──
    const transitForms = document.querySelectorAll('form');
    transitForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Avoid double interception if already processing
            if (this.getAttribute('data-submitting')) return;
            
            e.preventDefault();
            this.setAttribute('data-submitting', 'true');
            
            if (loaderIcon) loaderIcon.className = 'fas fa-spinner fa-spin-pulse';
            if (preloader) preloader.classList.remove('hidden');
            
            setTimeout(() => {
                this.submit();
            }, 300);
        });
    });

    // ── Scroll Progress Intelligence ──
    const scrollBar = document.getElementById('scroll-progress');
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        if (scrollBar) scrollBar.style.width = scrolled + "%";
    });

    // ── Header Scroll Effect ──
    const header = document.querySelector('.v6-header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        });
    }

    // ── Stat Counters ──
    const counters = document.querySelectorAll('.counter');
    const animateCounters = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const speed = 200;
            const inc = target / speed;
            const update = () => {
                const cur = +counter.innerText;
                if (cur < target) {
                    counter.innerText = Math.ceil(cur + inc);
                    setTimeout(update, 1);
                } else {
                    counter.innerText = target;
                }
            };
            update();
        });
    };

    const statsSec = document.getElementById('stats');
    if (statsSec) {
        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) animateCounters();
        }, { threshold: 0.5 });
        observer.observe(statsSec);
    }
});
