/**
 * drolung-base — shared front-end JS.
 *
 * 1. Cross-fade compact header on scroll (toggles .scrolled on <body>).
 * 2. Clone the primary nav into the compact bar so the active state and link
 *    list stay in sync without duplicating markup in PHP.
 * 3. Fade-up reveal animation via IntersectionObserver.
 * 4. Hamburger toggle for the branch header (drolung-branch theme).
 *    Targets .nav-hamburger + .nav-links, adds/removes .open.
 *    CSS for open state lives in branch-nav.css.
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

		/* ---- 4. Hamburger nav toggle (branch header) ---- */
		var hamburger = document.querySelector('.nav-hamburger');
		var navLinks  = document.querySelector('.nav-links');

		if (hamburger && navLinks) {
			/* Open / close on button click */
			hamburger.addEventListener('click', function () {
				var isOpen = hamburger.classList.toggle('open');
				navLinks.classList.toggle('open', isOpen);
				hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			});

			/* Close when clicking outside the nav */
			document.addEventListener('click', function (e) {
				if (
					navLinks.classList.contains('open') &&
					!navLinks.contains(e.target) &&
					!hamburger.contains(e.target)
				) {
					hamburger.classList.remove('open');
					navLinks.classList.remove('open');
					hamburger.setAttribute('aria-expanded', 'false');
				}
			});

			/* Close on Escape key */
			document.addEventListener('keydown', function (e) {
				if ((e.key === 'Escape' || e.keyCode === 27) && navLinks.classList.contains('open')) {
					hamburger.classList.remove('open');
					navLinks.classList.remove('open');
					hamburger.setAttribute('aria-expanded', 'false');
					hamburger.focus();
				}
			});
		}
	});
})();
