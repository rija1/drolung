<?php
/**
 * Drolung Base — main bootstrap.
 *
 * @package drolung-base
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DROLUNG_BASE_VERSION', '0.2.0' );
define( 'DROLUNG_BASE_DIR', get_template_directory() );
define( 'DROLUNG_BASE_URI', get_template_directory_uri() );

/* Core wiring is split into small files for readability. */
require_once DROLUNG_BASE_DIR . '/inc/theme-setup.php';
require_once DROLUNG_BASE_DIR . '/inc/enqueue.php';
require_once DROLUNG_BASE_DIR . '/inc/branding.php';
require_once DROLUNG_BASE_DIR . '/inc/nav.php';
/* CPT "projet" : migré vers le mu-plugin drolung-network (2026-06).
 * Voir wp-content/mu-plugins/drolung-network/ et la doc technique. */
require_once DROLUNG_BASE_DIR . '/inc/acf-fields.php';
