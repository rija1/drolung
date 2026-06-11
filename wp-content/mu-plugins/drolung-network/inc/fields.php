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
		array( 'key' => 'field_prj_tab_details', 'label' => 'Détails', 'name' => '', 'type' => 'tab', 'placement' => 'top' ),
		array(
			'key'          => 'field_prj_code',
			'label'        => 'Code projet',
			'name'         => 'code_projet',
			'type'         => 'text',
			'instructions' => 'Code du registre : [ENTITÉ]-[SECTEUR]-[LIEU]-[ANNÉE]-[SÉQ]. Clé de réconciliation comptable AssoConnect.',
			'wrapper'      => array( 'width' => 40 ),
		),
		array( 'key' => 'field_prj_budget', 'label' => 'Budget (EUR)', 'name' => 'budget_eur', 'type' => 'number', 'min' => 0, 'wrapper' => array( 'width' => 30 ) ),
		array(
			'key'          => 'field_prj_collecte',
			'label'        => 'Montant collecté (EUR)',
			'name'         => 'montant_collecte_eur',
			'type'         => 'number',
			'min'          => 0,
			'instructions' => 'Mise à jour manuelle (process mensuel) — pas de sync AssoConnect.',
			'wrapper'      => array( 'width' => 30 ),
		),
		array( 'key' => 'field_prj_debut', 'label' => 'Date de début', 'name' => 'date_debut', 'type' => 'date_picker', 'display_format' => 'd/m/Y', 'return_format' => 'Y-m-d', 'wrapper' => array( 'width' => 50 ) ),
		array( 'key' => 'field_prj_fin', 'label' => 'Date de fin (prévue)', 'name' => 'date_fin', 'type' => 'date_picker', 'display_format' => 'd/m/Y', 'return_format' => 'Y-m-d', 'wrapper' => array( 'width' => 50 ) ),
		array( 'key' => 'field_prj_benef_nb', 'label' => 'Bénéficiaires (nombre)', 'name' => 'beneficiaires_nombre', 'type' => 'number', 'min' => 0, 'wrapper' => array( 'width' => 40 ) ),
		array( 'key' => 'field_prj_benef_desc', 'label' => 'Bénéficiaires (description courte)', 'name' => 'beneficiaires_description', 'type' => 'text', 'wrapper' => array( 'width' => 60 ) ),

		/* ── Localisation ── */
		array( 'key' => 'field_prj_tab_loc', 'label' => 'Localisation', 'name' => '', 'type' => 'tab' ),
		array(
			'key'        => 'field_prj_localisation',
			'label'      => 'Localisation',
			'name'       => 'localisation',
			'type'       => 'group',
			'layout'     => 'block',
			'sub_fields' => array(
				array( 'key' => 'field_prj_loc_region', 'label' => 'Région', 'name' => 'region', 'type' => 'text', 'wrapper' => array( 'width' => 40 ) ),
				array( 'key' => 'field_prj_loc_commune', 'label' => 'Commune / village', 'name' => 'commune', 'type' => 'text', 'wrapper' => array( 'width' => 40 ) ),
				array( 'key' => 'field_prj_loc_gps', 'label' => 'GPS (lat,lng)', 'name' => 'gps', 'type' => 'text', 'wrapper' => array( 'width' => 20 ) ),
			),
		),

		/* ── Dons (doc §7 — routage par branche) ── */
		array( 'key' => 'field_prj_tab_dons', 'label' => 'Dons', 'name' => '', 'type' => 'tab' ),
		array(
			'key'        => 'field_prj_dons',
			'label'      => 'Instruments de don par branche',
			'name'       => 'dons',
			'type'       => 'group',
			'layout'     => 'block',
			'instructions' => 'Laisser vide = le bouton don du site renvoie au formulaire générique de l\'entité.',
			'sub_fields' => array(
				array( 'key' => 'field_prj_don_dsf', 'label' => 'DSF — URL formulaire AssoConnect', 'name' => 'assoconnect_url', 'type' => 'url' ),
				array( 'key' => 'field_prj_don_duk', 'label' => 'DUK — URL Stripe (Payment Link)', 'name' => 'duk_url', 'type' => 'url' ),
				array( 'key' => 'field_prj_don_dsm', 'label' => 'DSM — infos don local (MVola…)', 'name' => 'dsm_info', 'type' => 'text' ),
			),
		),

		/* ── Réseau ── */
		array( 'key' => 'field_prj_tab_reseau', 'label' => 'Réseau', 'name' => '', 'type' => 'tab' ),
		array(
			'key'           => 'field_prj_canonical',
			'label'         => 'Site canonical (SEO)',
			'name'          => 'site_canonical',
			'type'          => 'select',
			'instructions'  => 'Version "officielle" pour Google (doc §10). Par défaut DSF : c\'est là que le donateur international peut donner.',
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
			'label'         => 'Partenaires',
			'name'          => 'partenaires',
			'type'          => 'relationship',
			'post_type'     => array( 'partenaire' ),
			'filters'       => array( 'search' ),
			'return_format' => 'id',
			'instructions'  => 'Inclure les porteurs historiques (ex. edu4mada pour l\'École des femmes) — attribution honnête, doc §11.',
		),
	);

	/* Photothèque — ACF Pro uniquement (doc §5). */
	if ( $has_gallery ) {
		$projet_fields[] = array( 'key' => 'field_prj_tab_photos', 'label' => 'Photos', 'name' => '', 'type' => 'tab' );
		$projet_fields[] = array(
			'key'           => 'field_prj_photos',
			'label'         => 'Photothèque du projet',
			'name'          => 'photos',
			'type'          => 'gallery',
			'return_format' => 'id',
			'instructions'  => 'Photothèque officielle (sliders, headers). Politique photo doc §11 : consentement documenté, pas d\'enfants identifiables nom + visage.',
		);
	}

	acf_add_local_field_group( array(
		'key'      => 'group_drolung_projet',
		'title'    => 'Projet — données',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'projet' ) ) ),
		'position' => 'normal',
		'fields'   => $projet_fields,
	) );

	/* ─────────────────────────────────────────────────────────
	 * PROJET UPDATE
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( array(
		'key'      => 'group_drolung_projet_update',
		'title'    => 'Update — rattachement',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'projet_update' ) ) ),
		'position' => 'side',
		'fields'   => array(
			array(
				'key'           => 'field_upd_projet',
				'label'         => 'Projet',
				'name'          => 'projet',
				'type'          => 'post_object',
				'post_type'     => array( 'projet' ),
				'required'      => 1,
				'return_format' => 'id',
			),
			array(
				'key'           => 'field_upd_restrict',
				'label'         => 'Restreindre à certaines branches',
				'name'          => 'branches_restreintes',
				'type'          => 'checkbox',
				'choices'       => array( 'dsf' => 'DSF', 'dsm' => 'DSM', 'duk' => 'DUK', 'org' => 'Central' ),
				'instructions'  => 'Vide = hérite de la visibilité du projet (cas normal).',
				'return_format' => 'value',
			),
		),
	) );

	/* ─────────────────────────────────────────────────────────
	 * PARTENAIRE  (logo = image à la une du CPT)
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( array(
		'key'      => 'group_drolung_partenaire',
		'title'    => 'Partenaire — infos',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'partenaire' ) ) ),
		'position' => 'normal',
		'fields'   => array(
			array( 'key' => 'field_part_url', 'label' => 'Site web', 'name' => 'url', 'type' => 'url' ),
			array( 'key' => 'field_part_role', 'label' => 'Rôle (mention courte)', 'name' => 'role', 'type' => 'text', 'instructions' => 'Ex. « Programme porté par » / « Partenaire technique ». Affiché tel quel.' ),
		),
	) );

	/* ─────────────────────────────────────────────────────────
	 * ARTICLE — canonical seulement (doc §3.4 : champs minimaux)
	 * ───────────────────────────────────────────────────────── */
	acf_add_local_field_group( array(
		'key'      => 'group_drolung_article',
		'title'    => 'Article — réseau',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'article' ) ) ),
		'position' => 'side',
		'fields'   => array(
			array(
				'key'           => 'field_art_canonical',
				'label'         => 'Site canonical (SEO)',
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
