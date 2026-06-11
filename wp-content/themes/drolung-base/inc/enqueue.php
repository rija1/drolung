<?php
/**
 * Enqueue parent + child styles and scripts.
 *
 * Order:
 *  1. Google Fonts (Playfair Display + DM Sans + DM Mono)
 *  2. Parent base.css      (variables, header, common utilities)
 *  3. Child style.css      (theme metadata + child-specific overrides)
 *  4. Parent base.js       (scroll behavior, nav cloning, fade-up observer)
 *
 * Child themes that need their own JS can enqueue it with a higher dependency
 * on 'drolung-base-js'.
 *
 * @package drolung-base
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'drolung_base_enqueue_assets', 10 );
function drolung_base_enqueue_assets() {
	/* 1. Fonts */
	wp_enqueue_style(
		'drolung-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap',
		[],
		null
	);

	/* 2. Parent base CSS */
	wp_enqueue_style(
		'drolung-base-css',
		DROLUNG_BASE_URI . '/assets/css/base.css',
		[ 'drolung-fonts' ],
		DROLUNG_BASE_VERSION
	);

	/* 3. Child theme style.css — the WordPress canonical "this is a theme" file */
	if ( is_child_theme() ) {
		wp_enqueue_style(
			'drolung-child',
			get_stylesheet_uri(),
			[ 'drolung-base-css' ],
			wp_get_theme()->get( 'Version' )
		);
	}

	/* 4. Parent base JS */
	wp_enqueue_script(
		'drolung-base-js',
		DROLUNG_BASE_URI . '/assets/js/base.js',
		[],
		DROLUNG_BASE_VERSION,
		true
	);
}
