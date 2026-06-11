<?php
/**
 * Plugin Name: Drolung — Seed ACF field values on each site
 * Description: When a new branch site is provisioned, pre-fills the ACF fields on the front page, À propos and Notre action pages with the same French copy that the templates use as defaults. Admins then see populated fields in WP Admin and can edit existing content rather than starting from blank. Idempotent per site via the `drolung_acf_seeded` flag.
 * Author: Drolung dev
 * Version: 0.1.0
 * Network: True
 *
 * To replay the seed on a specific site, delete its `drolung_acf_seeded`
 * option and refresh wp-admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'drolung_seed_acf_per_site', 30 );

function drolung_seed_acf_per_site() {
	if ( ! is_multisite() || ! is_super_admin() ) {
		return;
	}
	if ( ! function_exists( 'update_field' ) ) {
		return; // ACF not yet loaded.
	}

	$root  = DOMAIN_CURRENT_SITE;
	$sites = [ $root, 'dsm.' . $root, 'dsf.' . $root, 'duk.' . $root ];

	foreach ( $sites as $domain ) {
		$blog_id = get_blog_id_from_url( $domain, '/' );
		if ( ! $blog_id ) {
			continue;
		}
		switch_to_blog( $blog_id );
		if ( ! get_option( 'drolung_acf_seeded' ) ) {
			drolung_seed_acf_values();
			update_option( 'drolung_acf_seeded', current_time( 'mysql' ) );
		}
		/* DSF — surcharge des 3 axes : Eau & Assainissement / Éducation / Santé.
		 * Drapeau distinct pour s'appliquer même sur un site déjà seedé.
		 * Pour rejouer : delete_option( 'drolung_dsf_axes_v1' ); */
		if ( $domain === 'dsf.' . $root && ! get_option( 'drolung_dsf_axes_v1' ) ) {
			drolung_seed_dsf_axes();
			update_option( 'drolung_dsf_axes_v1', current_time( 'mysql' ) );
		}
		restore_current_blog();
	}
}

/**
 * Apply the actual field-by-field seeding for the *current* site.
 * Called from inside switch_to_blog() context.
 */
