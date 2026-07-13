<?php
/**
 * Routeur virtuel (doc §10, option B).
 *
 * Sur les sites branches, les permaliens /projets/slug et
 * /articles/slug existent (CPT enregistré partout) mais le contenu
 * vit sur le site central → WordPress produirait un 404. Ce routeur
 * intercepte le 404, charge l'item via les helpers, vérifie sa
 * visibilité pour la branche, puis rend le template du thème avec
 * l'item exposé via drolung_item().
 *
 * Templates attendus dans le thème (au portage) :
 *   single-projet.php / single-article.php — qui lisent drolung_item().
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', 'drolung_virtual_single', 5 );
function drolung_virtual_single() {

	if ( is_main_site() || ! is_404() ) {
		return; /* sur le central les posts existent réellement */
	}

	$qv = $GLOBALS['wp_query']->query_vars;

	/* Détecter le type + slug demandés. */
	$post_type = '';
	$slug      = '';
	if ( ! empty( $qv['projet'] ) ) {
		$post_type = 'projet';
		$slug      = $qv['projet'];
	} elseif ( ! empty( $qv['article'] ) ) {
		$post_type = 'article';
		$slug      = $qv['article'];
	} elseif ( ! empty( $qv['post_type'] ) && ! empty( $qv['name'] ) ) {
		$pt = is_array( $qv['post_type'] ) ? reset( $qv['post_type'] ) : $qv['post_type'];
		if ( in_array( $pt, array( 'projet', 'article' ), true ) ) {
			$post_type = $pt;
			$slug      = $qv['name'];
		}
	}

	if ( ! $post_type || ! $slug ) {
		return;
	}

	$item = 'projet' === $post_type
		? drolung_get_projet( $slug )
		: drolung_get_article( $slug );

	if ( ! $item ) {
		return; /* vrai 404 */
	}

	/* Visibilité : l'item doit cibler la branche courante. */
	if ( ! in_array( drolung_current_branch(), $item['branches'], true ) ) {
		return;
	}

	/* Servir la page. */
	status_header( 200 );
	$GLOBALS['wp_query']->is_404      = false;
	$GLOBALS['wp_query']->is_singular = true;
	$GLOBALS['drolung_item']          = $item;

	/*
	 * Ce post n'existe pas réellement dans la base de la branche (le contenu
	 * vit sur le site central) — is_singular = true sans objet $post fait
	 * planter tout code de thème qui suppose sa présence (body_class(),
	 * is_singular() checks internes, etc. lisent $post->ID / ->post_type).
	 * On construit donc un WP_Post "virtuel" à partir de l'item extrait,
	 * pattern standard pour les pages virtuelles en WordPress.
	 */
	$virtual_post = new WP_Post( (object) array(
		'ID'                    => (int) $item['id'],
		'post_author'           => 0,
		'post_date'             => $item['date'] ?? current_time( 'mysql' ),
		'post_date_gmt'         => get_gmt_from_date( $item['date'] ?? current_time( 'mysql' ) ),
		'post_content'          => $item['content_html'] ?? '',
		'post_title'            => $item['title'] ?? '',
		'post_excerpt'          => $item['excerpt'] ?? '',
		'post_status'           => 'publish',
		'comment_status'        => 'closed',
		'ping_status'           => 'closed',
		'post_password'         => '',
		'post_name'             => $item['slug'] ?? $slug,
		'to_ping'                => '',
		'pinged'                => '',
		'post_modified'         => $item['modified'] ?? $item['date'] ?? current_time( 'mysql' ),
		'post_modified_gmt'     => get_gmt_from_date( $item['modified'] ?? $item['date'] ?? current_time( 'mysql' ) ),
		'post_content_filtered' => '',
		'post_parent'           => 0,
		'guid'                  => $item['permalink_central'] ?? home_url( "/{$post_type}/{$slug}/" ),
		'menu_order'            => 0,
		'post_type'             => $post_type,
		'post_mime_type'        => '',
		'comment_count'         => 0,
		'filter'                => 'raw',
	) );

	/*
	 * Prime the post object cache so ANY later `get_post( $id )` call by
	 * this exact ID (shortlinks, canonical URL, oEmbed discovery, adjacent
	 * post links, SEO plugins…) resolves to the virtual post instead of
	 * hitting the branch's own DB — where that ID doesn't exist — and
	 * getting back null. `wp_cache_add()` never overwrites a real cached
	 * post, so this can't clobber unrelated local content. Non-persistent
	 * cache only (no object-cache.php here), so nothing leaks across
	 * requests.
	 */
	wp_cache_add( $virtual_post->ID, $virtual_post, 'posts' );

	$GLOBALS['wp_query']->is_single          = true;
	$GLOBALS['wp_query']->post               = $virtual_post;
	$GLOBALS['wp_query']->posts              = array( $virtual_post );
	$GLOBALS['wp_query']->post_count         = 1;
	$GLOBALS['wp_query']->queried_object     = $virtual_post;
	$GLOBALS['wp_query']->queried_object_id  = $virtual_post->ID;

	$GLOBALS['post'] = $virtual_post;
	setup_postdata( $virtual_post );

	$template = locate_template( array(
		"single-{$post_type}.php",
		'singular.php',
		'index.php',
	) );

	if ( $template ) {
		include $template;
		exit;
	}
}

