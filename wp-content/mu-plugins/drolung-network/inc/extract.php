<?php
/**
 * Extraction des données pendant le switch_to_blog (doc §6).
 *
 * RÈGLE : ces fonctions s'exécutent UNIQUEMENT pendant que le blog
 * central est actif. Elles retournent des tableaux plats (IDs résolus
 * en URLs, contenu rendu en HTML final) qui restent valides après
 * restore_current_blog().
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Langue + groupe de traduction Polylang d'un post (null si Polylang absent).
 */
function drolung_extract_lang_info( $post_id ) {
	$lang  = null;
	$group = 'post-' . $post_id;
	$siblings = array();

	if ( taxonomy_exists( 'language' ) ) {
		$terms = get_the_terms( $post_id, 'language' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$lang = $terms[0]->slug;
		}
	}
	if ( taxonomy_exists( 'post_translations' ) ) {
		$terms = get_the_terms( $post_id, 'post_translations' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$group = 'tg-' . $terms[0]->term_id;
			$map   = maybe_unserialize( $terms[0]->description );
			if ( is_array( $map ) ) {
				$siblings = $map; /* [ 'fr' => 12, 'en' => 34, … ] */
			}
		}
	}

	return array( 'lang' => $lang, 'group' => $group, 'siblings' => $siblings );
}

/**
 * Rendu du contenu en HTML final (blocs cœur uniquement — doc §6).
 */
function drolung_render_content( $post ) {
	$html = apply_filters( 'the_content', $post->post_content );
	return str_replace( ']]>', ']]&gt;', $html );
}

/**
 * URLs d'une image attachée, en plusieurs tailles.
 */
function drolung_extract_image( $attachment_id ) {
	if ( ! $attachment_id ) {
		return null;
	}
	$full = wp_get_attachment_image_url( $attachment_id, 'full' );
	if ( ! $full ) {
		return null;
	}
	return array(
		'id'     => (int) $attachment_id,
		'full'   => $full,
		'large'  => wp_get_attachment_image_url( $attachment_id, 'large' ),
		'medium' => wp_get_attachment_image_url( $attachment_id, 'medium' ),
		'thumb'  => wp_get_attachment_image_url( $attachment_id, 'thumbnail' ),
		'alt'    => (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
		'caption' => (string) wp_get_attachment_caption( $attachment_id ),
	);
}

/**
 * Slugs des termes d'une taxonomie pour un post.
 */
function drolung_extract_term_slugs( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return array();
	}
	return wp_list_pluck( $terms, 'slug' );
}

/**
 * Termes (slug => nom) d'une taxonomie pour un post.
 */
function drolung_extract_terms( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return array();
	}
	$out = array();
	foreach ( $terms as $t ) {
		$out[ $t->slug ] = $t->name;
	}
	return $out;
}

/**
 * Partenaires liés à un projet (relationship ACF → tableaux plats).
 */
function drolung_extract_partenaires( $projet_id ) {
	$ids = get_post_meta( $projet_id, 'partenaires', true );
	if ( ! is_array( $ids ) || empty( $ids ) ) {
		return array();
	}
	$out = array();
	foreach ( $ids as $pid ) {
		$p = get_post( $pid );
		if ( ! $p || 'publish' !== $p->post_status ) {
			continue;
		}
		$out[] = array(
			'id'   => (int) $pid,
			'nom'  => get_the_title( $p ),
			'logo' => drolung_extract_image( get_post_thumbnail_id( $pid ) ),
			'url'  => (string) get_post_meta( $pid, 'url', true ),
			'role' => (string) get_post_meta( $pid, 'role', true ),
		);
	}
	return $out;
}

/**
 * Photothèque ACF gallery (tableau d'IDs en meta) → tableaux plats.
 */
function drolung_extract_photos( $projet_id ) {
	$ids = get_post_meta( $projet_id, 'photos', true );
	if ( ! is_array( $ids ) || empty( $ids ) ) {
		return array();
	}
	$out = array();
	foreach ( $ids as $aid ) {
		$img = drolung_extract_image( $aid );
		if ( $img ) {
			$out[] = $img;
		}
	}
	return $out;
}

/**
 * Extraction complète d'un PROJET.
 *
 * @param WP_Post $post         Le post (blog central actif).
 * @param bool    $with_content Rendre le contenu complet (pages détail).
 */