function drolung_seed_acf_values() {

	/* ── Front page ──────────────────────────────────────── */
	$front_id = (int) get_option( 'page_on_front' );
	if ( $front_id ) {
		$front = [
			'hero_eyebrow'      => __( 'Notre engagement', 'drolung-branch' ),
			'hero_title'        => __( 'Agir <em>localement</em>,<br>changer durablement.', 'drolung-branch' ),
			'hero_sub'          => __( 'Une association de proximité qui soutient des projets concrets en éducation, santé et environnement, en partenariat avec les communautés locales.', 'drolung-branch' ),
			'hero_cta1_label'   => __( 'Soutenir notre action', 'drolung-branch' ),
			'hero_cta2_label'   => __( 'Découvrir nos actions →', 'drolung-branch' ),
			'hero_cta2_url'     => home_url( '/notre-action/' ),

			'impact_1_num'      => '3',
			'impact_1_label'    => __( 'Axes d\'intervention', 'drolung-branch' ),
			'impact_2_num'      => '100%',
			'impact_2_label'    => __( 'Bénévoles', 'drolung-branch' ),
			'impact_3_num'      => '0',
			'impact_3_label'    => __( 'Projets en cours', 'drolung-branch' ),
			'impact_4_num'      => '2026',
			'impact_4_label'    => __( 'Année de création', 'drolung-branch' ),

			'intro_eyebrow'     => __( 'Qui nous sommes', 'drolung-branch' ),
			'intro_title'       => __( 'Une association ancrée dans la <em>solidarité</em>', 'drolung-branch' ),
			'intro_body'        => '<p>' . __( 'Drolung Solidarité réunit des bénévoles autour d\'une conviction simple : l\'aide la plus efficace est celle qui s\'enracine dans les besoins exprimés par les communautés elles-mêmes. Nous travaillons en lien direct avec les acteurs de terrain.', 'drolung-branch' ) . '</p>',
			'intro_badge_num'   => '2026',
			'intro_badge_label' => __( 'Année de création', 'drolung-branch' ),
			'intro_cta_label'   => __( 'Notre histoire & mission →', 'drolung-branch' ),

			'map_eyebrow'       => __( 'Où nous intervenons', 'drolung-branch' ),
			'map_title'         => __( 'Sur le <em>terrain</em>', 'drolung-branch' ),
			'map_body'          => '<p>' . __( 'Nos projets se concentrent sur quelques régions choisies pour la qualité de nos liens locaux et la pertinence des besoins identifiés.', 'drolung-branch' ) . '</p>',

			'donate_eyebrow'    => __( 'Faire un don', 'drolung-branch' ),
			'donate_title'      => __( 'Votre don <em>change</em> les choses', 'drolung-branch' ),
			'donate_body'       => '<p>' . __( 'Chaque euro reçu est affecté directement à un projet concret. Les comptes de l\'association sont publiés chaque année dans un souci de transparence totale.', 'drolung-branch' ) . '</p>',
		];
		foreach ( $front as $key => $val ) {
			update_field( $key, $val, $front_id );
		}
	}

	/* ── À propos ────────────────────────────────────────── */
	$apropos = get_page_by_path( 'a-propos' );
	if ( $apropos ) {
		$fields = [
			'hero_eyebrow'     => __( 'À propos', 'drolung-branch' ),
			'hero_title'       => __( 'Au service <em>des communautés</em>', 'drolung-branch' ),
			'hero_sub'         => __( 'Drolung Solidarité accompagne les communautés dans une démarche d\'engagement durable, ancrée localement et portée par des bénévoles.', 'drolung-branch' ),
			'mission_eyebrow'  => __( 'Notre histoire', 'drolung-branch' ),
			'mission_title'    => __( 'Deux assos, <em>une même intention</em>', 'drolung-branch' ),
			'mission_body'     => '<p>' . __( 'En 2025, plusieurs membres du réseau Drolung — bouddhistes pratiquants franco-malgaches et leurs proches — ont décidé de structurer leur engagement par la création de deux associations sœurs.', 'drolung-branch' ) . '</p><p>' . __( 'Le constat d\'origine est simple : ce que nous voulons faire à Madagascar a besoin d\'être ancré là-bas, et ce que nous voulons offrir comme soutien depuis la France a besoin d\'un cadre clair, transparent et juridiquement adapté. Deux entités, une seule intention.', 'drolung-branch' ) . '</p>',
		];
		foreach ( $fields as $key => $val ) {
			update_field( $key, $val, $apropos->ID );
		}
	}

	/* ── Notre action ────────────────────────────────────── */
	$action = get_page_by_path( 'notre-action' );
	if ( $action ) {
		$fields = [
			'hero_eyebrow' => __( 'Notre action', 'drolung-branch' ),
			'hero_title'   => __( 'Agir <em>au plus près</em>', 'drolung-branch' ),
			'hero_sub'     => __( 'Aux côtés des familles, des écoles et des soignants. Pour un changement durable, choisi de l\'intérieur.', 'drolung-branch' ),
			'intro_title'  => __( 'Le terrain <em>d\'abord</em>', 'drolung-branch' ),
			'intro_body'   => '<p>' . __( 'Nous croyons qu\'un développement juste ne se décrète pas depuis l\'extérieur. Il s\'enracine dans les besoins exprimés par les communautés elles-mêmes, dans le respect de leurs savoirs, de leur rythme et de leur dignité.', 'drolung-branch' ) . '</p><p>' . __( 'Nous travaillons en lien direct avec les familles, les enseignants, les soignants et les acteurs locaux. Nous avançons à leurs côtés, jamais à leur place.', 'drolung-branch' ) . '</p>',

			'axe_1_title'  => __( 'Apprendre, transmettre, faire grandir', 'drolung-branch' ),
			'axe_1_body'   => '<p>' . __( 'Soutenir la scolarité des enfants, accompagner les jeunes dans leurs études et leur orientation, valoriser la transmission des savoirs locaux. Parce que chaque génération a le droit de choisir son avenir en connaissance de cause.', 'drolung-branch' ) . '</p>',
			'axe_2_title'  => __( 'Prendre soin, sans condition', 'drolung-branch' ),
			'axe_2_body'   => '<p>' . __( 'Faciliter l\'accès aux soins de base, soutenir les structures de santé locales, accompagner la santé maternelle et infantile. Parce que se soigner ne devrait jamais être un privilège.', 'drolung-branch' ) . '</p>',
			'axe_3_title'  => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
			'axe_3_body'   => '<p>' . __( 'Encourager l\'agriculture vivrière, soutenir les coopératives et les artisans, préserver les écosystèmes dont dépendent les familles. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ) . '</p>',
		];
		foreach ( $fields as $key => $val ) {
			update_field( $key, $val, $action->ID );
		}
	}
}

/**
 * DSF override — replaces the 3 axes with Eau & Assainissement / Éducation / Santé.
 * Touches only the Notre action page; the home page reads its 3 axes from there.
 */
function drolung_seed_dsf_axes() {
	$action = get_page_by_path( 'notre-action' );
	if ( ! $action ) {
		return;
	}

	$fields = [
		// Axe 1 — Eau & Assainissement (nouveau).
		'axe_1_tag'   => __( 'Eau & Assainissement', 'drolung-branch' ),
		'axe_1_title' => __( 'L\'eau, première condition', 'drolung-branch' ),
		'axe_1_body'  => '<p>' . __( 'Garantir un accès durable à l\'eau potable et à un assainissement digne. Aménager des points d\'eau, accompagner les communautés dans la gestion de leurs ressources, transmettre les bons gestes d\'hygiène. Parce que tout commence par là.', 'drolung-branch' ) . '</p>',
		'axe_1_image' => 'https://images.unsplash.com/photo-1538300342682-cf57afb97285?auto=format&fit=crop&q=80&w=700&h=420',

		// Axe 2 — Éducation (décalé depuis l'axe 1 par défaut).
		'axe_2_tag'   => __( 'Éducation', 'drolung-branch' ),
		'axe_2_title' => __( 'Apprendre, transmettre, faire grandir', 'drolung-branch' ),
		'axe_2_body'  => '<p>' . __( 'Soutenir la scolarité des enfants, accompagner les jeunes dans leurs études et leur orientation, valoriser la transmission des savoirs locaux. Parce que chaque génération a le droit de choisir son avenir en connaissance de cause.', 'drolung-branch' ) . '</p>',
		'axe_2_image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&q=80&w=700&h=420',

		// Axe 3 — Santé (décalé depuis l'axe 2 par défaut).
		'axe_3_tag'   => __( 'Santé', 'drolung-branch' ),
		'axe_3_title' => __( 'Prendre soin, sans condition', 'drolung-branch' ),
		'axe_3_body'  => '<p>' . __( 'Faciliter l\'accès aux soins de base, soutenir les structures de santé locales, accompagner la santé maternelle et infantile. Parce que se soigner ne devrait jamais être un privilège.', 'drolung-branch' ) . '</p>',
		'axe_3_image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=700&h=420',
	];

	foreach ( $fields as $key => $val ) {
		update_field( $key, $val, $action->ID );
	}
}
