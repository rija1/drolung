<?php
/**
 * Plugin Name: Drolung — Theme assignment + page scaffold
 * Description: Network-enables drolung-org and drolung-branch, assigns drolung-org to the main site, drolung-branch to the three branch subsites, and creates the standard French-slug pages on each. Idempotent.
 * Author: Drolung dev
 * Version: 0.1.0
 * Network: True
 *
 * Runs on `admin_init` after step 1. Stores per-site flags so it never re-runs
 * automatically. To replay on a specific site:
 *   delete_option( 'drolung_pages_created' );        // per-site
 *   delete_site_option( 'drolung_themes_assigned' ); // network-wide
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- 1. Network-enable both child themes ---------- */
add_action( 'admin_init', 'drolung_enable_themes_network' );
function drolung_enable_themes_network() {
	if ( ! is_multisite() || ! is_super_admin() ) {
		return;
	}
	/* Bumped to v2 when drolung-duk was added — forces the function to run once
	   more so the new child theme appears in the allowed list. */
	if ( get_site_option( 'drolung_themes_network_enabled_v2' ) ) {
		return;
	}
	$allowed = (array) get_site_option( 'allowedthemes', [] );
	$allowed['drolung-base']   = true;
	$allowed['drolung-org']    = true;
	$allowed['drolung-branch'] = true;
	$allowed['drolung-duk']    = true;
	update_site_option( 'allowedthemes', $allowed );
	update_site_option( 'drolung_themes_network_enabled_v2', current_time( 'mysql' ) );
}

/* ---------- 2. Assign theme to each site (idempotent) ---------- */
add_action( 'admin_init', 'drolung_assign_themes_to_sites' );
function drolung_assign_themes_to_sites() {
	if ( ! is_multisite() || ! is_super_admin() ) {
		return;
	}
	/* Bumped to v2 when DUK was reassigned from drolung-branch → drolung-duk.
	   Forces the function to run once more so the new mapping takes effect. */
	if ( get_site_option( 'drolung_themes_assigned_v2' ) ) {
		return;
	}

	$log  = [];
	$root = DOMAIN_CURRENT_SITE;

	$assignments = [
		[ 'domain' => $root,           'theme' => 'drolung-org',    'label' => 'main' ],
		[ 'domain' => 'dsm.' . $root,  'theme' => 'drolung-branch', 'label' => 'DSM' ],
		[ 'domain' => 'dsf.' . $root,  'theme' => 'drolung-branch', 'label' => 'DSF' ],
		[ 'domain' => 'duk.' . $root,  'theme' => 'drolung-duk',    'label' => 'DUK' ],
	];

	foreach ( $assignments as $a ) {
		$blog_id = get_blog_id_from_url( $a['domain'], '/' );
		if ( ! $blog_id ) {
			$log[] = "⚠ Site not found: {$a['domain']} — skipped.";
			continue;
		}
		switch_to_blog( $blog_id );
		switch_theme( $a['theme'] );
		restore_current_blog();
		$log[] = "✅ {$a['label']} ({$a['domain']}) → {$a['theme']}";
	}

	update_site_option( 'drolung_themes_assigned_v2', [
		'when' => current_time( 'mysql' ),
		'log'  => $log,
	] );
	set_transient( 'drolung_themes_just_assigned', $log, 60 );
}

/* ---------- 3. Create the standard pages per site ---------- */
add_action( 'admin_init', 'drolung_create_pages_on_each_site' );
function drolung_create_pages_on_each_site() {
	if ( ! is_multisite() || ! is_super_admin() ) {
		return;
	}

	$root = DOMAIN_CURRENT_SITE;

	$plan = [
		[
			'domain' => $root,
			'pages'  => [
				[ 'slug' => 'a-propos',    'title' => 'À propos' ],
				[ 'slug' => 'reseau',      'title' => 'Le réseau' ],
				[ 'slug' => 'notre-action','title' => 'Notre action' ],
				[ 'slug' => 'temoignages', 'title' => 'Témoignages' ],
				[ 'slug' => 'contact',     'title' => 'Contact' ],
				[ 'slug' => 'soutenir',    'title' => 'Soutenir' ],
			],
		],
		[
			'domain' => 'dsm.' . $root,
			'pages'  => drolung_branch_pages(),
		],
		[
			'domain' => 'dsf.' . $root,
			'pages'  => drolung_branch_pages(),
		],
		[
			'domain' => 'duk.' . $root,
			'pages'  => drolung_branch_pages(),
		],
	];

	foreach ( $plan as $site ) {
		$blog_id = get_blog_id_from_url( $site['domain'], '/' );
		if ( ! $blog_id ) {
			continue;
		}
		switch_to_blog( $blog_id );
		if ( ! get_option( 'drolung_pages_created' ) ) {
			drolung_seed_pages( $site['pages'] );
			update_option( 'drolung_pages_created', current_time( 'mysql' ) );
		}
		/* Run home-page check outside the flag-gate so existing sites
		 * also get the Accueil page on the next admin visit. The function
		 * is idempotent: it skips if the page already exists and
		 * page_on_front is already set. */
		drolung_ensure_home_page();
		restore_current_blog();
	}
}

/**
 * Make sure each site has an "Accueil" page set as the static front page.
 * This is what ACF fields on the home (front-page.php) attach to. The page
 * itself has no body content — the template owns the rendering.
 */
function drolung_ensure_home_page() {
	$home    = get_page_by_path( 'accueil' );
	$home_id = $home ? (int) $home->ID : 0;

	if ( ! $home_id ) {
		$created = wp_insert_post( [
			'post_title'     => 'Accueil',
			'post_name'      => 'accueil',
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_content'   => '',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		] );
		if ( is_wp_error( $created ) || ! $created ) {
			return;
		}
		$home_id = (int) $created;
	}

	if ( (int) get_option( 'page_on_front' ) !== $home_id ) {
		update_option( 'page_on_front', $home_id );
	}
	if ( get_option( 'show_on_front' ) !== 'page' ) {
		update_option( 'show_on_front', 'page' );
	}
}

function drolung_branch_pages() {
	/* Note: no 'projets' WP page — that URL is served by archive-projet.php
	 * (the CPT archive). Creating a WP page with the same slug would
	 * collide with the archive and never render. */
	return [
		[ 'slug' => 'a-propos',           'title' => 'À propos' ],
		[ 'slug' => 'notre-action',       'title' => 'Notre action' ],
		[ 'slug' => 'ou-nous-intervenons','title' => 'Où nous intervenons' ],
		[ 'slug' => 's-engager',          'title' => 'S\'engager' ],
		[ 'slug' => 'ressources',         'title' => 'Ressources' ],
	];
}

/** Create the page if it doesn't already exist. */
function drolung_seed_pages( $pages ) {
	foreach ( $pages as $page ) {
		$existing = get_page_by_path( $page['slug'] );
		if ( $existing ) {
			continue;
		}
		wp_insert_post( [
			'post_title'   => $page['title'],
			'post_name'    => $page['slug'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		] );
	}
}

/* ---------- 4. Show admin notice once after the work runs ---------- */
add_action( 'admin_notices', function() {
	$log = get_transient( 'drolung_themes_just_assigned' );
	if ( ! $log ) {
		return;
	}
	delete_transient( 'drolung_themes_just_assigned' );
	echo '<div class="notice notice-success is-dismissible"><p><strong>Drolung — Themes &amp; pages setup:</strong></p><ul style="margin-left:24px;">';
	foreach ( (array) $log as $line ) {
		echo '<li>' . esc_html( $line ) . '</li>';
	}
	echo '</ul></div>';
});
