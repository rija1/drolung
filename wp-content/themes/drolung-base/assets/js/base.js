/**
 * drolung-base — shared front-end JS.
 *
 * 1. Cross-fade compact header on scroll (toggles .scrolled on <body>).
 * 2. Clone the primary nav into the compact bar so the active state and link
 *    list stay in sync without duplicating markup in PHP.
 * 3. Fade-up reveal animation via IntersectionObserver.
 */

(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
	}

	ready(function () {
		/* ---- 1 & 2. Compact header bootstrap ---- */
		var sourceNav = document.querySelector('.site-header .main-nav');
		var targetNav = document.querySelector('.site-header-compact .compact-nav');
		if (sourceNav && targetNav) {
			targetNav.innerHTML = sourceNav.innerHTML;
		}

		var onScroll = function () {
			document.body.classList.toggle('scrolled', window.scrollY > 80);
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();

		/* ---- 3. Fade-up observer ---- */
		var fades = document.querySelectorAll('.fade-up');
		if (fades.length && 'IntersectionObserver' in window) {
			var obs = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('visible');
						obs.unobserve(entry.target);
					}
				});
			}, { threshold: 0.12 });
			fades.forEach(function (el) { obs.observe(el); });
		} else {
			/* Fallback: just reveal everything immediately. */
			fades.forEach(function (el) { el.classList.add('visible'); });
		}
	});
})();