function drolung_extract_projet( $post, $with_content = false ) {
	$id   = $post->ID;
	$lang = drolung_extract_lang_info( $id );

	return array(
		'type'              => 'projet',
		'id'                => $id,
		'slug'              => $post->post_name,
		'title'             => get_the_title( $post ),
		'excerpt'           => get_the_excerpt( $post ),
		'content_html'      => $with_content ? drolung_render_content( $post ) : null,
		'date'              => $post->post_date,
		'modified'          => $post->post_modified,
		'thumbnail'         => drolung_extract_image( get_post_thumbnail_id( $id ) ),
		'permalink_central' => get_permalink( $post ),
		'lang'              => $lang['lang'],
		'translation_group' => $lang['group'],
		'translations'      => $lang['siblings'],
		'branches'          => drolung_extract_term_slugs( $id, 'drolung_branch' ),
		'domaines'          => drolung_extract_term_slugs( $id, 'projet_domaine' ),
		'types'             => drolung_extract_terms( $id, 'projet_type' ),
		'statut'            => drolung_extract_terms( $id, 'projet_statut' ),
		'meta'              => array(
			'code_projet'               => (string) get_post_meta( $id, 'code_projet', true ),
			'budget'                    => (string) get_post_meta( $id, 'budget', true ),
			'budget_eur'                => (float) get_post_meta( $id, 'budget_eur', true ),
			'montant_collecte_eur'      => (float) get_post_meta( $id, 'montant_collecte_eur', true ),
			'date_debut'                => (string) get_post_meta( $id, 'date_debut', true ),
			'date_fin'                  => (string) get_post_meta( $id, 'date_fin', true ),
			'beneficiaires_nombre'      => (int) get_post_meta( $id, 'beneficiaires_nombre', true ),
			'beneficiaires_description' => (string) get_post_meta( $id, 'beneficiaires_description', true ),
			'localisation'              => array(
				'region'  => (string) get_post_meta( $id, 'localisation_region', true ),
				'commune' => (string) get_post_meta( $id, 'localisation_commune', true ),
				'gps'     => (string) get_post_meta( $id, 'localisation_gps', true ),
			),
			'partenaire'                => (string) get_post_meta( $id, 'partenaire', true ),
			'dons'                      => array(
				'assoconnect_url' => (string) get_post_meta( $id, 'dons_assoconnect_url', true ),
				'duk_url'         => (string) get_post_meta( $id, 'dons_duk_url', true ),
				'dsm_info'        => (string) get_post_meta( $id, 'dons_dsm_info', true ),
			),
			'site_canonical'            => get_post_meta( $id, 'site_canonical', true ) ?: 'dsf',
			'featured_home'             => (bool) get_post_meta( $id, 'featured_home', true ),
		),
		'photos'            => drolung_extract_photos( $id ),
		'partenaires'       => drolung_extract_partenaires( $id ),
	);
}

/**
 * Extraction complète d'un ARTICLE.
 */
function drolung_extract_article( $post, $with_content = false ) {
	$id   = $post->ID;
	$lang = drolung_extract_lang_info( $id );

	return array(
		'type'              => 'article',
		'id'                => $id,
		'slug'              => $post->post_name,
		'title'             => get_the_title( $post ),
		'excerpt'           => get_the_excerpt( $post ),
		'content_html'      => $with_content ? drolung_render_content( $post ) : null,
		'date'              => $post->post_date,
		'modified'          => $post->post_modified,
		'author'            => get_the_author_meta( 'display_name', (int) $post->post_author ),
		'thumbnail'         => drolung_extract_image( get_post_thumbnail_id( $id ) ),
		'permalink_central' => get_permalink( $post ),
		'lang'              => $lang['lang'],
		'translation_group' => $lang['group'],
		'translations'      => $lang['siblings'],
		'branches'          => drolung_extract_term_slugs( $id, 'drolung_branch' ),
		'domaines'          => drolung_extract_term_slugs( $id, 'projet_domaine' ),
		'themes'            => drolung_extract_terms( $id, 'theme_article' ),
		'meta'              => array(
			'site_canonical' => get_post_meta( $id, 'site_canonical', true ) ?: 'org',
		),
	);
}

/**
 * Extraction d'une UPDATE de projet.
 */
function drolung_extract_update( $post ) {
	$id   = $post->ID;
	$lang = drolung_extract_lang_info( $id );

	$restrict = get_post_meta( $id, 'branches_restreintes', true );

	return array(
		'type'              => 'projet_update',
		'id'                => $id,
		'title'             => get_the_title( $post ),
		'content_html'      => drolung_render_content( $post ),
		'date'              => $post->post_date,
		'thumbnail'         => drolung_extract_image( get_post_thumbnail_id( $id ) ),
		'projet_id'         => (int) get_post_meta( $id, 'projet', true ),
		'branches_restreintes' => is_array( $restrict ) ? $restrict : array(),
		'lang'              => $lang['lang'],
		'translation_group' => $lang['group'],
	);
}
