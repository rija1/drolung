<?php
/**
 * drolung-duk — UK child theme bootstrap.
 *
 * Loads its own design system (assets/css/duk.css) instead of the parent's
 * base.css, because the DUK mockup uses a different set of class names and
 * design tokens from the French DSM/DSF branches. Parent functionality —
 * CPT registration, ACF fields, branding helpers — is inherited unchanged.
 *
 * @package drolung-duk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DROLUNG_DUK_VERSION', '0.1.0' );
define( 'DROLUNG_DUK_URI', get_stylesheet_directory_uri() );

/**
 * Replace the parent's CSS with our DUK design system, but KEEP the Google
 * Fonts dependency the parent registers so we don't load fonts twice.
 *
 * We hook at priority 20 so the parent's enqueue (priority 10) has already
 * registered its handles by the time we run.
 */
add_action( 'wp_enqueue_scripts', 'drolung_duk_enqueue_assets', 20 );
function drolung_duk_enqueue_assets() {
	/* 1. Remove the parent's base.css — DUK does NOT use it. The fonts
	      stylesheet the parent enqueues stays in place. */
	wp_dequeue_style( 'drolung-base-css' );
	wp_deregister_style( 'drolung-base-css' );

	/* The child style.css that base enqueues (handle: drolung-child) is empty
	   on this theme — keep it loaded so anyone adding inline overrides still
	   gets them, but make it depend on our duk.css below. */

	/* 2. DUK design system */
	wp_enqueue_style(
		'drolung-duk-css',
		DROLUNG_DUK_URI . '/assets/css/duk.css',
		[ 'drolung-fonts' ],
		DROLUNG_DUK_VERSION
	);

	/* 3. Re-prioritise the child style.css so it loads AFTER duk.css */
	wp_style_add_data( 'drolung-child', 'after', '/* drolung-duk child overrides — none yet */' );

	/* 4. DUK-specific JS — fade-up observer + nav hamburger toggle */
	wp_enqueue_script(
		'drolung-duk-js',
		DROLUNG_DUK_URI . '/assets/js/duk.js',
		[],
		DROLUNG_DUK_VERSION,
		true
	);
}

/**
 * Drop the parent's base.js too — its features (sticky compact header)
 * conflict with the DUK single-header design.
 */
add_action( 'wp_enqueue_scripts', 'drolung_duk_dequeue_parent_js', 25 );
function drolung_duk_dequeue_parent_js() {
	wp_dequeue_script( 'drolung-base-js' );
	wp_deregister_script( 'drolung-base-js' );
}

/**
 * Donate link override — points to the future /donate/ page on this subsite.
 * (For now the page may not exist yet; the link goes nowhere harmlessly.)
 */
add_filter( 'drolung_donate_url', function () {
	return home_url( '/donate/' );
} );

/**
 * Donate label override — the UK site uses English.
 */
add_filter( 'drolung_donate_label', function () {
	return __( 'Donate', 'drolung-duk' );
} );
