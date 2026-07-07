<?php
/**
 * One-shot : configure Polylang languages per subsite.
 *
 * Runs on admin_init (Polylang is fully booted by then).
 * Per-blog gate: drolung_pll_lang_seeded_v1 (site option keyed by blog_id).
 *
 * DSF (blog 4) → French (fr_FR) as default, English (en_GB) secondary
 * DSM (blog 3) → French (fr_FR) as default, English (en_GB) secondary
 * DUK (blog 5) → English (en_GB) as default — deferred, DUK is paused
 *
 * To force re-run for a site: delete_site_option( 'drolung_pll_lang_seeded_v1_4' )
 * then trigger an admin page load on that site.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'drolung_pll_seed_languages' );
function drolung_pll_seed_languages() {
	$bid = get_current_blog_id();

	/* Already done for this site. */
	if ( get_site_option( 'drolung_pll_lang_seeded_v1_' . $bid ) ) {
		return;
	}

	/* Need Polylang booted. */
	if ( ! function_exists( 'PLL' ) || ! PLL() || ! is_a( PLL()->model, 'PLL_Model' ) ) {
		return;
	}

	/* Already has languages — nothing to add. */
	$existing = function_exists( 'pll_languages_list' ) ? pll_languages_list() : array();
	if ( ! empty( $existing ) ) {
		update_site_option( 'drolung_pll_lang_seeded_v1_' . $bid, current_time( 'mysql' ) );
		return;
	}

	/* Map blog_id → list of locales to add (first = default). */
	$site_langs = array(
		3 => array( 'fr_FR', 'en_GB' ), // DSM
		4 => array( 'fr_FR', 'en_GB' ), // DSF
		// 5 → DUK: deferred until mockup is final
	);

	if ( ! isset( $site_langs[ $bid ] ) ) {
		return;
	}

	$order = 0;
	foreach ( $site_langs[ $bid ] as $locale ) {
		$result = PLL()->model->add_language( array(
			'locale'     => $locale,
			'term_group' => $order++,
		) );

		if ( is_wp_error( $result ) ) {
			error_log( 'Drolung PLL seed: could not add ' . $locale . ' on blog ' . $bid . ': ' . $result->get_error_message() );
		}
	}

	/* Mark all existing pages as French (the current default). */
	$default_lang = function_exists( 'pll_default_language' ) ? pll_default_language() : 'fr';
	if ( $default_lang && function_exists( 'pll_set_post_language' ) ) {
		$pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		) );
		foreach ( $pages as $pid ) {
			pll_set_post_language( $pid, $default_lang );
		}
	}

	update_site_option( 'drolung_pll_lang_seeded_v1_' . $bid, current_time( 'mysql' ) );
}
