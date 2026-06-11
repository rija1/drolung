<?php
/**
 * Plugin Name: Drolung — One-Time Setup
 * Description: Network-activates Pods and creates the DSM / DSF / DUK subsites on first visit to /wp-admin. Self-disables once done.
 * Author: Drolung dev
 * Version: 0.1.0
 * Network: True
 *
 * This is a must-use plugin that runs automatically. It executes its setup
 * only once: when an admin page is loaded, it checks a flag in the network
 * options, performs activation + subsite creation if not yet done, then
 * stores the flag so it never runs again.
 *
 * If you want to re-run it (e.g. to add a subsite), delete the option:
 *   delete_site_option('drolung_setup_done');
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'drolung_run_one_time_setup' );

function drolung_run_one_time_setup() {
	if ( ! is_multisite() ) {
		add_action( 'admin_notices', function() {
			echo '<div class="notice notice-error"><p><strong>Drolung setup:</strong> this site is not multisite — setup skipped.</p></div>';
		});
		return;
	}

	if ( ! is_super_admin() ) {
		return; // Only super admins can do this.
	}

	if ( get_site_option( 'drolung_setup_done' ) ) {
		return; // Already ran.
	}

	$log = [];

	/* ---------- 1. Network-activate Pods ---------- */
	$pods_file = 'pods/init.php';
	$active = (array) get_site_option( 'active_sitewide_plugins', [] );
	if ( ! isset( $active[ $pods_file ] ) ) {
		if ( file_exists( WP_PLUGIN_DIR . '/pods/init.php' ) ) {
			$active[ $pods_file ] = time();
			update_site_option( 'active_sitewide_plugins', $active );
			$log[] = '✅ Pods network-activated.';
		} else {
			$log[] = '⚠ Pods plugin files not found at wp-content/plugins/pods/.';
		}
	} else {
		$log[] = 'ℹ Pods already network-activated.';
	}

	/* ---------- 2. Create subsites (DSM, DSF, DUK) ---------- */
	$current_user_id = get_current_user_id();
	$root_domain = DOMAIN_CURRENT_SITE; // e.g. drolung.local

	$sites = [
		[ 'slug' => 'dsm', 'title' => 'Drolung Solidarité Madagascar' ],
		[ 'slug' => 'dsf', 'title' => 'Drolung Solidarité France' ],
		[ 'slug' => 'duk', 'title' => 'Drolung UK' ],
	];

	foreach ( $sites as $site ) {
		$domain = $site['slug'] . '.' . $root_domain;

		if ( get_blog_id_from_url( $domain, '/' ) ) {
			$log[] = "ℹ Subsite already exists: {$domain}";
			continue;
		}

		$blog_id = wpmu_create_blog(
			$domain,
			'/',
			$site['title'],
			$current_user_id,
			[ 'public' => 1 ],
			get_current_network_id()
		);

		if ( is_wp_error( $blog_id ) ) {
			$log[] = "❌ Failed to create {$domain}: " . $blog_id->get_error_message();
		} else {
			$log[] = "✅ Created subsite: {$domain} (ID {$blog_id})";
		}
	}

	/* ---------- 3. Lock the flag so this never runs again ---------- */
	update_site_option( 'drolung_setup_done', [
		'when'  => current_time( 'mysql' ),
		'log'   => $log,
	] );

	/* ---------- 4. One-time admin notice ---------- */
	set_transient( 'drolung_setup_just_ran', $log, 60 );
}

/* Show the result once, right after setup runs */
add_action( 'admin_notices', function() {
	$log = get_transient( 'drolung_setup_just_ran' );
	if ( ! $log ) {
		return;
	}
	delete_transient( 'drolung_setup_just_ran' );
	echo '<div class="notice notice-success is-dismissible"><p><strong>Drolung — One-time setup complete:</strong></p><ul style="margin-left:24px;">';
	foreach ( (array) $log as $line ) {
		echo '<li>' . esc_html( $line ) . '</li>';
	}
	echo '</ul></div>';
});
