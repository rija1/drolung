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
