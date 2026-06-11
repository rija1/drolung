<?php
/**
 * Branding helpers — read per-site identity from WordPress options.
 *
 * The header is shared across the network but the brand text and logo
 * change per site. We expose three helpers that templates and child
 * themes can call without caring how the data is stored.
 *
 * Storage strategy:
 *  - Brand name and tag are hard-coded per subdomain (see arrays below) so that
 *    Settings → General (blogname / blogdescription) can be used freely in the
 *    WP admin without affecting what the header displays.
 *  - Both values can still be overridden per-site via the Customizer
 *    (drolung_brand_name / drolung_brand_tag) when a one-off override is needed.
 *  - For the logo, we use the standard WordPress Custom Logo (per site).
 *
 * @package drolung-base
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hard-coded brand names per subdomain.
 * Edit here — do NOT rely on Settings → General (blogname).
 */
function drolung_brand_name_defaults() {
	return [
		'dsm'     => 'DROLUNG SOLIDARITE',
		'dsf'     => 'DROLUNG SOLIDARITE',
		'duk'     => 'DROLUNG',
		'drolung' => 'DROLUNG',
	];
}

/**
 * Hard-coded sub-tags per subdomain.
 * Edit here — do NOT rely on Settings → General (blogdescription).
 */
function drolung_brand_tag_defaults() {
	return [
		'dsm'     => 'MADAGASCAR',
		'dsf'     => 'FRANCE',
		'duk'     => 'UNITED KINGDOM',
		'drolung' => 'GLOBAL NETWORK',
	];
}

/**
 * Return the subdomain prefix for the current site (e.g. 'dsm', 'dsf').
 */
function drolung_site_prefix() {
	$host = parse_url( home_url(), PHP_URL_HOST );
	return explode( '.', $host )[0];
}

/**
 * The big wordmark shown in the header.
 * Pulled from hard-coded defaults; Customizer can still override if needed.
 */
function drolung_get_brand_name() {
	$custom = get_theme_mod( 'drolung_brand_name' );
	if ( $custom ) {
		return $custom;
	}
	$defaults = drolung_brand_name_defaults();
	$prefix   = drolung_site_prefix();
	return isset( $defaults[ $prefix ] ) ? $defaults[ $prefix ] : 'DROLUNG';
}

/**
 * The small sub-tag under the wordmark (e.g. "FRANCE", "MADAGASCAR").
 * Pulled from hard-coded defaults; Customizer can still override if needed.
 */
function drolung_get_brand_tag() {
	$custom = get_theme_mod( 'drolung_brand_tag' );
	if ( $custom ) {
		return strtoupper( $custom );
	}
	$defaults = drolung_brand_tag_defaults();
	$prefix   = drolung_site_prefix();
	return isset( $defaults[ $prefix ] ) ? $defaults[ $prefix ] : '';
}

/**
 * URL of the wheel logo image, falling back to the default in assets/images/.
 */
function drolung_get_logo_url() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( $src ) {
			return $src[0];
		}
	}
	/* Fallback: bundled wheel from the parent theme. */
	return DROLUNG_BASE_URI . '/assets/images/logo.png';
}

/**
 * Add Customizer controls for brand_name and brand_tag.
 */
add_action( 'customize_register', 'drolung_customize_branding' );
function drolung_customize_branding( $wp_customize ) {
	$wp_customize->add_section( 'drolung_branding', [
		'title'    => __( 'Drolung — Branding', 'drolung-base' ),
		'priority' => 25,
	] );

	$wp_customize->add_setting( 'drolung_brand_name', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'drolung_brand_name', [
		'label'       => __( 'Wordmark (line 1)', 'drolung-base' ),
		'description' => __( 'Override the hard-coded wordmark for this site. Leave empty to use the default.', 'drolung-base' ),
		'section'     => 'drolung_branding',
		'type'        => 'text',
	] );

	$wp_customize->add_setting( 'drolung_brand_tag', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'drolung_brand_tag', [
		'label'       => __( 'Sub-tag (line 2)', 'drolung-base' ),
		'description' => __( 'Small uppercase line under the wordmark — e.g. MADAGASCAR, FRANCE, GLOBAL NETWORK.', 'drolung-base' ),
		'section'     => 'drolung_branding',
		'type'        => 'text',
	] );
}