/**
 * Tells Polylang where the language switcher should point on a routed
 * virtual page (single-projet.php / single-article.php).
 *
 * Polylang's own URL lookup (`get_translation_url()`) resolves
 * translations by querying the CURRENT site's database for the queried
 * object — but a routed item never exists there (it lives on the
 * central site), so Polylang always falls back to "no translation".
 * This filter is Polylang's documented escape hatch for exactly this
 * kind of case (it says so in its own docblock: "Internally used by
 * Polylang for the static front page and posts page").
 *
 * The translation-group lookup itself must run on the central site
 * (switch_to_blog) — the branch's own Polylang instance has no record
 * of a post ID that only exists centrally.
 */
add_filter( 'pll_pre_translation_url', 'drolung_network_translation_url', 10, 3 );
function drolung_network_translation_url( $url, $language, $queried_object_id ) {
	$item = function_exists( 'drolung_item' ) ? drolung_item() : null;

	if ( ! $item || (int) $item['id'] !== (int) $queried_object_id || ! function_exists( 'pll_get_post_translations' ) ) {
		return $url;
	}

	switch_to_blog( DROLUNG_MAIN_SITE_ID );
	$translations = pll_get_post_translations( $item['id'] );
	restore_current_blog();

	$translated_id = isset( $translations[ $language->slug ] ) ? (int) $translations[ $language->slug ] : 0;

	if ( ! $translated_id || $translated_id === (int) $item['id'] ) {
		return $url;
	}

	$translated_item = 'article' === $item['type']
		? drolung_get_article( $translated_id, $language->slug )
		: drolung_get_projet( $translated_id, $language->slug );

	if ( ! $translated_item ) {
		return $url;
	}

	$base = 'article' === $item['type'] ? 'articles' : 'projets';

	/*
	 * Not using pll_home_url() here: on this install it resolves to the
	 * *front page* URL (which includes the front page's own slug, e.g.
	 * /en/homepage/) rather than a bare /en/ prefix — same quirk already
	 * documented for the homepage language switcher. Build the prefix
	 * directly instead: no prefix for the default (hidden) language,
	 * /{slug}/ otherwise — the pattern the branch's rewrite rules
	 * actually expect.
	 */
	$default_lang = function_exists( 'pll_default_language' ) ? pll_default_language() : '';
	$prefix       = ( $language->slug === $default_lang ) ? '' : trailingslashit( $language->slug );

	return home_url( '/' . $prefix . $base . '/' . $translated_item['slug'] . '/' );
}
