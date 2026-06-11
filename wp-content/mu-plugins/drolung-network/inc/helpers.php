<?php
/**
 * API publique cross-site (doc §6).
 *
 * Les thèmes appellent UNIQUEMENT ces fonctions — jamais
 * switch_to_blog() directement. Toutes retournent des tableaux plats
 * (cf. extract.php), avec fallback de langue et cache réseau.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ─────────────────────────────────────────────────────────────
 * Sélection de la meilleure langue par groupe de traduction.
 * ───────────────────────────────────────────────────────────── */

/**
 * Réduit une liste d'items (toutes langues) à un item par groupe de
 * traduction, en suivant la chaîne de fallback du site courant.
 */
function drolung_pick_best_language( $items, $chain = null ) {
	if ( null === $chain ) {
		$chain = drolung_lang_fallback_chain();
	}

	$groups = array();
	foreach ( $items as $item ) {
		$groups[ $item['translation_group'] ][] = $item;
	}

	$out = array();
	foreach ( $groups as $variants ) {
		$best = null;
		foreach ( $chain as $lang ) {
			foreach ( $variants as $v ) {
				if ( $v['lang'] === $lang ) {
					$best = $v;
					break 2;
				}
			}
		}
		if ( null === $best ) {
			$best = $variants[0]; /* langue inconnue / Polylang absent */
		}
		$best['is_fallback'] = ( null !== $best['lang'] && ! empty( $chain ) && $best['lang'] !== $chain[0] );
		$out[] = $best;
	}

	return $out;
}

/* ─────────────────────────────────────────────────────────────
 * LISTES
 * ───────────────────────────────────────────────────────────── */

/**
 * Projets visibles sur une branche, dans la meilleure langue.
 *
 * @param string|null $branch dsf|dsm|duk|org — défaut : branche courante.
 * @param array       $args   Args WP_Query additionnels (tax_query projet_type…,
 *                            posts_per_page, etc.) + 'with_content' (bool).
 */
function drolung_get_projets( $branch = null, $args = array() ) {
	return drolung_get_central_list( 'projet', $branch, $args );
}

/**
 * Articles réseau ciblés sur une branche.
 */
function drolung_get_articles( $branch = null, $args = array() ) {
	return drolung_get_central_list( 'article', $branch, $args );
}

/**
 * Moteur commun projets/articles.
 */
