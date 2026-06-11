<?php
/**
 * drolung-org — child theme bootstrap.
 *
 * Inherits all wiring from drolung-base. We only declare what's different
 * for the mother site here: brand defaults, donate URL, optional extra CSS.
 *
 * @package drolung-org
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Brand defaults for the mother site if the Customizer is empty. */
add_filter( 'theme_mod_drolung_brand_name', function ( $value ) {
	return $value ?: 'DROLUNG';
} );
add_filter( 'theme_mod_drolung_brand_tag', function ( $value ) {
	return $value ?: 'GLOBAL NETWORK';
} );

/* The mother site has no "Faire un don" button in the same way — point to network page. */
add_filter( 'drolung_donate_label', function () {
	return __( 'Soutenir', 'drolung-org' );
} );
add_filter( 'drolung_donate_url', function () {
	return home_url( '/soutenir/' );
} );

/* Optional site-specific CSS layered on top of base.css. */
add_action( 'wp_enqueue_scripts', function () {
	if ( file_exists( get_stylesheet_directory() . '/assets/css/site.css' ) ) {
		wp_enqueue_style(
			'drolung-org-site',
			get_stylesheet_directory_uri() . '/assets/css/site.css',
			[ 'drolung-base-css' ],
			wp_get_theme()->get( 'Version' )
		);
	}
}, 20 );
