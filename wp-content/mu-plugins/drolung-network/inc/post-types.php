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

/**
 * Rend `projet` et `article` traduisibles par Polylang. Sans ce filtre,
 * Polylang ignore complètement ces CPT réseau (ni taxonomie `language`,
 * ni `post_translations`) — `drolung_extract_lang_info()` (extract.php)
 * ne peut alors jamais regrouper une fiche FR et sa traduction EN sous
 * un même `translation_group` : elles apparaîtraient comme deux projets
 * distincts au lieu d'un seul avec bascule de langue.
 * Doit être ajouté tôt (avant que Polylang ne mette en cache sa liste de
 * types traduits) — un mu-plugin convient parfaitement.
 */
add_filter( 'pll_get_post_types', function ( $post_types ) {
	$post_types['projet']  = 'projet';
	$post_types['article'] = 'article';
	return $post_types;
} );

/**
 * On the /projets/ and /articles/ archives, Polylang's language switcher
 * decides whether to show a translation link by counting how many posts
 * of that post type exist, in that language, on the CURRENT site
 * (PLL_Model::count_posts()). But 'projet'/'article' content only ever
 * lives on the central site — a branch's own database always has zero
 * of them, in every language — so Polylang always counted 0 and hid
 * every non-active language link on the archive, including ones that
 * demonstrably work (verified via drolung_get_projets()). Force it to
 * never hide for these two post types; the real availability check
 * already happens per-item via drolung_get_projets()/pick_best_language.
 */
add_filter( 'pll_hide_archive_translation_url', function ( $hide, $lang_slug, $args ) {
	if ( isset( $args['post_type'] ) && in_array( $args['post_type'], array( 'projet', 'article' ), true ) ) {
		return false;
	}
	return $hide;
}, 10, 3 );

add_action( 'init', 'drolung_network_register_post_types', 6 );
function drolung_network_register_post_types() {

	$central_ui = is_main_site();

	/* ── PROJET ──────────────────────────────────────────────── */
	register_post_type( 'projet', array(
		'labels' => array(
			'name'                  => 'Projects',
			'singular_name'         => 'Project',
			'menu_name'             => 'Projects',
			'add_new'               => 'Add New',
			'add_new_item'          => 'Add New Project',
			'edit_item'             => 'Edit Project',
			'new_item'              => 'New Project',
			'view_item'             => 'View Project',
			'view_items'            => 'View Projects',
			'search_items'          => 'Search Projects',
			'not_found'             => 'No projects found.',
			'all_items'             => 'All Projects',
			'featured_image'        => 'Main Photo',
			'set_featured_image'    => 'Set main photo',
			'remove_featured_image' => 'Remove main photo',
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
			'name'          => 'Project Updates',
			'singular_name' => 'Project Update',
			'menu_name'     => 'Updates',
			'add_new'       => 'Add New',
			'add_new_item'  => 'Add New Update',
			'edit_item'     => 'Edit Update',
			'all_items'     => 'All Updates',
			'not_found'     => 'No updates found.',
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
			'name'          => 'Partners',
			'singular_name' => 'Partner',
			'menu_name'     => 'Partners',
			'add_new_item'  => 'Add New Partner',
			'edit_item'     => 'Edit Partner',
			'all_items'     => 'All Partners',
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
			'name'          => 'Network Articles',
			'singular_name' => 'Network Article',
			'menu_name'     => 'Network Articles',
			'add_new_item'  => 'Add New Article',
			'edit_item'     => 'Edit Article',
			'all_items'     => 'All Articles',
			'not_found'     => 'No articles found.',
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

/**
 * Le nom du CPT ('name' dans les labels ci-dessus) sert aussi de titre
 * d'onglet par défaut pour la page d'archive publique — mais ces labels
 * sont volontairement en anglais (interface d'admin, doc demandée pour
 * les utilisateurs non-francophones). Sans ce filtre, l'archive
 * publique de DSF/DSM afficherait "Projects"/"Network Articles" en
 * anglais alors que le reste de la page est en français. On force donc
 * le titre public dans la langue de la branche courante.
 */
add_filter( 'post_type_archive_title', 'drolung_network_public_archive_title', 10, 2 );
function drolung_network_public_archive_title( $title, $post_type ) {
	$is_english = function_exists( 'drolung_current_branch' ) && 'duk' === drolung_current_branch();

	$titles = array(
		'projet'  => $is_english ? 'Projects' : 'Projets',
		'article' => $is_english ? 'Articles' : 'Articles',
	);

	return $titles[ $post_type ] ?? $title;
}
