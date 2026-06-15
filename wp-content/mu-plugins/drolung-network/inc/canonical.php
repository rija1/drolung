<?php
/**
 * Canonical configurable par item (doc §10 — décidé).
 *
 * Chaque projet/article désigne son site « officiel » via le champ
 * site_canonical (défaut : dsf pour les projets, org pour les articles).
 * Tous les sites qui rendent la page émettent rel=canonical vers ce site.
 *
 * Ordre des hooks : le routeur virtuel (router.php) s'exécute à
 * template_redirect, APRÈS 'wp'. La décision se prend donc au moment
 * de wp_head, quand $GLOBALS['drolung_item'] est disponible.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Sur le site central, les singles projet/article sont de vrais posts :
 * on remplace le canonical natif de WordPress par le nôtre. */
add_action( 'wp', 'drolung_canonical_maybe_remove_native' );
function drolung_canonical_maybe_remove_native() {
	if ( is_main_site() && is_singular( array( 'projet', 'article' ) ) ) {
		remove_action( 'wp_head', 'rel_canonical' );
	}
}

/* Émis sur tous les sites ; ne produit rien si la requête courante
 * n'affiche pas un item réseau. (Sur les pages virtuelles des branches,
 * le canonical natif ne sort rien — la requête est un 404 réhabilité.) */
add_action( 'wp_head', 'drolung_canonical_tag', 1 );
function drolung_canonical_tag() {
	$item = drolung_canonical_current_item();
	if ( ! $item ) {
		return;
	}
	$target = isset( $item['meta']['site_canonical'] ) ? $item['meta']['site_canonical'] : 'dsf';

	/* Garde-fou : si le site canonical choisi n'affiche pas cet item
	 * (ex. projet dharma ciblé DUK avec défaut DSF), retomber sur la
	 * première branche cochée — jamais de canonical vers un 404. */
	if ( ! empty( $item['branches'] ) && ! in_array( $target, $item['branches'], true ) ) {
		$target = $item['branches'][0];
	}

	$url = drolung_item_url_on_branch( $item, $target );
	echo '<link rel="canonical" href="' . esc_url( $url ) . "\" />\n";
}

/**
 * L'item réseau affiché sur cette requête, ou null.
 * Deux cas : routeur virtuel (branches) ou vrai single (site central).
 */
function drolung_canonical_current_item() {
	if ( isset( $GLOBALS['drolung_item'] ) ) {
		return $GLOBALS['drolung_item'];
	}
	if ( is_main_site() && is_singular( array( 'projet', 'article' ) ) ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			return 'projet' === $post->post_type
				? drolung_get_projet( $post->ID )
				: drolung_get_article( $post->ID );
		}
	}
	return null;
}
