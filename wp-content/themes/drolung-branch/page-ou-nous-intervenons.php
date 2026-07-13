<?php
/**
 * Template for the "Où nous intervenons" page (slug: ou-nous-intervenons).
 *
 * The DSF and DSM mockups (mockup-dsf/where-we-work.html and
 * mockup-dsm/where-we-work.html) both redirect this page to projets.html.
 * This template mirrors that decision: it issues a permanent redirect to
 * /projets/ so that any inbound link or bookmark to /ou-nous-intervenons/
 * lands on the correct archive page.
 *
 * No content, no header/footer — a clean 301 is faster and semantically
 * correct for a page that has been fully superseded by another.
 *
 * If you ever want to reinstate a standalone "Où nous intervenons" page
 * (e.g. with a map or a geographic overview), replace the wp_redirect() call
 * below with get_header() / content / get_footer() and remove this comment.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_redirect( esc_url( function_exists( 'drolung_lang_url' ) ? drolung_lang_url( 'projets' ) : home_url( '/projets/' ) ), 301 );
exit;