function drolung_get_central_list( $post_type, $branch = null, $args = array() ) {
	if ( null === $branch ) {
		$branch = drolung_current_branch();
	}
	$chain        = drolung_lang_fallback_chain();
	$with_content = ! empty( $args['with_content'] );
	unset( $args['with_content'] );

	$cache_key = "list:{$post_type}:{$branch}:" . implode( ',', $chain ) . ':' . wp_json_encode( $args ) . ':' . ( $with_content ? 1 : 0 );
	$cached    = drolung_cache_get( $cache_key );
	if ( false !== $cached ) {
		return $cached;
	}

	switch_to_blog( DROLUNG_MAIN_SITE_ID );

	$query_args = wp_parse_args( $args, array(
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
	$query_args['post_type']   = $post_type;
	$query_args['post_status'] = 'publish';
	$query_args['tax_query'][] = array(
		'taxonomy' => 'drolung_branch',
		'field'    => 'slug',
		'terms'    => $branch,
	);

	$extractor = 'projet' === $post_type ? 'drolung_extract_projet' : 'drolung_extract_article';
	$items     = array();
	foreach ( get_posts( $query_args ) as $post ) {
		$items[] = call_user_func( $extractor, $post, $with_content );
	}

	restore_current_blog();

	$items = drolung_pick_best_language( $items, $chain );

	drolung_cache_set( $cache_key, $items );
	return $items;
}

/* ─────────────────────────────────────────────────────────────
 * DÉTAIL
 * ───────────────────────────────────────────────────────────── */

/**
 * Un projet par slug ou ID central, contenu rendu, meilleure langue.
 */
function drolung_get_projet( $id_or_slug, $lang = null ) {
	return drolung_get_central_item( 'projet', $id_or_slug, $lang );
}

/**
 * Un article par slug ou ID central.
 */
function drolung_get_article( $id_or_slug, $lang = null ) {
	return drolung_get_central_item( 'article', $id_or_slug, $lang );
}

/**
 * Moteur commun détail. Résout les traductions liées (Polylang) et
 * suit la chaîne de fallback.
 */
function drolung_get_central_item( $post_type, $id_or_slug, $lang = null ) {
	$chain = drolung_lang_fallback_chain();
	if ( $lang ) {
		array_unshift( $chain, $lang );
		$chain = array_values( array_unique( $chain ) );
	}

	$cache_key = "item:{$post_type}:{$id_or_slug}:" . implode( ',', $chain );
	$cached    = drolung_cache_get( $cache_key );
	if ( false !== $cached ) {
		return $cached;
	}

	switch_to_blog( DROLUNG_MAIN_SITE_ID );

	$post = is_numeric( $id_or_slug )
		? get_post( (int) $id_or_slug )
		: get_page_by_path( sanitize_title( $id_or_slug ), OBJECT, $post_type );

	$item = null;
	if ( $post && $post->post_type === $post_type && 'publish' === $post->post_status ) {

		/* Traductions liées : suivre la chaîne de fallback. */
		$lang_info = drolung_extract_lang_info( $post->ID );
		if ( ! empty( $lang_info['siblings'] ) ) {
			foreach ( $chain as $l ) {
				if ( isset( $lang_info['siblings'][ $l ] ) ) {
					$candidate = get_post( (int) $lang_info['siblings'][ $l ] );
					if ( $candidate && 'publish' === $candidate->post_status ) {
						$post = $candidate;
						break;
					}
				}
			}
		}

		$extractor = 'projet' === $post_type ? 'drolung_extract_projet' : 'drolung_extract_article';
		$item      = call_user_func( $extractor, $post, true );
	}

	restore_current_blog();

	drolung_cache_set( $cache_key, $item );
	return $item;
}

/* ─────────────────────────────────────────────────────────────
 * UPDATES (timeline d'un projet — doc §3.2)
 * ───────────────────────────────────────────────────────────── */

/**
 * Updates d'un projet (toutes ses traductions confondues), filtrées
 * par la branche courante (héritage + restriction optionnelle),
 * meilleure langue, ordre antichronologique.
 *
 * @param int   $projet_central_id ID central du projet (n'importe quelle langue).
 * @param array $args              'limit' (int, défaut 20) + 'branch'.
 */
function drolung_get_projet_updates( $projet_central_id, $args = array() ) {
	$branch = ! empty( $args['branch'] ) ? $args['branch'] : drolung_current_branch();
	$limit  = isset( $args['limit'] ) ? (int) $args['limit'] : 20;
	$chain  = drolung_lang_fallback_chain();

	$cache_key = "updates:{$projet_central_id}:{$branch}:{$limit}:" . implode( ',', $chain );
	$cached    = drolung_cache_get( $cache_key );
	if ( false !== $cached ) {
		return $cached;
	}

	switch_to_blog( DROLUNG_MAIN_SITE_ID );

	/* IDs du projet dans toutes ses langues (la relation peut pointer n'importe laquelle). */
	$projet_ids = array( (int) $projet_central_id );
	$lang_info  = drolung_extract_lang_info( (int) $projet_central_id );
	foreach ( $lang_info['siblings'] as $sid ) {
		$projet_ids[] = (int) $sid;
	}
	$projet_ids = array_values( array_unique( $projet_ids ) );

	$posts = get_posts( array(
		'post_type'      => 'projet_update',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => array(
			array(
				'key'     => 'projet',
				'value'   => $projet_ids,
				'compare' => 'IN',
			),
		),
	) );

	$items = array();
	foreach ( $posts as $post ) {
		$items[] = drolung_extract_update( $post );
	}

	restore_current_blog();

	/* Restriction de branche : vide = hérite du projet (déjà filtré en amont). */
	$items = array_values( array_filter( $items, function ( $u ) use ( $branch ) {
		return empty( $u['branches_restreintes'] ) || in_array( $branch, $u['branches_restreintes'], true );
	} ) );

	$items = drolung_pick_best_language( $items, $chain );

	/* Re-tri par date (le groupage peut altérer l'ordre) + limite. */
	usort( $items, function ( $a, $b ) {
		return strcmp( $b['date'], $a['date'] );
	} );
	if ( $limit > 0 ) {
		$items = array_slice( $items, 0, $limit );
	}

	drolung_cache_set( $cache_key, $items );
	return $items;
}

/**
 * « Dernières nouvelles du terrain » pour la home d'une branche :
 * updates des projets de la branche, tous projets confondus.
 */
function drolung_get_branch_updates( $branch = null, $limit = 5 ) {
	if ( null === $branch ) {
		$branch = drolung_current_branch();
	}

	$projets = drolung_get_projets( $branch, array( 'posts_per_page' => -1 ) );

	$all = array();
	foreach ( $projets as $projet ) {
		$updates = drolung_get_projet_updates( $projet['id'], array( 'branch' => $branch ) );
		foreach ( $updates as $u ) {
			$u['projet_title'] = $projet['title'];
			$u['projet_slug']  = $projet['slug'];
			$all[] = $u;
		}
	}

	usort( $all, function ( $a, $b ) {
		return strcmp( $b['date'], $a['date'] );
	} );

	return array_slice( $all, 0, $limit );
}

/* ─────────────────────────────────────────────────────────────
 * PARTENAIRES
 * ───────────────────────────────────────────────────────────── */

/**
 * Partenaires d'un projet (déjà inclus dans drolung_get_projet() —
 * helper séparé pour les usages isolés).
 */
function drolung_get_partenaires( $projet_central_id ) {
	$cache_key = "partenaires:{$projet_central_id}";
	$cached    = drolung_cache_get( $cache_key );
	if ( false !== $cached ) {
		return $cached;
	}

	switch_to_blog( DROLUNG_MAIN_SITE_ID );
	$items = drolung_extract_partenaires( (int) $projet_central_id );
	restore_current_blog();

	drolung_cache_set( $cache_key, $items );
	return $items;
}

/* ─────────────────────────────────────────────────────────────
 * URLS & DONS
 * ───────────────────────────────────────────────────────────── */

/**
 * URL d'un item (projet/article) sur un site de branche donné.
 */
function drolung_item_url_on_branch( $item, $branch ) {
	$blog_id = drolung_branch_blog_id( $branch );
	if ( ! $blog_id ) {
		return $item['permalink_central'];
	}
	$base = 'projet' === $item['type'] ? 'projets' : 'articles';
	return get_home_url( $blog_id, '/' . $base . '/' . $item['slug'] . '/' );
}

/**
 * Instrument de don d'un projet pour la branche courante (doc §7).
 *
 * @return array{type:string,value:string}|null  type: url|info, ou null
 *         si aucun instrument dédié (→ le thème affiche le don générique).
 */
function drolung_get_don_instrument( $item, $branch = null ) {
	if ( null === $branch ) {
		$branch = drolung_current_branch();
	}
	$dons = isset( $item['meta']['dons'] ) ? $item['meta']['dons'] : array();

	switch ( $branch ) {
		case 'dsf':
		case 'org': /* le site central renvoie vers l'instrument DSF */
			return ! empty( $dons['assoconnect_url'] ) ? array( 'type' => 'url', 'value' => $dons['assoconnect_url'] ) : null;
		case 'duk':
			return ! empty( $dons['duk_url'] ) ? array( 'type' => 'url', 'value' => $dons['duk_url'] ) : null;
		case 'dsm':
			return ! empty( $dons['dsm_info'] ) ? array( 'type' => 'info', 'value' => $dons['dsm_info'] ) : null;
	}
	return null;
}

/**
 * Item courant exposé par le routeur virtuel (router.php).
 */
function drolung_item() {
	return isset( $GLOBALS['drolung_item'] ) ? $GLOBALS['drolung_item'] : null;
}
