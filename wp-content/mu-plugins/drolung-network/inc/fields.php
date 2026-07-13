<?php
/**
 * Schéma de champs ACF des types réseau (doc §3 / §5).
 *
 * Enregistré en PHP (versionné), uniquement sur le site central —
 * seul site où ces types ont une UI. Champs *neutres* (synchronisés
 * entre traductions par Polylang, doc §4) ; les textes traduisibles
 * vivent dans titre/contenu/extrait natifs.
 *
 * Le champ gallery (photos) requiert ACF Pro : il n'est enregistré
 * que si le type de champ existe, le reste du schéma fonctionne
 * avec ACF free en attendant la licence.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/init', 'drolung_network_register_fields' );
function drolung_network_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) || ! is_main_site() ) {
		return;
	}

	$has_gallery = function_exists( 'acf_get_field_type' ) && acf_get_field_type( 'gallery' );

	/* ─────────────────────────────────────────────────────────
	 * PROJET
	 * ───────────────────────────────────────────────────────── */
	$projet_fields = array(

		/* ── Détails (neutres) ── */
		array( 'key' => 'field_prj_tab_details', 'label' => 'Details', 'name' => '', 'type' => 'tab', 'placement' => 'top' ),
		array(
			'key'          => 'field_prj_code',
			'label'        => 'Project Code',
			'name'         => 'code_projet',
			'type'         => 'text',
			'instructions' => 'Registry code: [ENTITY]-[SECTOR]-[LOCATION]-[YEAR]-[SEQ]. AssoConnect accounting reconciliation key.',
			'wrapper'      => array( 'width' => 40 ),
		),
		array( 'key' => 'field_prj_budget', 'label' => 'Budget (EUR)', 'name' => 'budget_eur', 'type' => 'number', 'min' => 0, 'wrapper' => array( 'width' => 30 ) ),
		array(
			'key'          => 'field_prj_collecte',
			'label'        => 'Amount Raised (EUR)',
			'name'         => 'montant_collecte_eur',
			'type'         => 'number',
			'min'          => 0,
			'instructions' => 'Manual update (monthly process) — no AssoConnect sync.',
			'wrapper'      => array( 'width' => 30 ),
		),
		array( 'key' => 'field_prj_debut', 'label' => 'Start Date', 'name' => 'date_debut', 'type' => 'date_picker', 'display_format' => 'd/m/Y', 'return_format' => 'Y-m-d', 'wrapper' => array( 'width' => 50 ) ),
		array( 'key' => 'field_prj_fin', 'label' => 'End Date (planned)', 'name' => 'date_fin', 'type' => 'date_picker', 'display_format' => 'd/m/Y', 'return_format' => 'Y-m-d', 'wrapper' => array( 'width' => 50 ) ),
		array( 'key' => 'field_prj_benef_nb', 'label' => 'Beneficiaries (number)', 'name' => 'beneficiaires_nombre', 'type' => 'number', 'min' => 0, 'wrapper' => array( 'width' => 40 ) ),
		array( 'key' => 'field_prj_benef_desc', 'label' => 'Beneficiaries (short description)', 'name' => 'beneficiaires_description', 'type' => 'text', 'wrapper' => array( 'width' => 60 ) ),

		/* ── Localisation ── */
		array( 'key' => 'field_prj_tab_loc', 'label' => 'Location', 'name' => '', 'type' => 'tab' ),
		array(
			'key'        => 'field_prj_localisation',
			'label'      => 'Location',
			'name'       => 'localisation',
			'type'       => 'group',
			'layout'     => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_prj_loc_region', 'label' => 'Region', 'name' => 'region', 'type' => 'text', 'wrapper' => array( 'width' => 40 ) ),
				array( 'key' => 'field_prj_loc_commune', 'label' => 'Town / village', 'name' => 'commune', 'type' => 'text', 'wrapper' => array( 'width' => 40 ) ),
				array( 'key' => 'field_prj_loc_gps', 'label' => 'GPS (lat,lng)', 'name' => 'gps', 'type' => 'text', 'wrapper' => array( 'width' => 20 ) ),
			),
		),

		/* ── Dons (doc §7 — routage par branche) ── */
		array( 'key' => 'field_prj_tab_dons', 'label' => 'Donations', 'name' => '', 'type' => 'tab' ),
		array(
			'key'        => 'field_prj_dons',
			'label'      => 'Donation instruments per branch',
			'name'       => 'dons',
			'type'       => 'group',
			'layout'     => 'block',
			'instructions' => 'Leave empty = the site\'s donate button falls back to the entity\'s generic donation form.',
			'sub_fields' => array(
				array( 'key' => 'field_prj_don_dsf', 'label' => 'DSF — AssoConnect form URL', 'name' => 'assoconnect_url', 'type' => 'url' ),
				array( 'key' => 'field_prj_don_duk', 'label' => 'DUK — Stripe URL (Payment Link)', 'name' => 'duk_url', 'type' => 'url' ),
				array( 'key' => 'field_prj_don_dsm', 'label' => 'DSM — local donation info (MVola…)', 'name' => 'dsm_info', 'type' => 'text' ),
			),
		),

		/* ── Réseau ── */
		array( 'key' => 'field_prj_tab_reseau', 'label' => 'Network', 'name' => '', 'type' => 'tab' ),
		array(
			'key'           => 'field_prj_featured_home',
			'label'         => 'Feature on homepage',
			'name'          => 'featured_home',
			'type'          => 'true_false',
			'instructions'  => 'Checked = this project appears in the "Our Projects" block on the homepage of its branch(es). Check at most 4 projects per branch — the homepage only shows 4.',
			'ui'            => 1,
			'default_value' => 0,
		),
		array(
			'key'           => 'field_prj_canonical',
			'label'         => 'Canonical Site (SEO)',
			'name'          => 'site_canonical',
			'type'          => 'select',
			'instructions'  => 'The "official" version for Google (doc §10). Defaults to DSF: that\'s where international donors can give.',
			'choices'       => array(
				'dsf'     => 'DSF — dsf.drolung.org',
				'dsm'     => 'DSM — dsm.drolung.org',
				'duk'     => 'DUK — duk.drolung.org',
				'org'     => 'Central — drolung.org',
			),
			'default_value' => 'dsf',
			'return_format' => 'value',
		),
		array(
			'key'           => 'field_prj_partenaires',
			'label'         => 'Partners',
			'name'          => 'partenaires',
			'type'          => 'relationship',
			'post_type'     => array( 'partenaire' ),
			'filters'       => array( 'search' ),
			'return_format' => 'id',
			'instructions'  => 'Include historical implementing partners (e.g. edu4mada for the Women\'s School) — honest attribution, doc §11.',
		),
	);

	/* Photothèque — ACF Pro uniquement (doc §5). */
	if ( $has_gallery ) {
		$projet_fields[] = array( 'key' => 'field_prj_tab_photos', 'label' => 'Photos', 'name' => '', 'type' => 'tab' );
		$projet_fields[] = array(
			'key'           => 'field_prj_photos',
			'label'         => 'Project Photo Library',
			'name'          => 'photos',
			'type'          => 'gallery',
			'return_format' => 'id',
			'instructions'  => 'Official photo library (sliders, headers). Photo policy doc §11: documented consent, no identifiable children (name + face).',
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_drolung_projet',
		'title'    => 'Project — Data',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'projet' ) ) ),
		'position' => 'normal',
		'fields'   => $projet_fields,
	) );

	/* ─────────────────────────────────────────────────────────
	 * PROJET UPDATE
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( array(
		'key'      => 'group_drolung_projet_update',
		'title'    => 'Update — Linked Project',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'projet_update' ) ) ),
		'position' => 'side',
		'fields'   => array(
			array(
				'key'           => 'field_upd_projet',
				'label'         => 'Project',
				'name'          => 'projet',
				'type'          => 'post_object',
				'post_type'     => array( 'projet' ),
				'required'      => 1,
				'return_format' => 'id',
			),
			array(
				'key'           => 'field_upd_restrict',
				'label'         => 'Restrict to specific branches',
				'name'          => 'branches_restreintes',
				'type'          => 'checkbox',
				'choices'       => array( 'dsf' => 'DSF', 'dsm' => 'DSM', 'duk' => 'DUK', 'org' => 'Central' ),
				'instructions'  => 'Empty = inherits the project\'s visibility (normal case).',
				'return_format' => 'value',
			),
		),
	) );

	/* ─────────────────────────────────────────────────────────
	 * PARTENAIRE  (logo = image à la une du CPT)
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( array(
		'key'      => 'group_drolung_partenaire',
		'title'    => 'Partner — Info',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'partenaire' ) ) ),
		'position' => 'normal',
		'fields'   => array(
			array( 'key' => 'field_part_url', 'label' => 'Website', 'name' => 'url', 'type' => 'url' ),
			array( 'key' => 'field_part_role', 'label' => 'Role (short mention)', 'name' => 'role', 'type' => 'text', 'instructions' => 'E.g. "Program run by" / "Technical partner". Displayed as-is.' ),
		),
	) );

	/* ─────────────────────────────────────────────────────────
	 * ARTICLE — canonical seulement (doc §3.4 : champs minimaux)
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( array(
		'key'      => 'group_drolung_article',
		'title'    => 'Article — Network',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'article' ) ) ),
		'position' => 'side',
		'fields'   => array(
			array(
				'key'           => 'field_art_canonical',
				'label'         => 'Canonical Site (SEO)',
				'name'          => 'site_canonical',
				'type'          => 'select',
				'choices'       => array(
					'org' => 'Central — drolung.org',
					'dsf' => 'DSF — dsf.drolung.org',
					'dsm' => 'DSM — dsm.drolung.org',
					'duk' => 'DUK — duk.drolung.org',
				),
				'default_value' => 'org',
				'return_format' => 'value',
			),
		),
	) );
}
