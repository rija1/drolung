<?php
/**
 * drolung-branch — child theme bootstrap.
 *
 * Shared across DSM, DSF, and any future French branch. Per-site identity
 * (brand name, tagline, donate URL) is read from the Customizer; helpers
 * are defined in the parent (drolung-base/inc/branding.php).
 *
 * Header design: single sticky nav (top-bar + site-nav), matching DUK.
 * The parent's big-logo / compact-scroll header is replaced by branch/header.php.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DROLUNG_BRANCH_VERSION', '0.2.0' );
define( 'DROLUNG_BRANCH_URI', get_stylesheet_directory_uri() );

/**
 * Enqueue branch-nav.css (header overrides) after base.css,
 * and branch-nav.js (hamburger) in place of base.js.
 */
add_action( 'wp_enqueue_scripts', 'drolung_branch_enqueue_assets', 20 );
function drolung_branch_enqueue_assets() {
	/* Load branch header CSS after base.css so our overrides win. */
	wp_enqueue_style(
		'drolung-branch-nav',
		DROLUNG_BRANCH_URI . '/assets/css/branch-nav.css',
		[ 'drolung-base-css' ],
		DROLUNG_BRANCH_VERSION
	);

	/* Load hamburger / fade-up JS */
	wp_enqueue_script(
		'drolung-branch-nav-js',
		DROLUNG_BRANCH_URI . '/assets/js/branch-nav.js',
		[],
		DROLUNG_BRANCH_VERSION,
		true
	);
}

/**
 * Dequeue parent base.js — its compact-header scroll logic conflicts with
 * the new single-nav design and is no longer needed.
 */
add_action( 'wp_enqueue_scripts', 'drolung_branch_dequeue_parent_js', 25 );
function drolung_branch_dequeue_parent_js() {
	wp_dequeue_script( 'drolung-base-js' );
	wp_deregister_script( 'drolung-base-js' );
}

/**
 * Donate link — points to the s'engager page on this subsite.
 */
add_filter( 'drolung_donate_url', function () {
	return home_url( '/s-engager/' );
} );

/**
 * Language switcher — uses Polylang when configured, otherwise shows nothing.
 *
 * pll_the_languages( raw=1 ) returns one entry per configured language with:
 *   'slug', 'url', 'current_lang' (bool), 'no_translation' (bool).
 * When there is only one language configured (no translated content yet) this
 * returns a single-item array, so the switcher shows only the active language
 * with no dead links.
 */
add_filter( 'drolung_topbar_langs', 'drolung_branch_pll_lang_switcher', 5 );
function drolung_branch_pll_lang_switcher( $langs ) {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return $langs;
	}

	$pll_list = pll_the_languages( array(
		'raw'              => 1,
		'hide_current'     => 0,
		'display_names_as' => 'slug',
	) );

	if ( empty( $pll_list ) ) {
		return $langs;
	}

	$out = array();
	foreach ( $pll_list as $lang ) {
		/* Skip entries that have no translation and are not the current page language. */
		if ( ! empty( $lang['no_translation'] ) && empty( $lang['current_lang'] ) ) {
			$out[] = array(
				'code'   => strtoupper( $lang['slug'] ),
				'url'    => '',   // no target — rendered as plain text in the header
				'active' => false,
			);
		} else {
			$out[] = array(
				'code'   => strtoupper( $lang['slug'] ),
				'url'    => esc_url( $lang['url'] ),
				'active' => ! empty( $lang['current_lang'] ),
			);
		}
	}
	return $out;
}

/**
 * Footer content — brand column + 3 link columns, matching the
 * `.footer-top` grid already styled in base.css (2fr/1fr/1fr/1fr).
 *
 * Social links and contact email are pulled from the S'engager and
 * Contact pages' own ACF fields (single source of truth — no
 * duplicate data entry for the footer).
 */
