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
 * ───────────────────────────────────────────────────────────── */
if ( ! function_exists( 'drolung_field' ) ) {
	function drolung_field( $key, $default = '', $post_id = false ) {
		if ( ! function_exists( 'get_field' ) ) {
			return $default;
		}
		$value = get_field( $key, $post_id );
		if ( $value === '' || $value === null || $value === false ) {
			return $default;
		}
		return $value;
	}
}

/* ─────────────────────────────────────────────────────────────
 * Register field groups. Hooked late so ACF is loaded first.
 * ───────────────────────────────────────────────────────────── */
add_action( 'acf/init', 'drolung_register_acf_fields' );

function drolung_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ─────────────────────────────────────────────────────────
	 * FRONT PAGE (front-page.php on branch theme).
	 * Location: the static front page (whatever it is on the site).
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_front',
		'title'    => 'Page d\'accueil',
		'location' => [ [ [
			'param'    => 'page_type',
			'operator' => '==',
			'value'    => 'front_page',
		] ] ],
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'fields'          => [

			/* ── HERO ─────────────────────────────────────── */
			[ 'key' => 'field_front_hero_tab',     'label' => 'Hero',                  'name' => '', 'type' => 'tab', 'placement' => 'top' ],
			[ 'key' => 'field_front_hero_eyebrow', 'label' => 'Surtitre (eyebrow)',    'name' => 'hero_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_hero_title',   'label' => 'Titre (HTML autorisé)', 'name' => 'hero_title',   'type' => 'textarea', 'rows' => 3, 'new_lines' => 'br', 'instructions' => 'Tu peux mettre un mot en <em>italique</em> en l\'entourant de balises &lt;em&gt;mot&lt;/em&gt;.' ],
			[ 'key' => 'field_front_hero_sub',     'label' => 'Sous-titre',            'name' => 'hero_sub',     'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ],
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

			/* ── MAP / WHERE WE WORK ─────────────────────── */
			[ 'key' => 'field_front_map_tab',     'label' => 'Zones d\'intervention', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_map_eyebrow', 'label' => 'Surtitre', 'name' => 'map_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_map_title',   'label' => 'Titre (HTML)', 'name' => 'map_title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_front_map_body',    'label' => 'Texte',    'name' => 'map_body',    'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ],

			/* ── TESTIMONIAL ─────────────────────────────── */
			[ 'key' => 'field_front_test_tab',         'label' => 'Témoignage', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_test_text',        'label' => 'Citation',       'name' => 'test_text',        'type' => 'textarea', 'rows' => 4 ],
			[ 'key' => 'field_front_test_author_name', 'label' => 'Nom',            'name' => 'test_author_name', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_front_test_author_role', 'label' => 'Rôle / lieu',    'name' => 'test_author_role', 'type' => 'text', 'wrapper' => [ 'width' => 50 ] ],
			[ 'key' => 'field_front_test_author_photo','label' => 'Photo (carrée)', 'name' => 'test_author_photo','type' => 'image', 'return_format' => 'url' ],

			/* ── DONATE ──────────────────────────────────── */
			[ 'key' => 'field_front_donate_tab',     'label' => 'Faire un don', 'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_front_donate_eyebrow', 'label' => 'Surtitre', 'name' => 'donate_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_front_donate_title',   'label' => 'Titre (HTML)', 'name' => 'donate_title', 'type' => 'textarea', 'rows' => 2, 'new_lines' => '' ],
			[ 'key' => 'field_front_donate_body',    'label' => 'Texte',    'name' => 'donate_body', 'type' => 'textarea', 'rows' => 3, 'new_lines' => 'wpautop' ],
		],
	] );

	/* ─────────────────────────────────────────────────────────
	 * À PROPOS PAGE.
	 * Bound to the auto-created page with slug 'a-propos'.
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( [
		'key'      => 'group_drolung_apropos',
		'title'    => 'À propos — contenu éditable',
		'location' => [ [ [
			'param'    => 'page',
			'operator' => '==',
			'value'    => drolung_acf_page_id_by_slug( 'a-propos' ),
		] ] ],
		'menu_order'      => 0,
		'position'        => 'normal',
		'fields'          => [

			/* ── HERO ─────────────────────────────────────── */
			[ 'key' => 'field_apropos_hero_tab',     'label' => 'Hero',                  'name' => '', 'type' => 'tab' ],
			[ 'key' => 'field_apropos_hero_eyebrow', 'label' => 'Hero — surtitre',       'name' => 'hero_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_apropos_hero_title',   'label' => 'Hero — titre (HTML)',   'name' => 'hero_title',   'type' => 'textarea', 'rows' => 2, 'instructions' => 'Utilise <em>mot</em> pour mettre en italique doré.' ],
			[ 'key' => 'field_apropos_hero_sub',     'label' => 'Hero — sous-titre',     'name' => 'hero_sub',     'type' => 'textarea', 'rows' => 3 ],
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
		'location' => [ [ [
			'param'    => 'page',
			'operator' => '==',
			'value'    => drolung_acf_page_id_by_slug( 'notre-action' ),
		] ] ],
		'menu_order'      => 0,
		'position'        => 'normal',
		'fields'          => [
			/* ── HERO ─────────────────────────────────────── */
			[ 'key' => 'field_action_hero_eyebrow', 'label' => 'Hero — surtitre',    'name' => 'hero_eyebrow', 'type' => 'text' ],
			[ 'key' => 'field_action_hero_title',   'label' => 'Hero — titre (HTML)','name' => 'hero_title',   'type' => 'textarea', 'rows' => 2 ],
			[ 'key' => 'field_action_hero_sub',     'label' => 'Hero — sous-titre',  'name' => 'hero_sub',     'type' => 'textarea', 'rows' => 3 ],

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
