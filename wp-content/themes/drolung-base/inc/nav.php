<?php
/**
 * Navigation helpers:
 *  - A flat walker that renders <a> tags directly (no <ul>/<li>) so the markup
 *    matches the mockups one-to-one and the CSS in base.css "just works".
 *  - A fallback that prints sensible default links if no menu is assigned yet.
 *
 * @package drolung-base
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-includes/class-walker-nav-menu.php';

class Drolung_Flat_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {} // no sub-menus
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$is_active = in_array( 'current-menu-item', $classes, true )
			|| in_array( 'current-menu-parent', $classes, true )
			|| in_array( 'current_page_item', $classes, true );

		$attrs  = ' href="' . esc_url( $item->url ) . '"';
		$attrs .= $is_active ? ' class="active" aria-current="page"' : '';
		if ( ! empty( $item->target ) ) {
			$attrs .= ' target="' . esc_attr( $item->target ) . '"';
		}
		if ( ! empty( $item->xfn ) ) {
			$attrs .= ' rel="' . esc_attr( $item->xfn ) . '"';
		}

		$output .= '<a' . $attrs . '>' . esc_html( $item->title ) . '</a>';
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {} // no closing tag, <a/> is self-rendering above
}

/**
 * Default nav when the admin hasn't created a menu yet.
 * Prints a few links that exist on most child themes.
 */
function drolung_nav_fallback( $args ) {
	$candidates = [
		__( 'Accueil', 'drolung-base' )    => home_url( '/' ),
		__( 'À propos', 'drolung-base' )   => home_url( '/a-propos/' ),
		__( 'Projets', 'drolung-base' )    => home_url( '/projets/' ),
		__( 'Contact', 'drolung-base' )    => home_url( '/contact/' ),
	];
	echo '<a class="active" aria-current="page" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Accueil', 'drolung-base' ) . '</a>';
	foreach ( $candidates as $label => $url ) {
		if ( $label === __( 'Accueil', 'drolung-base' ) ) continue;
		echo '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
	}
}
