<?php
/**
 * One-shot : crée les 4 projets initiaux (DSM/DSF) sur le site central.
 *
 * Gate : site option drolung_projets_seeded_v1.
 * Pour forcer la re-création : delete_site_option( 'drolung_projets_seeded_v1' ).
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'drolung_seed_projets', 15 );
function drolung_seed_projets() {
	if ( ! is_main_site() ) {
		return;
	}
	if ( get_site_option( 'drolung_projets_seeded_v1' ) ) {
		return;
	}

	$projets = array(

		array(
			'post_title'   => 'Eau potable · Ambohitrolomahitsy',
			'post_name'    => 'eau-potable-ambohitrolomahitsy',
			'post_excerpt' => '1 300 habitants sans eau potable fiable depuis 15 ans. Ce projet, évalué par MDF, prévoit 3 captages de source, un réservoir de 28 m³ et 10 bornes-fontaines gravitaires.',
			'post_content' => '<p>1 300 habitants sans eau potable fiable depuis 15 ans. Ce projet, évalué par Madagascar Development Fund, prévoit 3 captages de source, un réservoir de 28 m³ et 10 bornes-fontaines gravitaires desservant les différents quartiers du village d\'Ambohitrolomahitsy.</p><p>Le système gravitaire choisi ne nécessite ni pompe ni électricité, ce qui garantit la durabilité et la maintenabilité locale de l\'infrastructure.</p>',
			'meta'  => array(
				'budget'                 => '11–14 800 €',
				'budget_eur'             => 11000,
				'location'               => 'Ambohitrolomahitsy, Analamanga',
				'partenaire'             => 'Madagascar Development Fund',
				'localisation_commune'   => 'Ambohitrolomahitsy',
				'localisation_region'    => 'Analamanga',
				'beneficiaires_nombre'   => 1300,
				'site_canonical'         => 'dsf',
			),
			'projet_type'   => 'eau',
			'projet_statut' => 'en-preparation',
			'projet_domaine'=> 'humanitaire',
			'branches'      => array( 'dsf', 'dsm' ),
		),

		array(
			'post_title'   => 'École des Femmes · Anjozorobe',
			'post_name'    => 'ecole-des-femmes-anjozorobe',
			'post_excerpt' => 'Relance d\'un programme interrompu début 2025 : sessions mensuelles pour 50 à 100 femmes (santé, nutrition, finance, hygiène). La salle et les formatrices sont prêtes.',
			'post_content' => '<p>Relance d\'un programme d\'éducation pour les femmes interrompu début 2025, à Anjozorobe. Les sessions mensuelles couvrent santé, nutrition, gestion financière et hygiène pour 50 à 100 participantes par session.</p><p>La salle de formation et les formatrices locales sont déjà en place. Le budget de 4 830 € (an 1) couvre l\'organisation des 12 sessions et les frais d\'animation, avec une récurrence prévue à 1 680 €/an.</p>',
			'meta'  => array(
				'budget'                      => '4 830 € (an 1)',
				'budget_eur'                  => 4830,
				'location'                    => 'Anjozorobe, Hautes Terres',
				'partenaire'                  => '',
				'localisation_commune'        => 'Anjozorobe',
				'localisation_region'         => 'Hautes Terres',
				'beneficiaires_description'   => '50 à 100 femmes par session',
				'site_canonical'              => 'dsf',
			),
			'projet_type'   => 'education',
			'projet_statut' => 'en-preparation',
			'projet_domaine'=> 'humanitaire',
			'branches'      => array( 'dsf', 'dsm' ),
		),

		array(
			'post_title'   => 'Forêt comestible · Anjozorobe',
			'post_name'    => 'foret-comestible-anjozorobe',
			'post_excerpt' => 'Reprise d\'une forêt comestible co-construite avec 15 familles et 54 enfants (2021–2025). Techniques de permaculture sur sols dégradés, sans intrants chimiques.',
			'post_content' => '<p>Sur les hautes terres d\'Anjozorobe, une parcelle de sols dégradés a été progressivement transformée en forêt comestible : arbres fruitiers, cultures vivrières étagées, buttes de permaculture — sans aucun intrant chimique.</p><p>Le programme a été lancé en 2021 par Éducation pour Madagascar (edu4mada), qui l\'a porté pendant quatre ans sous le nom de « PerMada ». Quinze familles et leurs 54 enfants y ont appris les techniques de permaculture, appliquées ensuite dans leurs propres jardins.</p><p>Début 2025, le retrait des organisations porteuses a interrompu le programme. Les familles sont restées. Les arbres aussi. C\'est cette base, vivante mais fragile, que Drolung Solidarité Madagascar veut faire repartir — avec le soutien financier de Drolung Solidarité France.</p><p><strong>Priorité d\'investissement :</strong> un système de récupération d\'eau de pluie pour pallier la saison sèche (avril–octobre), qui représentait la contrainte principale du projet.</p>',
			'meta'  => array(
				'budget'                      => '4 380 € (an 1)',
				'budget_eur'                  => 4380,
				'location'                    => 'Anjozorobe, Hautes Terres',
				'partenaire'                  => 'Éducation pour Madagascar (edu4mada)',
				'localisation_commune'        => 'Anjozorobe',
				'localisation_region'         => 'Hautes Terres',
				'beneficiaires_nombre'        => 69,
				'beneficiaires_description'   => '15 familles et leurs 54 enfants',
				'site_canonical'              => 'dsf',
			),
			'projet_type'   => 'environnement',
			'projet_statut' => 'en-preparation',
			'projet_domaine'=> 'humanitaire',
			'branches'      => array( 'dsf', 'dsm' ),
		),

		array(
			'post_title'   => 'Reconstruction d\'école · Tamatave',
			'post_name'    => 'reconstruction-ecole-tamatave',
			'post_excerpt' => 'Une école sinistrée par un cyclone récent sur la côte Est. Les enfants continuent d\'occuper un bâtiment endommagé. DSM évalue les travaux nécessaires.',
			'post_content' => '<p>Sur la côte Est de Madagascar, à Tamatave (Toamasina), une école a été sinistrée par un cyclone récent. Les enfants continuent d\'utiliser le bâtiment endommagé, faute d\'alternative.</p><p>Drolung Solidarité Madagascar est en cours d\'évaluation des travaux nécessaires (structure, toiture, sanitaires). Le budget sera confirmé à l\'issue de l\'évaluation terrain.</p>',
			'meta'  => array(
				'budget'               => 'Budget à confirmer',
				'budget_eur'           => 0,
				'location'             => 'Tamatave (Toamasina), côte Est',
				'partenaire'           => '',
				'localisation_commune' => 'Tamatave (Toamasina)',
				'localisation_region'  => 'côte Est',
				'site_canonical'       => 'dsf',
			),
			'projet_type'   => 'education',
			'projet_statut' => 'en-evaluation',
			'projet_domaine'=> 'humanitaire',
			'branches'      => array( 'dsf', 'dsm' ),
		),

	);

	foreach ( $projets as $data ) {
		/* Skip if a projet with this slug already exists. */
		$existing = get_page_by_path( $data['post_name'], OBJECT, 'projet' );
		if ( $existing ) {
			continue;
		}

		$post_id = wp_insert_post( array(
			'post_type'    => 'projet',
			'post_status'  => 'publish',
			'post_title'   => $data['post_title'],
			'post_name'    => $data['post_name'],
			'post_excerpt' => $data['post_excerpt'],
			'post_content' => $data['post_content'],
		), true );

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		/* Post meta. */
		foreach ( $data['meta'] as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		/* Taxonomy terms. */
		if ( taxonomy_exists( 'projet_type' ) ) {
			wp_set_object_terms( $post_id, $data['projet_type'], 'projet_type' );
		}
		if ( taxonomy_exists( 'projet_statut' ) ) {
			wp_set_object_terms( $post_id, $data['projet_statut'], 'projet_statut' );
		}
		if ( taxonomy_exists( 'projet_domaine' ) ) {
			wp_set_object_terms( $post_id, $data['projet_domaine'], 'projet_domaine' );
		}
		if ( taxonomy_exists( 'drolung_branch' ) ) {
			wp_set_object_terms( $post_id, $data['branches'], 'drolung_branch' );
		}
	}

	update_site_option( 'drolung_projets_seeded_v1', current_time( 'mysql' ) );
}
