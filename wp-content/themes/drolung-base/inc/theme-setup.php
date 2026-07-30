<?php
/**
 * Theme setup: WordPress features and nav menus.
 *
 * @package drolung-base
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'drolung_base_setup' );
function drolung_base_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', [
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	] );
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'script',
		'style',
	] );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( [
		'primary' => __( 'Primary navigation', 'drolung-base' ),
		'footer'  => __( 'Footer navigation', 'drolung-base' ),
	] );
}

/* Useful: set a sensible content width for embeds, etc. */
add_action( 'after_setup_theme', function () {
	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 1200;
	}
} );

/**
 * Désactive le script/polyfill d'emoji de WordPress core — dernière requête
 * externe restante (s.w.org) une fois les polices et images auto-hébergées.
 * Aucune perte : tous les navigateurs actuels rendent les emoji nativement,
 * ce polyfill ne sert plus qu'à IE11/vieux Android.
 */
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', function ( $plugins ) {
		return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : $plugins;
	} );
	add_filter( 'wp_resource_hints', function ( $urls, $relation_type ) {
		if ( 'dns-prefetch' === $relation_type ) {
			$urls = array_filter( $urls, function ( $url ) {
				return false === strpos( $url, 's.w.org' );
			} );
		}
		return $urls;
	}, 10, 2 );
} );
