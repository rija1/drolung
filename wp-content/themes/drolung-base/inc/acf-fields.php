<?php
/**
 * ACF field groups for editable page content.
 *
 * Registered in PHP (not via the admin UI) so the schema is version-
 * controlled and propagates to every branch site automatically.
 *
 * Group strategy:
 *   - One field group per page (front_page, a_propos, notre_action…).
 *   - Each group is tied to a page via `page_template` or `post_name`.
 *   - Templates read values with drolung_field( $key, $default ) which
 *     falls back to the static copy whenever the field is empty —
 *     so the site never goes blank before the admin enters values.
 *
 * The free ACF version has no Repeater field, so list-like sections
 * (programmes, news, bureau) will be migrated to dedicated CPTs in a
 * later phase. For now, fixed-length lists use numbered fields
 * (region_1_name, region_2_name…).
 *
 * @package drolung-base
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ─────────────────────────────────────────────────────────────
 * Helper: get_field() wrapper with default fallback.
 *
 * Priority :
 *   1. ACF get_field()       — when ACF is active (central + branches via
 *                              09-drolung-branch-acf.php option filter).
 *   2. get_post_meta() brute — resilience fallback if ACF is ever absent.
 *      ACF image fields store attachment IDs → converted to URL automatically.
 * ───────────────────────────────────────────────────────────── */
if ( ! function_exists( 'drolung_field' ) ) {
	function drolung_field( $key, $default = '', $post_id = false ) {
		if ( function_exists( 'get_field' ) ) {
			$value = get_field( $key, $post_id );
			if ( $value !== '' && $value !== null && $value !== false ) {
				return $value;
			}
			return $default;
		}

		/* Fallback : post meta brute (quand ACF n'est pas chargé). */
		$pid = $post_id ? (int) $post_id : get_the_ID();
		if ( ! $pid ) {
			return $default;
		}
		$value = get_post_meta( $pid, $key, true );
		if ( $value === '' || $value === null || $value === false ) {
			return $default;
		}
		/* Les champs image ACF stockent l'ID de l'attachment. Convertir en URL. */
		if ( is_numeric( $value ) && (int) $value > 0 ) {
			$url = wp_get_attachment_url( (int) $value );
			return $url ?: $default;
		}
		return $value;
	}
}

/* ─────────────────────────────────────────────────────────────
 * Register field groups. Hooked late so ACF is loaded first.
 * ───────────────────────────────────────────────────────────── */
add_action( 'acf/init', 'drolung_register_acf_fields' );

/**
 * Network-wide options page (central only) — a home for content that
 * belongs to a shared listing/archive rather than any single post, so
 * it doesn't need a fake "current post" to hang an ACF field group off.
 * See group_drolung_projets_archive below for the reason this exists.
 */
add_action( 'acf/init', 'drolung_register_network_options_page' );
function drolung_register_network_options_page() {
	if ( function_exists( 'acf_add_options_page' ) && is_main_site() ) {
		acf_add_options_page( array(
			'page_title' => 'Réglages réseau',
			'menu_title' => 'Réglages réseau',
			'menu_slug'  => 'drolung-network-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
			'position'   => 60,
		) );
	}
}

