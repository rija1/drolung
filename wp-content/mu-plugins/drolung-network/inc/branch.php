<?php
/**
 * Identification de la branche courante et résolution branche → blog.
 *
 * Branches : dsf | dsm | duk | org (org = site central drolung.org).
 * La branche est déduite du sous-domaine, surchargheable par l'option
 * de site `drolung_branch_slug` (utile si un domaine ne suit pas la
 * convention, p.ex. domaine ccTLD dédié plus tard).
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slug de branche du site courant : 'dsf', 'dsm', 'duk' ou 'org'.
 */
function drolung_current_branch() {
	$override = get_option( 'drolung_branch_slug' );
	if ( $override ) {
		return $override;
	}
	return drolung_branch_from_host( (string) parse_url( home_url(), PHP_URL_HOST ) );
}

/**
 * Déduit la branche d'un hostname (dsf.drolung.org → dsf ; drolung.org → org).
 */
function drolung_branch_from_host( $host ) {
	$prefix = explode( '.', $host )[0];
	$known  = array( 'dsf', 'dsm', 'duk' );
	return in_array( $prefix, $known, true ) ? $prefix : 'org';
}

/**
 * Blog ID d'une branche (org → site central). Mis en cache statique.
 *
 * @return int|null Blog ID, ou null si introuvable.
 */
function drolung_branch_blog_id( $branch ) {
	static $map = null;

	if ( 'org' === $branch ) {
		return (int) DROLUNG_MAIN_SITE_ID;
	}

	if ( null === $map ) {
		$map = array();
		foreach ( get_sites( array( 'number' => 100 ) ) as $site ) {
			$b = drolung_branch_from_host( $site->domain );
			if ( (int) $site->blog_id === (int) DROLUNG_MAIN_SITE_ID ) {
				$b = 'org';
			} else {
				$override = get_blog_option( $site->blog_id, 'drolung_branch_slug' );
				if ( $override ) {
					$b = $override;
				}
			}
			if ( ! isset( $map[ $b ] ) ) {
				$map[ $b ] = (int) $site->blog_id;
			}
		}
	}

	return isset( $map[ $branch ] ) ? $map[ $branch ] : null;
}

/**
 * Chaîne de fallback de langue du site courant (doc §1 / §4).
 * La langue courante (Polylang) est mise en tête si disponible.
 */
function drolung_lang_fallback_chain() {
	$chains = array(
		'dsf' => array( 'fr', 'en' ),
		'dsm' => array( 'fr', 'en' ),            // + 'mg' plus tard (doc §14)
		'duk' => array( 'en' ),
		'org' => array( 'en', 'fr', 'zh' ),
	);

	$branch = drolung_current_branch();
	$chain  = isset( $chains[ $branch ] ) ? $chains[ $branch ] : array( 'en', 'fr' );

	if ( function_exists( 'pll_current_language' ) ) {
		$current = pll_current_language( 'slug' );
		if ( $current ) {
			array_unshift( $chain, $current );
			$chain = array_values( array_unique( $chain ) );
		}
	}

	return apply_filters( 'drolung_lang_fallback_chain', $chain, $branch );
}
