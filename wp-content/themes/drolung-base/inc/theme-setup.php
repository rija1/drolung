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
