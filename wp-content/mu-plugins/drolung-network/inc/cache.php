<?php
/**
 * Cache cross-site (doc §6 / §12).
 *
 * Stratégie "version salt" : les entrées sont des site-transients
 * (visibles de tous les sites du réseau) dont la clé inclut un numéro
 * de version réseau. Toute sauvegarde d'un contenu réseau sur le site
 * central incrémente la version → toutes les entrées deviennent
 * obsolètes d'un coup, sans registre de clés à maintenir.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** TTL par défaut des entrées de cache. */
function drolung_cache_ttl() {
	return (int) apply_filters( 'drolung_cache_ttl', HOUR_IN_SECONDS );
}

/** Version courante du cache réseau. */
function drolung_cache_version() {
	return (int) get_site_option( 'drolung_cache_version', 1 );
}

/** Clé complète (préfixe + version + hash). Les site-transients sont limités à 167 chars. */
function drolung_cache_key( $key ) {
	return 'drolung_' . drolung_cache_version() . '_' . md5( $key );
}

function drolung_cache_get( $key ) {
	return get_site_transient( drolung_cache_key( $key ) );
}

function drolung_cache_set( $key, $value ) {
	set_site_transient( drolung_cache_key( $key ), $value, drolung_cache_ttl() );
}

/** Invalide tout le cache réseau. */
function drolung_cache_flush() {
	update_site_option( 'drolung_cache_version', drolung_cache_version() + 1 );
}

/**
 * Invalidation : toute écriture sur un contenu réseau (site central).
 */
add_action( 'save_post', 'drolung_cache_on_save', 10, 2 );
add_action( 'deleted_post', 'drolung_cache_on_delete', 10, 2 );

function drolung_cache_on_save( $post_id, $post ) {
	if ( ! is_main_site() || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( in_array( $post->post_type, array( 'projet', 'projet_update', 'partenaire', 'article' ), true ) ) {
		drolung_cache_flush();
	}
}

function drolung_cache_on_delete( $post_id, $post ) {
	if ( ! is_main_site() || ! $post ) {
		return;
	}
	if ( in_array( $post->post_type, array( 'projet', 'projet_update', 'partenaire', 'article' ), true ) ) {
		drolung_cache_flush();
	}
}