function drolung_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ─────────────────────────────────────────────────────────
	 * FRONT PAGE (front-page.php on branch theme).
	 * Location: the static front page — matched by ID (not
	 * `page_type == front_page`, which only ever matches the literal
	 * `page_on_front` option and hides the group on Polylang
	 * translations of that page, e.g. an English "Homepage").
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_front',
		'title'    => 'Page d\'accueil',
		'location' => array_map(
			function ( $id ) {
				return [ [ 'param' => 'page', 'operator' => '==', 'value' => $id ] ];
			},
			drolung_acf_front_page_ids() ?: [ (int) get_option( 'page_on_front' ) ]
		),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'fields'          => [

			/* ── HERO ─────────────────────────────────────── */
			[ 'key' => 'field_front_hero_tab',     'label' => 'Hero',                  'name' => '', 'type' => 'tab', 'placement' => 'top' ],
			[ 'key' => 'field_front_hero_eyebrow', 'label' => 'Surtitre (eyebrow)',    'name' => 'hero_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_hero_title',   'label' => 'Titre (HTML autorisé)', 'name' => 'hero_title',   'type' => 'textarea', 'rows' => 3, 'new_lines' => 'br', 'instructions' => 'Tu peux mettre un mot en <em>italique</em> en l\'entourant de balises &lt;em&gt;mot&lt;/em&gt;.' ],
			[ 'key' => 'field_front_hero_sub',     'label' => 'Sous-titre',            'name' => 'hero_sub',     'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			[ 'key' => 'field_front_hero_cta1_label', 'label' => 'Bouton principal — texte', 'name' => 'hero_cta1_label', 'type' => 'text' ],
			[ 'key' => 'field_front_hero_cta1_url',   'label' => 'Bouton principal — URL',   'name' => 'hero_cta1_url',   'type' => 'url' ],
			[ 'key' => 'field_front_hero_cta2_label', 'label' => 'Bouton secondaire — texte','name' => 'hero_cta2_label', 'type' => 'text' ],
			[ 'key' => 'field_front_hero_cta2_url',   'label' => 'Bouton secondaire — URL',  'name' => 'hero_cta2_url',   'type' => 'url' ],
			[ 'key' => 'field_front_hero_image',   'label' => 'Image de fond du hero',  'name' => 'hero_image',  'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ],

			/* ── IMPACT BAND ─────────────────────────────── */
			[ 'key' => 'field_front_impact_tab',      'label' => 'Bandeau impact', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_impact_1_num',    'label' => 'Stat 1 — chiffre',  'name' => 'impact_1_num',   'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_impact_1_label',  'label' => 'Stat 1 — libellé', 'name' => 'impact_1_label', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_impact_2_num',    'label' => 'Stat 2 — chiffre',  'name' => 'impact_2_num',   'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_impact_2_label',  'label' => 'Stat 2 — libellé', 'name' => 'impact_2_label', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_impact_3_num',    'label' => 'Stat 3 — chiffre',  'name' => 'impact_3_num',   'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_impact_3_label',  'label' => 'Stat 3 — libellé', 'name' => 'impact_3_label', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_impact_4_num',    'label' => 'Stat 4 — chiffre',  'name' => 'impact_4_num',   'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_impact_4_label',  'label' => 'Stat 4 — libellé', 'name' => 'impact_4_label', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],

			/* ── INTRO ───────────────────────────────────── */
			[ 'key' => 'field_front_intro_tab',     'label' => 'Présentation', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_intro_eyebrow', 'label' => 'Surtitre',     'name' => 'intro_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_intro_title',   'label' => 'Titre (HTML)', 'name' => 'intro_title',   'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_front_intro_body',    'label' => 'Texte',        'name' => 'intro_body',    'type' => 'wysiwyg', 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_front_intro_image',   'label' => 'Image',        'name' => 'intro_image',   'type' => 'image', 'return_format' => 'url' ],
			[ 'key' => 'field_front_intro_badge_num',   'label' => 'Badge — chiffre', 'name' => 'intro_badge_num',   'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_intro_badge_label', 'label' => 'Badge — libellé', 'name' => 'intro_badge_label', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_intro_cta_label', 'label' => 'Lien vers À propos — texte', 'name' => 'intro_cta_label', 'type' => 'text' ],

			/* ── NOS PROJETS (préview) ───────────────────── */
			[ 'key' => 'field_front_map_tab',     'label' => 'Nos projets (préview)', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_map_eyebrow', 'label' => 'Surtitre', 'name' => 'map_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_map_title',   'label' => 'Titre (HTML)', 'name' => 'map_title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '', 'instructions' => 'Les 4 projets affichés sont sélectionnés individuellement sur chaque fiche projet (case « Mettre en avant sur la page d\'accueil »), pas ici.' ],

			/* ── TESTIMONIAL ─────────────────────────────── */
			[ 'key' => 'field_front_test_tab',         'label' => 'Témoignage', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_test_text',        'label' => 'Citation',       'name' => 'test_text',        'type' => 'textarea', 'rows' => 4 ],
			[ 'key' => 'field_front_test_author_name', 'label' => 'Nom',            'name' => 'test_author_name', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_front_test_author_role', 'label' => 'Rôle / lieu',    'name' => 'test_author_role', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_front_test_author_photo','label' => 'Photo (carrée)', 'name' => 'test_author_photo','type' => 'image', 'return_format' => 'url' ],

			/* ── CHIFFRES CLÉS ──────────────────────────── */
			[ 'key' => 'field_front_chiffres_tab',     'label' => 'Chiffres clés', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_chiffres_eyebrow', 'label' => 'Chiffres — surtitre',    'name' => 'chiffres_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_chiffres_title',   'label' => 'Chiffres — titre',       'name' => 'chiffres_title',   'type' => 'text' ],
			[ 'key' => 'field_front_chiffres_cta',     'label' => 'Chiffres — phrase de conclusion', 'name' => 'chiffres_cta', 'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_front_chiffre_1_num',    'label' => 'Chiffre 1 — valeur',    'name' => 'chiffre_1_num',    'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_chiffre_1_label',  'label' => 'Chiffre 1 — libellé',  'name' => 'chiffre_1_label',  'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_chiffre_2_num',    'label' => 'Chiffre 2 — valeur',    'name' => 'chiffre_2_num',    'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_chiffre_2_label',  'label' => 'Chiffre 2 — libellé',  'name' => 'chiffre_2_label',  'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_chiffre_3_num',    'label' => 'Chiffre 3 — valeur',    'name' => 'chiffre_3_num',    'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_chiffre_3_label',  'label' => 'Chiffre 3 — libellé',  'name' => 'chiffre_3_label',  'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_chiffre_4_num',    'label' => 'Chiffre 4 — valeur',    'name' => 'chiffre_4_num',    'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_chiffre_4_label',  'label' => 'Chiffre 4 — libellé',  'name' => 'chiffre_4_label',  'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_chiffre_5_num',    'label' => 'Chiffre 5 — valeur',    'name' => 'chiffre_5_num',    'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_chiffre_5_label',  'label' => 'Chiffre 5 — libellé',  'name' => 'chiffre_5_label',  'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_chiffre_6_num',    'label' => 'Chiffre 6 — valeur',    'name' => 'chiffre_6_num',    'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_chiffre_6_label',  'label' => 'Chiffre 6 — libellé',  'name' => 'chiffre_6_label',  'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],

			/* ── NOS ENGAGEMENTS ─────────────────────────── */
			[ 'key' => 'field_front_engagements_tab',    'label' => 'Nos engagements', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_engagement_1_label', 'label' => 'Engagement 1 — titre', 'name' => 'engagement_1_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_front_engagement_1_body',  'label' => 'Engagement 1 — texte', 'name' => 'engagement_1_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 60 ] ],
			[ 'key' => 'field_front_engagement_2_label', 'label' => 'Engagement 2 — titre', 'name' => 'engagement_2_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_front_engagement_2_body',  'label' => 'Engagement 2 — texte', 'name' => 'engagement_2_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 60 ] ],
			[ 'key' => 'field_front_engagement_3_label', 'label' => 'Engagement 3 — titre', 'name' => 'engagement_3_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_front_engagement_3_body',  'label' => 'Engagement 3 — texte', 'name' => 'engagement_3_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 60 ] ],
			[ 'key' => 'field_front_engagement_4_label', 'label' => 'Engagement 4 — titre', 'name' => 'engagement_4_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_front_engagement_4_body',  'label' => 'Engagement 4 — texte', 'name' => 'engagement_4_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 60 ] ],

			/* ── NEWSLETTER ──────────────────────────────── */
			[ 'key' => 'field_front_newsletter_tab',   'label' => 'Newsletter', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_newsletter_title', 'label' => 'Newsletter — titre', 'name' => 'newsletter_title', 'type' => 'text' ],
			[ 'key' => 'field_front_newsletter_body',  'label' => 'Newsletter — texte', 'name' => 'newsletter_body',  'type' => 'text' ],
			[ 'key' => 'field_front_newsletter_placeholder', 'label' => 'Champ e-mail — texte indicatif', 'name' => 'newsletter_placeholder', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_front_newsletter_cta_label',   'label' => 'Bouton — texte',                 'name' => 'newsletter_cta_label',   'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],

			/* ── DONATE ──────────────────────────────────── */
			[ 'key' => 'field_front_donate_tab',     'label' => 'Faire un don', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_donate_eyebrow', 'label' => 'Surtitre', 'name' => 'donate_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_donate_title',   'label' => 'Titre (HTML)', 'name' => 'donate_title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_front_donate_body',    'label' => 'Texte',    'name' => 'donate_body', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ],
			[ 'key' => 'field_front_donate_cta_label', 'label' => 'Bouton — texte', 'name' => 'donate_cta_label', 'type' => 'text' ],

			/* Exemples de dons */
			[ 'key' => 'field_front_don_ex_1_montant', 'label' => 'Exemple don 1 — montant', 'name' => 'don_exemple_1_montant', 'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_don_ex_1_desc',    'label' => 'Exemple don 1 — description', 'name' => 'don_exemple_1_desc', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_don_ex_2_montant', 'label' => 'Exemple don 2 — montant', 'name' => 'don_exemple_2_montant', 'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_don_ex_2_desc',    'label' => 'Exemple don 2 — description', 'name' => 'don_exemple_2_desc', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
			[ 'key' => 'field_front_don_ex_3_montant', 'label' => 'Exemple don 3 — montant', 'name' => 'don_exemple_3_montant', 'type' => 'text', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_front_don_ex_3_desc',    'label' => 'Exemple don 3 — description', 'name' => 'don_exemple_3_desc', 'type' => 'text', 'wrapper' => [ 'width' => 70 ] ],
		],
	] );

	/* ─────────────────────────────────────────────────────────
	 * À PROPOS PAGE.
	 * Bound to the auto-created page with slug 'a-propos'.
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_apropos',
		'title'    => 'À propos — contenu éditable',
		'location' => drolung_acf_page_location( 'a-propos' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'fields'          => [

			/* ── HERO ─────────────────────────────────────── */
			[ 'key' => 'field_apropos_hero_tab',     'label' => 'Hero',                  'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_hero_eyebrow', 'label' => 'Hero — surtitre',       'name' => 'hero_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_apropos_hero_title',   'label' => 'Hero — titre (HTML)',   'name' => 'hero_title',   'type' => 'textarea', 'rows' => 2, 'instructions' => 'Utilise <em>mot</em> pour mettre en italique doré.' ],
			[ 'key' => 'field_apropos_hero_sub',     'label' => 'Hero — sous-titre',     'name' => 'hero_sub',     'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
			[ 'key' => 'field_apropos_hero_image',   'label' => 'Hero — image de fond',  'name' => 'hero_image',   'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ],

			/* ── NOTRE HISTOIRE ──────────────────────────── */
			[ 'key' => 'field_apropos_histoire_tab',     'label' => 'Notre histoire',         'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_histoire_eyebrow', 'label' => 'Histoire — surtitre',    'name' => 'histoire_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_apropos_histoire_title',   'label' => 'Histoire — titre (HTML)','name' => 'histoire_title',   'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_apropos_histoire_body',    'label' => 'Histoire — texte',       'name' => 'histoire_body',    'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_apropos_histoire_image',   'label' => 'Histoire — image',       'name' => 'histoire_image',   'type' => 'image', 'return_format' => 'url' ],

			/* ── NOS VALEURS ─────────────────────────────── */
			[ 'key' => 'field_apropos_valeurs_tab',     'label' => 'Nos valeurs',              'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_valeurs_eyebrow', 'label' => 'Valeurs — surtitre',       'name' => 'valeurs_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_apropos_valeurs_title',   'label' => 'Valeurs — titre (HTML)',   'name' => 'valeurs_title',   'type' => 'textarea', 'rows' => 2 ],

			[ 'key' => 'field_apropos_valeur_1_label', 'label' => 'Valeur 1 — libellé', 'name' => 'valeur_1_label', 'type' => 'text', 'wrapper' => [ 'width' => 35 ] ],
			[ 'key' => 'field_apropos_valeur_1_body',  'label' => 'Valeur 1 — texte',   'name' => 'valeur_1_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 65 ] ],
			[ 'key' => 'field_apropos_valeur_2_label', 'label' => 'Valeur 2 — libellé', 'name' => 'valeur_2_label', 'type' => 'text', 'wrapper' => [ 'width' => 35 ] ],
			[ 'key' => 'field_apropos_valeur_2_body',  'label' => 'Valeur 2 — texte',   'name' => 'valeur_2_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 65 ] ],
			[ 'key' => 'field_apropos_valeur_3_label', 'label' => 'Valeur 3 — libellé', 'name' => 'valeur_3_label', 'type' => 'text', 'wrapper' => [ 'width' => 35 ] ],
			[ 'key' => 'field_apropos_valeur_3_body',  'label' => 'Valeur 3 — texte',   'name' => 'valeur_3_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 65 ] ],
			[ 'key' => 'field_apropos_valeur_4_label', 'label' => 'Valeur 4 — libellé', 'name' => 'valeur_4_label', 'type' => 'text', 'wrapper' => [ 'width' => 35 ] ],
			[ 'key' => 'field_apropos_valeur_4_body',  'label' => 'Valeur 4 — texte',   'name' => 'valeur_4_body',  'type' => 'textarea', 'rows' => 2, 'wrapper' => [ 'width' => 65 ] ],

			/* ── DRUPON KHEN RINPOCHE ─────────────────────── */
			[ 'key' => 'field_apropos_rinpoche_tab',       'label' => 'Drupon Khen Rinpoche',         'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_rinpoche_photo',     'label' => 'Photo (carrée)',                'name' => 'rinpoche_photo',     'type' => 'image', 'return_format' => 'url' ],
			[ 'key' => 'field_apropos_rinpoche_name',      'label' => 'Nom — ligne 1',                'name' => 'rinpoche_name',      'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_apropos_rinpoche_sub_name',  'label' => 'Nom — ligne 2 (en italique)',  'name' => 'rinpoche_sub_name',  'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_apropos_rinpoche_eyebrow',   'label' => 'Surtitre',                     'name' => 'rinpoche_eyebrow',   'type' => 'text' ],
			[ 'key' => 'field_apropos_rinpoche_title',     'label' => 'Titre (HTML)',                  'name' => 'rinpoche_title',     'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_apropos_rinpoche_body',      'label' => 'Texte (deux paragraphes)',      'name' => 'rinpoche_body',      'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_apropos_rinpoche_url',       'label' => 'URL du lien externe',           'name' => 'rinpoche_url',       'type' => 'url' ],
			[ 'key' => 'field_apropos_rinpoche_link_label','label' => 'Texte du lien externe',        'name' => 'rinpoche_link_label','type' => 'text' ],

			/* ── CITATION ─────────────────────────────────── */
			[ 'key' => 'field_apropos_quote_tab',        'label' => 'Citation',                  'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_quote_text',       'label' => 'Texte de la citation',      'name' => 'quote_text',       'type' => 'textarea', 'rows' => 5 ],
			[ 'key' => 'field_apropos_quote_author',     'label' => 'Auteur — nom (ligne 1)',    'name' => 'quote_author',     'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_apropos_quote_author_sub', 'label' => 'Auteur — rôle (ligne 2)',   'name' => 'quote_author_sub', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],

			/* ── LE BUREAU ────────────────────────────────── */
			[ 'key' => 'field_apropos_bureau_tab',   'label' => 'Le bureau',              'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_bureau_eyebrow','label' => 'Surtitre',              'name' => 'bureau_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_apropos_bureau_title', 'label' => 'Titre (HTML)',           'name' => 'bureau_title',   'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_apropos_bureau_intro', 'label' => 'Chapeau',               'name' => 'bureau_intro',   'type' => 'textarea', 'rows' => 3 ],

			/* Membre 1 */
			[ 'key' => 'field_apropos_member_1_role',  'label' => 'Membre 1 — rôle',  'name' => 'member_1_role',  'type' => 'text',    'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_1_name',  'label' => 'Membre 1 — nom',   'name' => 'member_1_name',  'type' => 'text',    'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_apropos_member_1_photo', 'label' => 'Membre 1 — photo', 'name' => 'member_1_photo', 'type' => 'image', 'return_format' => 'url', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_1_bio',   'label' => 'Membre 1 — bio',   'name' => 'member_1_bio',   'type' => 'textarea', 'rows' => 3 ],

			/* Membre 2 */
			[ 'key' => 'field_apropos_member_2_role',  'label' => 'Membre 2 — rôle',  'name' => 'member_2_role',  'type' => 'text',    'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_2_name',  'label' => 'Membre 2 — nom',   'name' => 'member_2_name',  'type' => 'text',    'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_apropos_member_2_photo', 'label' => 'Membre 2 — photo', 'name' => 'member_2_photo', 'type' => 'image', 'return_format' => 'url', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_2_bio',   'label' => 'Membre 2 — bio',   'name' => 'member_2_bio',   'type' => 'textarea', 'rows' => 3 ],

			/* Membre 3 */
			[ 'key' => 'field_apropos_member_3_role',  'label' => 'Membre 3 — rôle',  'name' => 'member_3_role',  'type' => 'text',    'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_3_name',  'label' => 'Membre 3 — nom',   'name' => 'member_3_name',  'type' => 'text',    'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_apropos_member_3_photo', 'label' => 'Membre 3 — photo', 'name' => 'member_3_photo', 'type' => 'image', 'return_format' => 'url', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_3_bio',   'label' => 'Membre 3 — bio',   'name' => 'member_3_bio',   'type' => 'textarea', 'rows' => 3 ],

			/* Membre 4 */
			[ 'key' => 'field_apropos_member_4_role',  'label' => 'Membre 4 — rôle',  'name' => 'member_4_role',  'type' => 'text',    'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_4_name',  'label' => 'Membre 4 — nom',   'name' => 'member_4_name',  'type' => 'text',    'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_apropos_member_4_photo', 'label' => 'Membre 4 — photo', 'name' => 'member_4_photo', 'type' => 'image', 'return_format' => 'url', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_4_bio',   'label' => 'Membre 4 — bio',   'name' => 'member_4_bio',   'type' => 'textarea', 'rows' => 3 ],

			/* Membre 5 */
			[ 'key' => 'field_apropos_member_5_role',  'label' => 'Membre 5 — rôle',  'name' => 'member_5_role',  'type' => 'text',    'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_5_name',  'label' => 'Membre 5 — nom',   'name' => 'member_5_name',  'type' => 'text',    'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_apropos_member_5_photo', 'label' => 'Membre 5 — photo', 'name' => 'member_5_photo', 'type' => 'image', 'return_format' => 'url', 'wrapper' => [ 'width' => 30 ] ],
			[ 'key' => 'field_apropos_member_5_bio',   'label' => 'Membre 5 — bio',   'name' => 'member_5_bio',   'type' => 'textarea', 'rows' => 3 ],

			/* ── RÉSEAU DROLUNG ───────────────────────────── */
			[ 'key' => 'field_apropos_reseau_tab',    'label' => 'Le réseau Drolung',     'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_reseau_eyebrow','label' => 'Surtitre',              'name' => 'reseau_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_apropos_reseau_title',  'label' => 'Titre (HTML)',           'name' => 'reseau_title',   'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_apropos_reseau_body',   'label' => 'Texte',                 'name' => 'reseau_body',    'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_apropos_reseau_image',  'label' => 'Image',                 'name' => 'reseau_image',   'type' => 'image', 'return_format' => 'url' ],
		],
	] );

	/* ─────────────────────────────────────────────────────────
	 * NOTRE ACTION PAGE.
	 * Bound to the auto-created page with slug 'notre-action'.
	 * 4 axes (fixed-length numbered fields) + intro two-col + dark principles.
	 * Updated 2026-06-16: added intro_eyebrow, axes_*, axe_4_*, principe_*.
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_notre_action',
		'title'    => 'Notre action — contenu éditable',
		'location' => drolung_acf_page_location( 'notre-action' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'fields'          => [
			/* ── HERO ─────────────────────────────────────── */
			[ 'key' => 'field_action_hero_eyebrow', 'label' => 'Hero — surtitre',    'name' => 'hero_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_action_hero_title',   'label' => 'Hero — titre (HTML)','name' => 'hero_title',   'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_action_hero_sub',     'label' => 'Hero — sous-titre',  'name' => 'hero_sub',     'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],

			/* ── INTRO TWO-COL ───────────────────────────── */
			[ 'key' => 'field_action_intro_tab',     'label' => 'Intro (deux colonnes)', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_action_intro_eyebrow', 'label' => 'Intro — surtitre (ex : « Notre rôle »)', 'name' => 'intro_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_action_intro_title',   'label' => 'Intro — titre (HTML)',  'name' => 'intro_title', 'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_action_intro_body',    'label' => 'Intro — texte (colonne droite)',  'name' => 'intro_body',  'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],

			/* ── AXES SECTION HEADER ─────────────────────── */
			[ 'key' => 'field_action_axes_tab',     'label' => 'Axes d\'action', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_action_axes_eyebrow', 'label' => 'Axes — surtitre', 'name' => 'axes_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_action_axes_title',   'label' => 'Axes — titre (HTML)', 'name' => 'axes_title', 'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_action_axes_body',    'label' => 'Axes — chapeau', 'name' => 'axes_body', 'type' => 'textarea', 'rows' => 3 ],

			/* Axe 1 */
			[ 'key' => 'field_action_axe_1_tag',   'label' => 'Axe 1 — étiquette (Éducation, Santé…)', 'name' => 'axe_1_tag',   'type' => 'text', 'instructions' => 'Le mot-clé court affiché en haut de la carte.' ],
			[ 'key' => 'field_action_axe_1_title', 'label' => 'Axe 1 — titre',   'name' => 'axe_1_title', 'type' => 'text' ],
			[ 'key' => 'field_action_axe_1_body',  'label' => 'Axe 1 — texte',   'name' => 'axe_1_body',  'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_action_axe_1_image', 'label' => 'Axe 1 — image',   'name' => 'axe_1_image', 'type' => 'image', 'return_format' => 'url' ],

			/* Axe 2 */
			[ 'key' => 'field_action_axe_2_tag',   'label' => 'Axe 2 — étiquette', 'name' => 'axe_2_tag',   'type' => 'text' ],
			[ 'key' => 'field_action_axe_2_title', 'label' => 'Axe 2 — titre',   'name' => 'axe_2_title', 'type' => 'text' ],
			[ 'key' => 'field_action_axe_2_body',  'label' => 'Axe 2 — texte',   'name' => 'axe_2_body',  'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_action_axe_2_image', 'label' => 'Axe 2 — image',   'name' => 'axe_2_image', 'type' => 'image', 'return_format' => 'url' ],

			/* Axe 3 */
			[ 'key' => 'field_action_axe_3_tag',   'label' => 'Axe 3 — étiquette', 'name' => 'axe_3_tag',   'type' => 'text' ],
			[ 'key' => 'field_action_axe_3_title', 'label' => 'Axe 3 — titre',   'name' => 'axe_3_title', 'type' => 'text' ],
			[ 'key' => 'field_action_axe_3_body',  'label' => 'Axe 3 — texte',   'name' => 'axe_3_body',  'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_action_axe_3_image', 'label' => 'Axe 3 — image',   'name' => 'axe_3_image', 'type' => 'image', 'return_format' => 'url' ],

			/* Axe 4 (added 2026-06-16 — Eau & Assainissement) */
			[ 'key' => 'field_action_axe_4_tag',   'label' => 'Axe 4 — étiquette', 'name' => 'axe_4_tag',   'type' => 'text' ],
			[ 'key' => 'field_action_axe_4_title', 'label' => 'Axe 4 — titre',   'name' => 'axe_4_title', 'type' => 'text' ],
			[ 'key' => 'field_action_axe_4_body',  'label' => 'Axe 4 — texte',   'name' => 'axe_4_body',  'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
			[ 'key' => 'field_action_axe_4_image', 'label' => 'Axe 4 — image',   'name' => 'axe_4_image', 'type' => 'image', 'return_format' => 'url' ],

			/* ── DARK SECTION — PRINCIPES / ENGAGEMENTS ─── */
			[ 'key' => 'field_action_principes_tab',     'label' => 'Section principes / engagements', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_action_principes_eyebrow', 'label' => 'Principes — surtitre', 'name' => 'principes_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_action_principes_title',   'label' => 'Principes — titre (HTML)', 'name' => 'principes_title', 'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_action_principes_body',    'label' => 'Principes — chapeau', 'name' => 'principes_body', 'type' => 'textarea', 'rows' => 3 ],

			[ 'key' => 'field_action_principe_1_label', 'label' => 'Principe 1 — libellé', 'name' => 'principe_1_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_action_principe_1_body',  'label' => 'Principe 1 — texte',  'name' => 'principe_1_body',  'type' => 'textarea', 'rows' => 3, 'wrapper' => [ 'width' => 60 ] ],

			[ 'key' => 'field_action_principe_2_label', 'label' => 'Principe 2 — libellé', 'name' => 'principe_2_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_action_principe_2_body',  'label' => 'Principe 2 — texte',  'name' => 'principe_2_body',  'type' => 'textarea', 'rows' => 3, 'wrapper' => [ 'width' => 60 ] ],

			[ 'key' => 'field_action_principe_3_label', 'label' => 'Principe 3 — libellé', 'name' => 'principe_3_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_action_principe_3_body',  'label' => 'Principe 3 — texte',  'name' => 'principe_3_body',  'type' => 'textarea', 'rows' => 3, 'wrapper' => [ 'width' => 60 ] ],

			[ 'key' => 'field_action_principe_4_label', 'label' => 'Principe 4 — libellé', 'name' => 'principe_4_label', 'type' => 'text', 'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_action_principe_4_body',  'label' => 'Principe 4 — texte',  'name' => 'principe_4_body',  'type' => 'textarea', 'rows' => 3, 'wrapper' => [ 'width' => 60 ] ],
		],
	] );

	/* ─────────────────────────────────────────────────────────
	 * RESSOURCES PAGE.
	 * Bound to the auto-created page with slug 'ressources'.
	 * Placeholder "Bientôt disponible" — contenu éditable.
	 * Portée 2026-06-16.
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_ressources',
		'title'    => 'Ressources — contenu éditable',
		'location' => drolung_acf_page_location( 'ressources' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'fields'          => [
			[ 'key' => 'field_ressources_eyebrow',    'label' => 'Surtitre (ex : « Bientôt disponible »)',       'name' => 'ressources_eyebrow',    'type' => 'text' ],
			[ 'key' => 'field_ressources_title',      'label' => 'Titre (HTML — utilise <em>mot</em>)',          'name' => 'ressources_title',      'type' => 'textarea', 'rows' => 2, 'instructions' => 'Utilise <em>mot</em> pour mettre en italique doré.' ],
			[ 'key' => 'field_ressources_body',       'label' => 'Texte du placeholder',                        'name' => 'ressources_body',       'type' => 'textarea', 'rows' => 4 ],
			[ 'key' => 'field_ressources_cta1_label', 'label' => 'Bouton 1 — texte',                            'name' => 'ressources_cta1_label', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_ressources_cta1_url',   'label' => 'Bouton 1 — URL',                              'name' => 'ressources_cta1_url',   'type' => 'url',  'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_ressources_cta2_label', 'label' => 'Bouton 2 — texte',                            'name' => 'ressources_cta2_label', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_ressources_cta2_url',   'label' => 'Bouton 2 — URL',                              'name' => 'ressources_cta2_url',   'type' => 'url',  'wrapper' => [ 'width' => 50 ] ],
		],
	] );

	/* ─────────────────────────────────────────────────────────
	 * S'ENGAGER PAGE.
	 * Bound by slug (same pattern as a-propos, notre-action).
	 * Uses a URL-condition so the group shows on both DSF and DSM:
	 *   slug == s-engager  (matches any site that has this page).
	 * Portée 2026-06-16.
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_engager',
		'title'    => 'S\'engager — contenu éditable',
		'location' => drolung_acf_page_location( 's-engager' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'label_placement' => 'top',
		'fields'          => [

			/* ── HERO ─────────────────────────────────────── */
			[ 'key' => 'field_engager_hero_tab',     'label' => 'Hero',                  'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_engager_hero_eyebrow', 'label' => 'Hero — surtitre',       'name' => 'engager_hero_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_engager_hero_title',   'label' => 'Hero — titre (HTML)',   'name' => 'engager_hero_title',   'type' => 'textarea', 'rows' => 2, 'instructions' => 'Utilise <em>mot</em> pour mettre en italique doré.' ],
			[ 'key' => 'field_engager_hero_sub',     'label' => 'Hero — sous-titre',     'name' => 'engager_hero_sub',     'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],

			/* ── SECTION DON ──────────────────────────────── */
			[ 'key' => 'field_engager_don_tab',           'label' => 'Section don',             'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_engager_don_eyebrow',       'label' => 'Don — surtitre',           'name' => 'engager_don_eyebrow',       'type' => 'text' ],
			[ 'key' => 'field_engager_don_title',         'label' => 'Don — titre (HTML)',       'name' => 'engager_don_title',         'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_engager_don_intro',         'label' => 'Don — phrase intro',       'name' => 'engager_don_intro',         'type' => 'textarea', 'rows' => 3 ],
			[ 'key' => 'field_engager_don_body',          'label' => 'Don — corps (HTML — liste exemples)',  'name' => 'engager_don_body',  'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0,
			                                                'instructions' => 'Pour DSF : liste des coûts projets (le formulaire AssoConnect est inséré automatiquement dessous). Pour DSM : boîte de renvoi vers DSF.' ],
			[ 'key' => 'field_engager_assoconnect_url',   'label' => 'Don — URL AssoConnect',    'name' => 'engager_assoconnect_url', 'type' => 'url',
			                                                'instructions' => 'Lien direct vers le formulaire de collecte AssoConnect (le visiteur y est redirigé, pas de formulaire intégré sur le site — l\'intégration en iframe a été abandonnée, cf. journal technique). Vide = bouton "Nous contacter" à la place. Pré-rempli automatiquement pour DSF.' ],
			[ 'key' => 'field_engager_don_cta_label',     'label' => 'Don — texte du bouton (si pas de formulaire)',  'name' => 'engager_don_cta_label',  'type' => 'text' ],
			[ 'key' => 'field_engager_don_cta_url',       'label' => 'Don — URL du bouton (si pas de formulaire)',    'name' => 'engager_don_cta_url',    'type' => 'url',
			                                                'instructions' => 'Laisser vide pour utiliser /contact/ par défaut.' ],
			[ 'key' => 'field_engager_don_image',         'label' => 'Don — image',              'name' => 'engager_don_image',         'type' => 'image', 'return_format' => 'url' ],
			[ 'key' => 'field_engager_don_image_alt',     'label' => 'Don — alt image',          'name' => 'engager_don_image_alt',     'type' => 'text' ],

			/* ── SECTION PARTAGE ──────────────────────────── */
			[ 'key' => 'field_engager_partage_tab',       'label' => 'Section partage',          'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_engager_partage_eyebrow',   'label' => 'Partage — surtitre',       'name' => 'engager_partage_eyebrow',   'type' => 'text' ],
			[ 'key' => 'field_engager_partage_title',     'label' => 'Partage — titre (HTML)',   'name' => 'engager_partage_title',     'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_engager_partage_body',      'label' => 'Partage — texte',          'name' => 'engager_partage_body',      'type' => 'textarea', 'rows' => 4 ],
			[ 'key' => 'field_engager_facebook_url',      'label' => 'Lien Facebook',            'name' => 'engager_facebook_url',      'type' => 'url' ],
			[ 'key' => 'field_engager_linkedin_url',      'label' => 'Lien LinkedIn',            'name' => 'engager_linkedin_url',      'type' => 'url' ],
			[ 'key' => 'field_engager_instagram_url',     'label' => 'Lien Instagram',           'name' => 'engager_instagram_url',     'type' => 'url' ],
			[ 'key' => 'field_engager_partage_image',     'label' => 'Partage — image',          'name' => 'engager_partage_image',     'type' => 'image', 'return_format' => 'url' ],
			[ 'key' => 'field_engager_partage_image_alt', 'label' => 'Partage — alt image',      'name' => 'engager_partage_image_alt', 'type' => 'text' ],

			/* ── SECTION PARTENARIAT ──────────────────────── */
			[ 'key' => 'field_engager_partenariat_tab',       'label' => 'Section partenariat',        'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_engager_partenariat_eyebrow',   'label' => 'Partenariat — surtitre',     'name' => 'engager_partenariat_eyebrow',   'type' => 'text' ],
			[ 'key' => 'field_engager_partenariat_title',     'label' => 'Partenariat — titre (HTML)', 'name' => 'engager_partenariat_title',     'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_engager_partenariat_body',      'label' => 'Partenariat — texte',        'name' => 'engager_partenariat_body',      'type' => 'textarea', 'rows' => 4 ],
			[ 'key' => 'field_engager_partenariat_cta_label', 'label' => 'Partenariat — texte bouton', 'name' => 'engager_partenariat_cta_label', 'type' => 'text' ],

			/* Mécénat cards */
			[ 'key' => 'field_engager_mecenat_1_title', 'label' => 'Carte mécénat 1 — titre', 'name' => 'engager_mecenat_1_title', 'type' => 'text',     'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_engager_mecenat_1_body',  'label' => 'Carte mécénat 1 — texte', 'name' => 'engager_mecenat_1_body',  'type' => 'textarea', 'rows' => 3, 'wrapper' => [ 'width' => 60 ] ],
			[ 'key' => 'field_engager_mecenat_2_title', 'label' => 'Carte mécénat 2 — titre', 'name' => 'engager_mecenat_2_title', 'type' => 'text',     'wrapper' => [ 'width' => 40 ] ],
			[ 'key' => 'field_engager_mecenat_2_body',  'label' => 'Carte mécénat 2 — texte', 'name' => 'engager_mecenat_2_body',  'type' => 'textarea', 'rows' => 3, 'wrapper' => [ 'width' => 60 ] ],
		],
	] );

	/* ─────────────────────────────────────────────────────────
	 * CONTACT PAGE.
	 * Rendered by page-contact.php (matched via WP's page-{slug}.php
	 * template hierarchy. Resolved by slug (+ its translations) rather
	 * than `page_template`, matching every other page-specific group.
	 * Portée 2026-06-16, corrigé 2026-07-10, généralisé 2026-07-11.
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_contact',
		'title'    => 'Contact — contenu éditable',
		'location' => drolung_acf_page_location( 'contact' ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'label_placement' => 'top',
		'fields'          => [

			/* ── TEXTES INTRO ─────────────────────────────── */
			[ 'key' => 'field_contact_eyebrow',        'label' => 'Surtitre (ex : « Restons en contact »)',            'name' => 'contact_eyebrow',        'type' => 'text' ],
			[ 'key' => 'field_contact_title',          'label' => 'Titre (HTML — utilise <em>mot</em> et <br>)',        'name' => 'contact_title',          'type' => 'textarea', 'rows' => 2, 'instructions' => 'Utilise <br> pour le saut de ligne et <em>mot</em> pour l\'italique doré.' ],
			[ 'key' => 'field_contact_sub',            'label' => 'Texte',            'name' => 'contact_sub',            'type' => 'wysiwyg', 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0 ],

			/* ── COORDONNÉES ──────────────────────────────── */
			[ 'key' => 'field_contact_email',          'label' => 'Adresse e-mail de contact',                         'name' => 'contact_email',          'type' => 'email' ],
			[ 'key' => 'field_contact_network_label',  'label' => 'Libellé lien réseau (ex : « Réseau Drolung »)',      'name' => 'contact_network_label',  'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_contact_network_url',    'label' => 'URL du site réseau (ex : https://drolung.org)',      'name' => 'contact_network_url',    'type' => 'url',  'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_contact_network_display','label' => 'Texte affiché du lien réseau (ex : drolung.org)',    'name' => 'contact_network_display','type' => 'text' ],
		],
	] );

	/*
	 * NB: pas de groupe de champs "Single projet" ici — le groupe
	 * `group_drolung_single_projet` qui vivait à cet endroit ne
	 * nourrissait qu'un chemin de rendu mort (le thème central est
	 * drolung-org, pas drolung-branch ; single-projet.php n'est jamais
	 * chargé via le hiérarchie de templates de WP sur le site central).
	 * Un de ses champs ("photos", en double avec field_prj_photos du
	 * groupe réseau) écrasait même les vraies données à l'enregistrement.
	 * Supprimé le 2026-07-10 — voir docs/tech-network.md §15. Le contenu
	 * éditorial du single projet (récit, badges, galerie…) est piloté
	 * depuis `drolung_item()` (drolung-network) ; tout enrichissement
	 * futur doit passer par ce système, pas par un groupe ACF théorique.
	 */

	/* ─────────────────────────────────────────────────────────
	 * PROJETS ARCHIVE.
	 * Shared hero + intro copy for the /projets/ archive listing page.
	 * Per-post data (title, budget, location) comes from WP core /
	 * get_post_meta() — not from ACF fields registered here.
	 * Portée 2026-06-16.
	 *
	 * Location fixed 2026-07-11: this used to be `post_type == projet`,
	 * which attached the group to *every individual project's* edit
	 * screen — confusing (it looks like a per-project field, but it
	 * controls the shared archive page) and broken on top of that: the
	 * archive page has no single "current project" to read a post ID
	 * from (`drolung_field()` with no explicit ID silently fell back to
	 * the hardcoded default every time, regardless of what was set on
	 * any project). Moved to the network options page — read via
	 * `drolung_get_network_option()` (helpers.php), not `drolung_field()`.
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( array(
		'key'      => 'group_drolung_projets_archive',
		'title'    => 'Archive projets — contenu éditable',
		'location' => array( array( array(
			'param'    => 'options_page',
			'operator' => '==',
			'value'    => 'drolung-network-settings',
		) ) ),
		'menu_order'      => 0,
		'position'        => 'normal',
		'label_placement' => 'top',
		'fields'          => array(

			/* ── HERO ─────────────────────────────────────── */
			array( 'key' => 'field_projets_hero_tab',     'label' => 'Hero',                  'name' => '', 'type' => 'tab', 'placement' => 'top' ),
			array( 'key' => 'field_projets_hero_eyebrow', 'label' => 'Hero — surtitre',       'name' => 'projets_hero_eyebrow', 'type' => 'text' ),
			array( 'key' => 'field_projets_hero_title',   'label' => 'Hero — titre (HTML)',   'name' => 'projets_hero_title',   'type' => 'textarea', 'rows' => 2,
			       'instructions' => 'Utilise <em>mot</em> pour mettre un mot en italique doré.' ),
			array( 'key' => 'field_projets_hero_sub',     'label' => 'Hero — sous-titre',     'name' => 'projets_hero_sub',     'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ),
			array( 'key' => 'field_projets_hero_image',   'label' => 'Hero — image de fond',  'name' => 'projets_hero_image',   'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

			/* ── INTRO ───────────────────────────────────── */
			array( 'key' => 'field_projets_intro_tab',    'label' => 'Section intro',         'name' => '', 'type' => 'tab' ),
			array( 'key' => 'field_projets_intro_eyebrow','label' => 'Intro — surtitre',      'name' => 'projets_intro_eyebrow', 'type' => 'text' ),
			array( 'key' => 'field_projets_intro_title',  'label' => 'Intro — titre (HTML)',  'name' => 'projets_intro_title',   'type' => 'textarea', 'rows' => 2,
			       'instructions' => 'Utilise <em>mot</em> pour mettre en italique doré.' ),
			array( 'key' => 'field_projets_intro_body',   'label' => 'Intro — texte',         'name' => 'projets_intro_body',    'type' => 'textarea', 'rows' => 4 ),
		),
	) );
}

/**
 * Resolve a page ID from its slug on the current site.
 * Returns 0 if not found (effectively disabling the location rule on
 * sites where that page hasn't been created yet — harmless).
 */
function drolung_acf_page_id_by_slug( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? $page->ID : 0;
}

/**
 * IDs of a page (found by its default-language slug) plus all of its
 * Polylang translations — e.g. slug 'a-propos' → [fr_id, en_id, zh_id].
 * Use this for any per-page ACF group's 'location' so the field group
 * follows every language version of the page, not just the one whose
 * slug happens to match. Without this, translated pages (different
 * slug) silently lose their ACF fields — see the front-page / contact
 * fixes this pattern replaces and generalizes (doc journal §15).
 *
 * @return int[] Never empty when the page exists; [0] (harmless no-op
 *               location) when it doesn't exist yet on this site.
 */
function drolung_acf_translated_page_ids( $slug ) {
	$id = drolung_acf_page_id_by_slug( $slug );
	if ( ! $id ) {
		return array( 0 );
	}
	$ids = array( $id );
	if ( function_exists( 'pll_get_post_translations' ) ) {
		$ids = array_merge( $ids, array_values( pll_get_post_translations( $id ) ) );
	}
	return array_values( array_unique( array_map( 'intval', $ids ) ) );
}

/**
 * ACF 'location' array (OR'd rule groups) matching a page and all of
 * its translations. Pass as the 'location' value of acf_add_local_field_group().
 */
function drolung_acf_page_location( $slug ) {
	return array_map(
		function ( $id ) {
			return array( array( 'param' => 'page', 'operator' => '==', 'value' => $id ) );
		},
		drolung_acf_translated_page_ids( $slug )
	);
}

/**
 * IDs of the static front page in every language: the literal
 * `page_on_front` option plus, when Polylang is active, all of its
 * linked translations (e.g. an English "Homepage" page).
 *
 * A `page_type == front_page` ACF location rule only ever matches the
 * option's literal ID, so it silently hides the field group on
 * translated front pages — use this instead wherever a "front page"
 * ACF group needs to work across languages too.
 */
function drolung_acf_front_page_ids() {
	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) {
		return array();
	}

	$ids = array( $front_id );
	if ( function_exists( 'pll_get_post_translations' ) ) {
		$ids = array_merge( $ids, array_values( pll_get_post_translations( $front_id ) ) );
	}

	return array_values( array_unique( array_map( 'intval', $ids ) ) );
}
