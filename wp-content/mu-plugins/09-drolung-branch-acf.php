<?php
/**
 * ACF sur les sites branches — chargement via filtre option.
 *
 * ACF Pro (licence Personal 1 site) est activé en site-level sur drolung.local
 * (central). Pour les branches (DSF, DSM, DUK), on charge le même plugin en
 * filtrant l'option `active_plugins` — pas de network-activate, pas de licence
 * supplémentaire requise en développement local (les domaines `.local` et le
 * sous-domaine `staging.*` sont auto-détectés hors quota par ACF).
 *
 * Pourquoi cette approche plutôt que network-activate ?
 *   – La licence Personal couvre 1 site ; network-activate l'activerait sur tous,
 *     ce qui violerait les CGU d'ACF en production.
 *   – Le filtre `option_active_plugins` n'est que dans la mémoire de la requête,
 *     pas en DB — il est transparent et réversible.
 *
 * ⚠️  PRODUCTION — avant déploiement sur *.drolung.org :
 *   Option A (recommandée) : passer à licence ACF Freelancer (2 sites) ou Agency.
 *   Option B : substituer ACF Free (advancedcustomfields.com) sur les branches
 *              (`advanced-custom-fields/acf.php`, plugin distinct, slug différent).
 *              Pro et Free peuvent coexister dans le réseau sur des sites différents.
 *
 * Workflow admin résultant :
 *   Pour éditer le contenu d'une page branch (hero, intro, etc.) :
 *   WP-admin du site → Pages → [nom de la page] → champs ACF directement
 *   sous l'éditeur (groupes enregistrés dans drolung-base/inc/acf-fields.php).
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajoute ACF Pro à la liste des plugins actifs sur les sites branches.
 *
 * Ce filtre est enregistré pendant le chargement des mu-plugins (avant les
 * plugins réguliers). Il est appelé quand WP lit `active_plugins` pour charger
 * les plugins — à ce moment-là, tous les mu-plugins sont déjà en mémoire et
 * DROLUNG_MAIN_SITE_ID est disponible (défini dans drolung-network.php).
 */
add_filter( 'option_active_plugins', 'drolung_branch_load_acf' );

function drolung_branch_load_acf( $plugins ) {
	$main_id = defined( 'DROLUNG_MAIN_SITE_ID' ) ? (int) DROLUNG_MAIN_SITE_ID : 1;

	// Site central : ACF Pro est déjà dans active_plugins, on ne l'ajoute pas.
	if ( get_current_blog_id() === $main_id ) {
		return $plugins;
	}

	$acf = 'advanced-custom-fields-pro/acf.php';
	if ( ! in_array( $acf, (array) $plugins, true ) ) {
		$plugins[] = $acf;
	}

	return $plugins;
}

/**
 * Masque la notice de mise à jour ACF Pro sur les branches (pas de clé licence
 * enregistrée pour ces domaines). Le site central conserve ses notifications.
 */
add_filter( 'acf/admin/show_admin_notice', 'drolung_branch_hide_acf_nag', 10, 2 );

function drolung_branch_hide_acf_nag( $show, $notice ) {
	$main_id = defined( 'DROLUNG_MAIN_SITE_ID' ) ? (int) DROLUNG_MAIN_SITE_ID : 1;
	if ( get_current_blog_id() === $main_id ) {
		return $show;
	}
	// Masquer uniquement les notices liées à la licence / mise à jour.
	$license_notices = array( 'update', 'license', 'activate' );
	foreach ( $license_notices as $kw ) {
		if ( isset( $notice['id'] ) && strpos( $notice['id'], $kw ) !== false ) {
			return false;
		}
	}
	return $show;
}