add_action( 'drolung_footer_content', 'drolung_branch_footer_content' );
function drolung_branch_footer_content() {
	$engager_id = drolung_acf_page_id_by_slug( 's-engager' );
	$contact_id = drolung_acf_page_id_by_slug( 'contact' );

	$facebook  = drolung_field( 'engager_facebook_url',  '#', $engager_id );
	$linkedin  = drolung_field( 'engager_linkedin_url',  '#', $engager_id );
	$instagram = drolung_field( 'engager_instagram_url', '#', $engager_id );

	$contact_email        = drolung_field( 'contact_email', 'contact@drolung.org', $contact_id );
	$contact_network_url  = drolung_field( 'contact_network_url', 'https://drolung.org', $contact_id );
	$contact_network_name = drolung_field( 'contact_network_display', 'drolung.org', $contact_id );
	?>
	<div class="footer-top">

		<div class="footer-brand">
			<div class="footer-brand__logo-wrap">
				<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="" style="height:32px;width:auto;">
				<span class="footer-brand__name"><?php echo esc_html( drolung_get_brand_name() ); ?></span>
			</div>
			<p><?php esc_html_e( 'Une association de proximité qui soutient des projets concrets en éducation, santé et environnement, en partenariat avec les communautés locales.', 'drolung-branch' ); ?></p>
			<div class="footer-social">
				<a href="<?php echo esc_url( $facebook ); ?>" class="social-btn" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h-2a5 5 0 0 0-5 5v2H6v4h2v7h4v-7h3l1-4h-4V8a1 1 0 0 1 1-1h3z"/></svg>
				</a>
				<a href="<?php echo esc_url( $linkedin ); ?>" class="social-btn" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="9" width="4" height="12"/><circle cx="5" cy="4" r="2"/><path d="M11 21v-7a3 3 0 0 1 6 0v7"/><path d="M11 21v-8"/><path d="M17 21v-7"/></svg>
				</a>
				<a href="<?php echo esc_url( $instagram ); ?>" class="social-btn" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
				</a>
			</div>
		</div>

		<div class="footer-col">
			<div class="footer-col__title"><?php esc_html_e( 'Navigation', 'drolung-branch' ); ?></div>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>"><?php esc_html_e( 'À propos', 'drolung-branch' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>"><?php esc_html_e( 'Notre action', 'drolung-branch' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/ou-nous-intervenons/' ) ); ?>"><?php esc_html_e( 'Où nous intervenons', 'drolung-branch' ); ?></a></li>
			</ul>
		</div>

		<div class="footer-col">
			<div class="footer-col__title"><?php esc_html_e( "S'engager", 'drolung-branch' ); ?></div>
			<ul>
				<li><a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) ) ); ?>"><?php esc_html_e( 'Faire un don', 'drolung-branch' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/projets/' ) ); ?>"><?php esc_html_e( 'Nos projets', 'drolung-branch' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/ressources/' ) ); ?>"><?php esc_html_e( 'Ressources', 'drolung-branch' ); ?></a></li>
			</ul>
		</div>

		<div class="footer-col">
			<div class="footer-col__title"><?php esc_html_e( 'Contact', 'drolung-branch' ); ?></div>
			<ul>
				<li><a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Nous contacter', 'drolung-branch' ); ?></a></li>
				<li><a href="<?php echo esc_url( $contact_network_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $contact_network_name ); ?></a></li>
			</ul>
		</div>

	</div>
	<?php
}

/**
 * Enregistre les chaînes Polylang de la page d'archive `/projets/`, dont
 * le contenu vient d'une page d'options réseau sans post associé (voir
 * `drolung_get_network_option_translated()`, helpers.php).
 *
 * `pll_register_string()` n'agit que quand `PLL()` est une instance
 * `PLL_Admin_Base` — jamais le cas sur une requête front-end pure — donc
 * cet enregistrement doit tourner sur un hook qui s'exécute aussi côté
 * admin (`init`, ici), pas depuis le template `archive-projet.php`
 * lui-même. Ré-enregistré à chaque `init` (idempotent) pour suivre toute
 * modification faite via ACF → Réglages réseau côté central.
 *
 * Traductions à saisir : wp-admin de CHAQUE branche (dsf/dsm.drolung.local)
 * → Langues → Traduction des chaînes → groupe « Drolung — Réglages réseau ».
 */
add_action( 'init', 'drolung_register_projets_archive_strings' );
function drolung_register_projets_archive_strings() {
	if ( ! function_exists( 'pll_register_string' ) || ! function_exists( 'drolung_get_network_option' ) ) {
		return;
	}

	$fields = array(
		'projets_hero_eyebrow'  => __( 'Nos projets', 'drolung-branch' ),
		'projets_hero_title'    => __( 'Quatre projets, <em>une même conviction</em>', 'drolung-branch' ),
		'projets_hero_sub'      => __( 'Les projets que Drolung Solidarité finance et accompagne, portés sur le terrain par notre association sœur.', 'drolung-branch' ),
		'projets_intro_eyebrow' => __( 'Notre soutien', 'drolung-branch' ),
		'projets_intro_title'   => __( 'Nos projets <em>en cours de montage</em>', 'drolung-branch' ),
		'projets_intro_body'    => __( 'Ces projets sont en cours de montage ou en recherche de financement. Tous sont portés sur le terrain par nos associations sœurs. Vos dons les rendent possibles, directement et sans intermédiaire.', 'drolung-branch' ),
	);

	foreach ( $fields as $key => $default ) {
		$value = drolung_get_network_option( $key, $default );
		if ( is_string( $value ) && '' !== $value ) {
			pll_register_string( $key, $value, 'Drolung — Réglages réseau', true );
		}
	}
}
