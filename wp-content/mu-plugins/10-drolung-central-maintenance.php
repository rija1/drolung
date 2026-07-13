<?php
/**
 * Placeholder "Site à venir" pour le site central (drolung.org).
 *
 * Le contenu public de drolung.org n'est pas encore prêt à être montré,
 * mais le site doit continuer de tourner normalement en coulisses : c'est
 * là que vivent les CPT réseau (projet/article/partenaire), lus par les
 * branches via switch_to_blog() — les couper casserait DSF. On affiche
 * donc juste une page d'attente à tout visiteur public, sans toucher au
 * fonctionnement interne du réseau.
 *
 * Gate : `wp site option update drolung_central_maintenance 1` pour
 * activer (désactivé par défaut — donc sans effet en dev tant que ce
 * n'est pas explicitement activé sur l'environnement de prod).
 * `wp site option delete drolung_central_maintenance` pour désactiver.
 *
 * Un admin connecté avec les droits d'édition voit le vrai site (utile
 * pour prévisualiser/gérer le contenu pendant que le public voit la
 * page d'attente).
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', 'drolung_central_maintenance_gate', 0 );
function drolung_central_maintenance_gate() {
	if ( ! is_main_site() ) {
		return;
	}
	if ( ! get_site_option( 'drolung_central_maintenance' ) ) {
		return;
	}
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return;
	}
	if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		return;
	}

	status_header( 503 );
	header( 'Retry-After: 3600' );
	nocache_headers();

	$logo_url = content_url( 'themes/drolung-base/assets/images/logo.png' );
	require __DIR__ . '/drolung-central-maintenance/coming-soon.php';
	exit;
}
