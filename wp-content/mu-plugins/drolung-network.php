<?php
/**
 * Plugin Name: Drolung Network
 * Description: Socle réseau Drolung — types de contenu centralisés (projets, updates, partenaires, articles), helpers cross-site, cache, routeur virtuel, canonical. Référence : doc-technique-drolung-network.md (dossier projet Cowork).
 * Version: 0.1.0
 * Author: Drolung
 *
 * Mu-plugin : chargé automatiquement sur TOUS les sites du réseau.
 * Les types sont enregistrés partout (permaliens, routeur) mais l'UI
 * d'édition n'existe que sur le site central (DROLUNG_MAIN_SITE_ID).
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ID du site central (site principal du réseau). Surchargheable dans wp-config.php. */
if ( ! defined( 'DROLUNG_MAIN_SITE_ID' ) ) {
	define( 'DROLUNG_MAIN_SITE_ID', 1 );
}

define( 'DROLUNG_NETWORK_VERSION', '0.1.0' );
define( 'DROLUNG_NETWORK_DIR', __DIR__ . '/drolung-network' );

require_once DROLUNG_NETWORK_DIR . '/inc/branch.php';
require_once DROLUNG_NETWORK_DIR . '/inc/taxonomies.php';
require_once DROLUNG_NETWORK_DIR . '/inc/post-types.php';
require_once DROLUNG_NETWORK_DIR . '/inc/fields.php';
require_once DROLUNG_NETWORK_DIR . '/inc/cache.php';
require_once DROLUNG_NETWORK_DIR . '/inc/extract.php';
require_once DROLUNG_NETWORK_DIR . '/inc/helpers.php';
require_once DROLUNG_NETWORK_DIR . '/inc/router.php';
require_once DROLUNG_NETWORK_DIR . '/inc/canonical.php';
