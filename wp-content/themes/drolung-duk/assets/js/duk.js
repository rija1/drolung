/**
 * drolung-duk — front-end JS
 *
 *  1. fade-up reveal on scroll (IntersectionObserver)
 *  2. mobile nav hamburger toggle
 */
(function () {
    /* 1. Fade-up reveal */
    if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-up').forEach(function (el) { obs.observe(el); });
    } else {
        /* No IO support → just show everything */
        document.querySelectorAll('.fade-up').forEach(function (el) { el.classList.add('visible'); });
    }

    /* 2. Mobile nav hamburger */
    var btn = document.querySelector('.nav-hamburger');
    var nav = document.querySelector('.nav-links');
    if (btn && nav) {
        btn.addEventListener('click', function () {
            var open = nav.classList.toggle('open');
            btn.classList.toggle('open', open);
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        nav.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () {
                nav.classList.remove('open');
                btn.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
            });
        });
    }
})();
