<?php
/**
 * CPT réseau (doc §3) : projet, projet_update, partenaire, article.
 *
 * Enregistrés sur tout le réseau (rewrite rules + routeur virtuel),
 * mais UI d'édition uniquement sur le site central — un contenu
 * "terrain" ne peut pas être créé au mauvais endroit (doc §2).
 *
 * Remplace l'ancien drolung-base/inc/cpt-projet.php (thème).
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'drolung_network_register_post_types', 6 );
function drolung_network_register_post_types() {

	$central_ui = is_main_site();

	/* ── PROJET ──────────────────────────────────────────────── */
	register_post_type( 'projet', array(
		'labels' => array(
			'name'                  => 'Projets',
			'singular_name'         => 'Projet',
			'menu_name'             => 'Projets',
			'add_new'               => 'Ajouter',
			'add_new_item'          => 'Ajouter un projet',
			'edit_item'             => 'Modifier le projet',
			'new_item'              => 'Nouveau projet',
			'view_item'             => 'Voir le projet',
			'view_items'            => 'Voir les projets',
			'search_items'          => 'Rechercher un projet',
			'not_found'             => 'Aucun projet trouvé.',
			'all_items'             => 'Tous les projets',
			'featured_image'        => 'Photo principale',
			'set_featured_image'    => 'Définir la photo principale',
			'remove_featured_image' => 'Retirer la photo principale',
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => $central_ui,
		'show_in_menu'       => $central_ui,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-portfolio',
		'menu_position'      => 20,
		'has_archive'        => 'projets',
		'rewrite'            => array( 'slug' => 'projets', 'with_front' => false ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
	) );

	/* ── PROJET UPDATE (nouvelles du terrain) ───────────────── */
	register_post_type( 'projet_update', array(
		'labels' => array(
			'name'          => 'Updates projet',
			'singular_name' => 'Update projet',
			'menu_name'     => 'Updates',
			'add_new'       => 'Ajouter',
			'add_new_item'  => 'Ajouter une update',
			'edit_item'     => 'Modifier l\'update',
			'all_items'     => 'Toutes les updates',
			'not_found'     => 'Aucune update.',
		),
		'public'             => false,
		'publicly_queryable' => false,   /* affichées en timeline, jamais en page propre */
		'show_ui'            => $central_ui,
		'show_in_menu'       => $central_ui ? 'edit.php?post_type=projet' : false,
		'show_in_rest'       => true,
		'has_archive'        => false,
		'rewrite'            => false,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		'capability_type'    => 'post',
	) );

	/* ── PARTENAIRE (référentiel logos, non traduit) ────────── */
	register_post_type( 'partenaire', array(
		'labels' => array(
			'name'          => 'Partenaires',
			'singular_name' => 'Partenaire',
			'menu_name'     => 'Partenaires',
			'add_new_item'  => 'Ajouter un partenaire',
			'edit_item'     => 'Modifier le partenaire',
			'all_items'     => 'Tous les partenaires',
		),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => $central_ui,
		'show_in_menu'       => $central_ui,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-groups',
		'menu_position'      => 21,
		'has_archive'        => false,
		'rewrite'            => false,
		'supports'           => array( 'title', 'thumbnail' ),  /* thumbnail = logo */
		'capability_type'    => 'post',
	) );

	/* ── ARTICLE (éditorial de fond partageable, doc §3.4) ──── */
	register_post_type( 'article', array(
		'labels' => array(
			'name'          => 'Articles réseau',
			'singular_name' => 'Article réseau',
			'menu_name'     => 'Articles réseau',
			'add_new_item'  => 'Ajouter un article',
			'edit_item'     => 'Modifier l\'article',
			'all_items'     => 'Tous les articles',
			'not_found'     => 'Aucun article.',
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => $central_ui,
		'show_in_menu'       => $central_ui,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-welcome-write-blog',
		'menu_position'      => 22,
		'has_archive'        => 'articles',
		'rewrite'            => array( 'slug' => 'articles', 'with_front' => false ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author' ),
		'capability_type'    => 'post',
	) );
}
