<?php
/**
 * Taxonomies réseau (doc §3).
 *
 * - drolung_branch : ciblage par entité (projets, articles). Multi.
 * - projet_type    : domaine d'intervention.
 * - projet_statut  : état du projet.
 * - theme_article  : classement des articles.
 *
 * Enregistrées sur tout le réseau (nécessaire aux requêtes cross-site
 * et aux rewrite rules), UI visible uniquement sur le site central.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'drolung_network_register_taxonomies', 5 );
function drolung_network_register_taxonomies() {

	$show_ui = is_main_site();

	register_taxonomy( 'drolung_branch', array( 'projet', 'article' ), array(
		'labels' => array(
			'name'          => 'Branches',
			'singular_name' => 'Branch',
			'menu_name'     => 'Branches',
			'all_items'     => 'All Branches',
		),
		'public'            => false,
		'show_ui'           => $show_ui,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'hierarchical'      => true,   /* cases à cocher dans l'admin (pas de tags libres) */
		'rewrite'           => false,
	) );

	register_taxonomy( 'projet_domaine', array( 'projet', 'article' ), array(
		'labels' => array(
			'name'          => 'Domains',
			'singular_name' => 'Domain',
			'menu_name'     => 'Domains',
			'all_items'     => 'All Domains',
		),
		'public'            => true,
		'show_ui'           => $show_ui,
		'hierarchical'      => true,   /* cases à cocher */
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'projets/domaine', 'with_front' => false ),
	) );

	register_taxonomy( 'projet_type', 'projet', array(
		'labels' => array(
			'name'          => 'Project Types',
			'singular_name' => 'Project Type',
			'menu_name'     => 'Types',
			'all_items'     => 'All Types',
			'add_new_item'  => 'Add New Type',
			'search_items'  => 'Search Types',
		),
		'public'            => true,
		'show_ui'           => $show_ui,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'projets/type', 'with_front' => false ),
	) );

	register_taxonomy( 'projet_statut', 'projet', array(
		'labels' => array(
			'name'          => 'Project Statuses',
			'singular_name' => 'Status',
			'menu_name'     => 'Statuses',
			'all_items'     => 'All Statuses',
		),
		'public'            => true,
		'show_ui'           => $show_ui,
		'hierarchical'      => false,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'projets/statut', 'with_front' => false ),
	) );

	register_taxonomy( 'theme_article', 'article', array(
		'labels' => array(
			'name'          => 'Article Themes',
			'singular_name' => 'Theme',
			'menu_name'     => 'Themes',
			'all_items'     => 'All Themes',
		),
		'public'            => true,
		'show_ui'           => $show_ui,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'articles/theme', 'with_front' => false ),
	) );
}

/**
 * Seed des termes standards — site central uniquement, seulement si absents.
 */
add_action( 'init', 'drolung_network_seed_terms', 11 );
function drolung_network_seed_terms() {
	if ( ! is_main_site() || ! taxonomy_exists( 'drolung_branch' ) ) {
		return;
	}

	$seeds = array(
		'drolung_branch' => array(
			'DSF — Drolung Solidarités France'  => 'dsf',
			'DSM — Drolung Solidarité Madagascar' => 'dsm',
			'DUK — Drolung UK'                  => 'duk',
			'Drolung International'             => 'org',
		),
		'projet_domaine' => array(
			'Humanitaire'              => 'humanitaire',
			'Soutien aux pratiquants'  => 'dharma',
		),
		'projet_type' => array(
			'Eau'                  => 'eau',
			'Éducation'            => 'education',
			'Santé'                => 'sante',
			'Agriculture'          => 'agriculture',
			'Environnement'        => 'environnement',
			'Autonomisation'       => 'autonomisation',
			'Sécurité alimentaire' => 'securite-alimentaire',
		),
		'projet_statut' => array(
			'En préparation'      => 'en-preparation',
			'En évaluation'       => 'en-evaluation',
			'En recherche de fonds' => 'recherche-de-fonds',
			'Financé'             => 'finance',
			'En cours'            => 'en-cours',
			'Terminé'             => 'termine',
			'À venir'             => 'a-venir',
			'Suspendu'            => 'suspendu',
		),
	);

	foreach ( $seeds as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		foreach ( $terms as $name => $slug ) {
			if ( ! term_exists( $slug, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			}
		}
	}
}
