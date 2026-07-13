/**
 * drolung-branch-2 — minimal front-end JS.
 * Mobile hamburger toggle only; the "Terrain" design has no scroll effects.
 */
( function () {
	var burger = document.querySelector( '.nav-hamburger' );
	var links  = document.querySelector( '.nav-links' );
	if ( ! burger || ! links ) {
		return;
	}
	burger.addEventListener( 'click', function () {
		var open = links.classList.toggle( 'open' );
		burger.classList.toggle( 'open', open );
		burger.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	} );
} )();
