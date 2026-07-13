/**
 * drolung-branch — header JS.
 *
 * Single sticky nav: just the hamburger toggle + fade-up observer.
 * The compact-header scroll logic from drolung-base/base.js is intentionally
 * not included — the branch now uses a single fixed nav like DUK.
 */
(function () {
	'use strict';

	/* Fade-up reveal */
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
		document.querySelectorAll('.fade-up').forEach(function (el) { el.classList.add('visible'); });
	}

	/* Mobile hamburger toggle */
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

	/* Language switcher dropdown */
	var langSwitch = document.querySelector('.lang-switch');
	var langBtn = langSwitch && langSwitch.querySelector('.lang-switch__btn');
	if (langSwitch && langBtn) {
		function closeLangSwitch() {
			langSwitch.classList.remove('open');
			langBtn.setAttribute('aria-expanded', 'false');
		}
		langBtn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = langSwitch.classList.toggle('open');
			langBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.addEventListener('click', function (e) {
			if (!langSwitch.contains(e.target)) { closeLangSwitch(); }
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') { closeLangSwitch(); langBtn.focus(); }
		});
	}
})();
