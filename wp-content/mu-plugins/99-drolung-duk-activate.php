<?php
/**
 * Plugin Name: Drolung — One-shot DUK theme activation
 * Description: Network-enables drolung-duk and switches the DUK subsite from drolung-branch to drolung-duk. Runs once on first front-end or admin hit, then self-deactivates via a site-option flag. Safe to delete after successful activation.
 * Author: Drolung dev
 * Version: 0.1.0
 * Network: True
 *
 * After confirming the DUK site renders properly with drolung-duk, this file
 * can be removed — the v2 flags written by 02-drolung-themes-pages.php take
 * over from there.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'drolung_duk_oneshot_activate', 5 );
function drolung_duk_oneshot_activate() {
	if ( ! is_multisite() ) {
		return;
	}
	if ( get_site_option( 'drolung_duk_oneshot_done' ) ) {
		return;
	}

	$log = [];

	/* 1. Network-enable drolung-duk if not already allowed. */
	$allowed = (array) get_site_option( 'allowedthemes', [] );
	if ( empty( $allowed['drolung-duk'] ) ) {
		$allowed['drolung-duk'] = true;
		update_site_option( 'allowedthemes', $allowed );
		$log[] = 'drolung-duk network-enabled';
	}

	/* 2. Switch the DUK site theme if it is still on drolung-branch. */
	$root   = defined( 'DOMAIN_CURRENT_SITE' ) ? DOMAIN_CURRENT_SITE : '';
	$duk_id = $root ? get_blog_id_from_url( 'duk.' . $root, '/' ) : 0;
	if ( $duk_id ) {
		switch_to_blog( $duk_id );
		$current_template = get_option( 'template' );      // parent
		$current_stylesheet = get_option( 'stylesheet' );  // child
		if ( $current_stylesheet !== 'drolung-duk' ) {
			switch_theme( 'drolung-duk' );
			$log[] = "DUK site (blog_id={$duk_id}) switched: stylesheet {$current_stylesheet} → drolung-duk";
		} else {
			$log[] = "DUK site already on drolung-duk — no change";
		}
		restore_current_blog();
	} else {
		$log[] = "⚠ Could not resolve DUK blog_id from duk.{$root}";
	}

	/* 3. Mirror the flags 02-drolung-themes-pages.php expects so the v2 gate
	      stays in sync (assignment effectively done by our one-shot now). */
	if ( ! get_site_option( 'drolung_themes_network_enabled_v2' ) ) {
		update_site_option( 'drolung_themes_network_enabled_v2', current_time( 'mysql' ) );
	}

	update_site_option( 'drolung_duk_oneshot_done', [
		'when' => current_time( 'mysql' ),
		'log'  => $log,
	] );

	/* Log to error_log so the user can verify in Local's logs if needed */
	if ( function_exists( 'error_log' ) ) {
		error_log( '[drolung-duk-oneshot] ' . implode( ' | ', $log ) );
	}
}
