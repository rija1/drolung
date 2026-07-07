<?php
/**
 * drolung-branch — child theme bootstrap.
 *
 * Shared across DSM, DSF, and any future French branch. Per-site identity
 * (brand name, tagline, donate URL) is read from the Customizer; helpers
 * are defined in the parent (drolung-base/inc/branding.php).
 *
 * Header design: single sticky nav (top-bar + site-nav), matching DUK.
 * The parent's big-logo / compact-scroll header is replaced by branch/header.php.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DROLUNG_BRANCH_VERSION', '0.2.0' );
define( 'DROLUNG_BRANCH_URI', get_stylesheet_directory_uri() );

/**
 * Enqueue branch-nav.css (header overrides) after base.css,
 * and branch-nav.js (hamburger) in place of base.js.
 */
add_action( 'wp_enqueue_scripts', 'drolung_branch_enqueue_assets', 20 );
function drolung_branch_enqueue_assets() {
	/* Load branch header CSS after base.css so our overrides win. */
	wp_enqueue_style(
		'drolung-branch-nav',
		DROLUNG_BRANCH_URI . '/assets/css/branch-nav.css',
		[ 'drolung-base-css' ],
		DROLUNG_BRANCH_VERSION
	);

	/* Load hamburger / fade-up JS */
	wp_enqueue_script(
		'drolung-branch-nav-js',
		DROLUNG_BRANCH_URI . '/assets/js/branch-nav.js',
		[],
		DROLUNG_BRANCH_VERSION,
		true
	);
}

/**
 * Dequeue parent base.js — its compact-header scroll logic conflicts with
 * the new single-nav design and is no longer needed.
 */
add_action( 'wp_enqueue_scripts', 'drolung_branch_dequeue_parent_js', 25 );
function drolung_branch_dequeue_parent_js() {
	wp_dequeue_script( 'drolung-base-js' );
	wp_deregister_script( 'drolung-base-js' );
}

/**
 * Donate link — points to the s'engager page on this subsite.
 */
add_filter( 'drolung_donate_url', function () {
	return home_url( '/s-engager/' );
} );

/**
 * Language switcher — uses Polylang when configured, otherwise shows nothing.
 *
 * pll_the_languages( raw=1 ) returns one entry per configured language with:
 *   'slug', 'url', 'current_lang' (bool), 'no_translation' (bool).
 * When there is only one language configured (no translated content yet) this
 * returns a single-item array, so the switcher shows only the active language
 * with no dead links.
 */
add_filter( 'drolung_topbar_langs', 'drolung_branch_pll_lang_switcher', 5 );
function drolung_branch_pll_lang_switcher( $langs ) {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return $langs;
	}

	$pll_list = pll_the_languages( array(
		'raw'              => 1,
		'hide_current'     => 0,
		'display_names_as' => 'slug',
	) );

	if ( empty( $pll_list ) ) {
		return $langs;
	}

	$out = array();
	foreach ( $pll_list as $lang ) {
		/* Skip entries that have no translation and are not the current page language. */
		if ( ! empty( $lang['no_translation'] ) && empty( $lang['current_lang'] ) ) {
			$out[] = array(
				'code'   => strtoupper( $lang['slug'] ),
				'url'    => '',   // no target — rendered as plain text in the header
				'active' => false,
			);
		} else {
			$out[] = array(
				'code'   => strtoupper( $lang['slug'] ),
				'url'    => esc_url( $lang['url'] ),
				'active' => ! empty( $lang['current_lang'] ),
			);
		}
	}
	return $out;
}
