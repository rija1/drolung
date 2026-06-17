<?php
/**
 * Plugin Name: Drolung — Auto-create primary menus
 * Description: On each site of the network, builds the Primary nav menu (and a small Footer menu) with the right items: pages for drolung-org, branch pages + Projets CPT archive for DSM/DSF/DUK. Idempotent per site.
 * Author: Drolung dev
 * Version: 0.1.0
 * Network: True
 *
 * To replay on a specific site, log into that site's admin and delete the
 * option `drolung_menus_seeded`, then refresh.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'drolung_seed_menus_per_site', 20 );

function drolung_seed_menus_per_site() {
	if ( ! is_multisite() || ! is_super_admin() ) {
		return;
	}

	$root = DOMAIN_CURRENT_SITE;
	$sites = [
		[ 'domain' => $root,           'kind' => 'org'    ],
		[ 'domain' => 'dsm.' . $root,  'kind' => 'branch' ],
		[ 'domain' => 'dsf.' . $root,  'kind' => 'branch' ],
		[ 'domain' => 'duk.' . $root,  'kind' => 'branch' ],
	];

	foreach ( $sites as $site ) {
		$blog_id = get_blog_id_from_url( $site['domain'], '/' );
		if ( ! $blog_id ) {
			continue;
		}
		switch_to_blog( $blog_id );
		if ( ! get_option( 'drolung_menus_seeded' ) ) {
			drolung_seed_primary_menu( $site['kind'] );
			drolung_seed_footer_menu();
			update_option( 'drolung_menus_seeded', current_time( 'mysql' ) );
		}
		restore_current_blog();
	}
}

/* ─────────────────────────────────────────────────────────────
 * Primary menu
 * ───────────────────────────────────────────────────────────── */
function drolung_seed_primary_menu( $kind ) {
	$menu_name = 'Primary';

	// If a primary menu already exists, leave it alone.
	$existing = wp_get_nav_menu_object( $menu_name );
	if ( $existing ) {
		drolung_assign_menu_location( $existing->term_id, 'primary' );
		return;
	}

	$menu_id = wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$items = ( $kind === 'org' ) ? drolung_org_menu_items() : drolung_branch_menu_items();

	foreach ( $items as $item ) {
		drolung_add_menu_item( $menu_id, $item );
	}

	drolung_assign_menu_location( $menu_id, 'primary' );
}

/* ─────────────────────────────────────────────────────────────
 * Footer menu — small set of utility links
 * ───────────────────────────────────────────────────────────── */
function drolung_seed_footer_menu() {
	$menu_name = 'Footer';
	$existing  = wp_get_nav_menu_object( $menu_name );
	if ( $existing ) {
		drolung_assign_menu_location( $existing->term_id, 'footer' );
		return;
	}
	$menu_id = wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$contact = get_page_by_path( 'contact' );
	if ( $contact ) {
		drolung_add_menu_item( $menu_id, [
			'type'   => 'page',
			'object' => 'page',
			'id'     => $contact->ID,
			'title'  => $contact->post_title,
		] );
	}
	drolung_assign_menu_location( $menu_id, 'footer' );
}

/* ─────────────────────────────────────────────────────────────
 * Menu definitions
 * ───────────────────────────────────────────────────────────── */
function drolung_org_menu_items() {
	return [
		[ 'type' => 'home',  'title' => 'Accueil' ],
		[ 'type' => 'page',  'slug'  => 'a-propos',      'title' => 'À propos' ],
		[ 'type' => 'page',  'slug'  => 'reseau',        'title' => 'Le réseau' ],
		[ 'type' => 'page',  'slug'  => 'notre-action',  'title' => 'Notre action' ],
		[ 'type' => 'page',  'slug'  => 'temoignages',   'title' => 'Témoignages' ],
		[ 'type' => 'page',  'slug'  => 'contact',       'title' => 'Contact' ],
	];
}

function drolung_branch_menu_items() {
	return [
		[ 'type' => 'home',          'title' => 'Accueil' ],
		[ 'type' => 'page',          'slug'  => 'notre-action',        'title' => 'Notre action' ],
		[ 'type' => 'page',          'slug'  => 'ou-nous-intervenons', 'title' => 'Où nous intervenons' ],
		[ 'type' => 'cpt_archive',   'object' => 'projet',             'title' => 'Projets' ],
		[ 'type' => 'page',          'slug'  => 's-engager',           'title' => "S'engager" ],
		[ 'type' => 'page',          'slug'  => 'a-propos',            'title' => 'À propos' ],
		[ 'type' => 'page',          'slug'  => 'ressources',          'title' => 'Ressources' ],
		[ 'type' => 'page',          'slug'  => 'contact',             'title' => 'Contact' ],
	];
}

/* ─────────────────────────────────────────────────────────────
 * Helpers
 * ───────────────────────────────────────────────────────────── */
function drolung_add_menu_item( $menu_id, $item ) {
	$args = [
		'menu-item-status' => 'publish',
		'menu-item-title'  => $item['title'],
	];

	switch ( $item['type'] ) {
		case 'home':
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = home_url( '/' );
			break;

		case 'page':
			$page = isset( $item['slug'] ) ? get_page_by_path( $item['slug'] ) : null;
			if ( ! $page ) {
				return; // skip missing page — don't pollute menu with broken links
			}
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $page->ID;
			break;

		case 'cpt_archive':
			$args['menu-item-type']   = 'post_type_archive';
			$args['menu-item-object'] = $item['object'];
			$args['menu-item-url']    = get_post_type_archive_link( $item['object'] );
			break;

		case 'custom':
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = $item['url'];
			break;
	}

	wp_update_nav_menu_item( $menu_id, 0, $args );
}

function drolung_assign_menu_location( $menu_id, $location ) {
	$locations = get_theme_mod( 'nav_menu_locations', [] );
	$locations[ $location ] = (int) $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
