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
		/* DSM — surcharge de la section don de la page S'engager.
		 * DSM redirige vers DSF pour les dons (boîte "rendez-vous sur DSF").
		 * v2 2026-06-25 : ajout engager_don_cta_url → page S'engager DSF.
		 * Pour rejouer : delete_option( 'drolung_dsm_engager_v2' ); */
		if ( $domain === 'dsm.' . $root && ! get_option( 'drolung_dsm_engager_v2' ) ) {
			drolung_seed_dsm_engager();
			update_option( 'drolung_dsm_engager_v2', current_time( 'mysql' ) );
		}
		/* Champs home page manquants : chiffres, projets, engagements, newsletter, dons.
		 * Nouvelle gate v2 — s'applique même si drolung_acf_seeded est déjà posé.
		 * Pour rejouer : delete_option( 'drolung_front_v2' ); */
		if ( ! get_option( 'drolung_front_v2' ) ) {
			drolung_seed_front_v2();
			update_option( 'drolung_front_v2', current_time( 'mysql' ) );
		}
		/* DSF — overrides des engagements sur la home page.
		 * Pour rejouer : delete_option( 'drolung_dsf_home_v1' ); */
		if ( $domain === 'dsf.' . $root && ! get_option( 'drolung_dsf_home_v1' ) ) {
			drolung_seed_dsf_home();
			update_option( 'drolung_dsf_home_v1', current_time( 'mysql' ) );
		}
		/* DSF — corrige axe 4 (Environnement), principe_1 (wording honnête),
		 * engager_don_intro (hors frais incompressibles).
		 * Gate distincte car drolung_dsf_axes_v1 a déjà été tirée.
		 * Pour rejouer : delete_option( 'drolung_dsf_home_copy_v1' ); */
		if ( $domain === 'dsf.' . $root && ! get_option( 'drolung_dsf_home_copy_v1' ) ) {
			drolung_seed_dsf_home_copy();
			update_option( 'drolung_dsf_home_copy_v1', current_time( 'mysql' ) );
		}
		/* DSM — corrige axe 4 (Environnement) et texte de la boîte redirection DSF.
		 * Gate distincte car drolung_acf_seeded a déjà été tirée.
		 * Pour rejouer : delete_option( 'drolung_dsm_axes_v1' ); */
		if ( $domain === 'dsm.' . $root && ! get_option( 'drolung_dsm_axes_v1' ) ) {
			drolung_seed_dsm_axes_v1();
			update_option( 'drolung_dsm_axes_v1', current_time( 'mysql' ) );
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

	/* ── S'engager ──────────────────────────────────────── */
	$engager = get_page_by_path( 's-engager' );
	if ( $engager ) {
		$fields = [
			// Hero.
			'engager_hero_eyebrow' => __( 'S\'engager', 'drolung-branch' ),
			'engager_hero_title'   => __( 'Agissez avec nous, <em>de plusieurs façons</em>', 'drolung-branch' ),
			'engager_hero_sub'     => __( 'Un don, un partenariat, un partage — chaque geste compte pour faire avancer les projets à Madagascar.', 'drolung-branch' ),
			// Don section.
			'engager_don_eyebrow'   => __( 'Faire un don', 'drolung-branch' ),
			'engager_don_title'     => __( 'Votre don agit <em>directement</em>', 'drolung-branch' ),
			'engager_don_intro'     => __( 'Chaque euro versé à DSF est affecté aux projets portés par Drolung Solidarité Madagascar, hors frais administratifs incompressibles (banque + obligations légales, de l\'ordre de 100 € par mois).', 'drolung-branch' ),
			'engager_don_cta_label' => __( 'Nous contacter pour un don', 'drolung-branch' ),
			// Partage section.
			'engager_partage_eyebrow' => __( 'Partagez', 'drolung-branch' ),
			'engager_partage_title'   => __( 'Parlez de nous, <em>partagez nos projets</em>', 'drolung-branch' ),
			'engager_partage_body'    => __( 'Le plus simple des engagements — et l\'un des plus puissants. Mentionner DSF et DSM autour de vous, partager nos publications, relayer nos projets : chaque partage élargit notre portée.', 'drolung-branch' ),
			// Partenariat section.
			'engager_partenariat_eyebrow'   => __( 'Partenariat', 'drolung-branch' ),
			'engager_partenariat_title'     => __( 'Vous êtes une entreprise <em>ou une fondation ?</em>', 'drolung-branch' ),
			'engager_partenariat_body'      => __( 'Nous sommes ouverts à des partenariats de mécénat, de compétences ou de financement de projet. Toute collaboration est traitée avec transparence et fait l\'objet d\'un rapport dédié.', 'drolung-branch' ),
			'engager_partenariat_cta_label' => __( 'Nous contacter', 'drolung-branch' ),
			// Mécénat cards.
			'engager_mecenat_1_title' => __( 'Mécénat', 'drolung-branch' ),
			'engager_mecenat_1_body'  => __( 'Financement direct d\'un projet identifié, avec rapport de suivi dédié.', 'drolung-branch' ),
			'engager_mecenat_2_title' => __( 'Mécénat de compétences', 'drolung-branch' ),
			'engager_mecenat_2_body'  => __( 'Mise à disposition d\'expertise (santé, agronomie, éducation, logistique).', 'drolung-branch' ),
		];
		foreach ( $fields as $key => $val ) {
			update_field( $key, $val, $engager->ID );
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
			// Hero.
			'hero_eyebrow'  => __( 'Notre action', 'drolung-branch' ),
			'hero_title'    => __( 'Agir <em>au plus près</em>', 'drolung-branch' ),
			'hero_sub'      => __( 'Aux côtés des familles, des écoles et des soignants. Pour un changement durable, choisi de l\'intérieur.', 'drolung-branch' ),

			// Intro two-col (DSM defaults).
			'intro_eyebrow' => __( 'Notre approche', 'drolung-branch' ),
			'intro_title'   => __( 'Le terrain <em>d\'abord</em>', 'drolung-branch' ),
			'intro_body'    => '<p>' . __( 'Nous croyons qu\'un développement juste ne se décrète pas depuis l\'extérieur. Il s\'enracine dans les besoins exprimés par les communautés elles-mêmes, dans le respect de leurs savoirs, de leur rythme et de leur dignité.', 'drolung-branch' ) . '</p><p>' . __( 'À Madagascar, Drolung Solidarité Madagascar travaille en lien direct avec les familles, les enseignants, les soignants et les acteurs locaux. Nous avançons à leurs côtés, jamais à leur place.', 'drolung-branch' ) . '</p>',

			// Axes section header (DSM defaults).
			'axes_eyebrow'  => __( 'Nos axes d\'action', 'drolung-branch' ),
			'axes_title'    => __( 'Quatre axes <em>indissociables</em>', 'drolung-branch' ),
			'axes_body'     => __( 'L\'éducation, la santé, l\'environnement et l\'accès à l\'eau ne s\'opposent jamais : ce sont les quatre conditions d\'une vie digne. Nous travaillons aux quatre en même temps, parce que c\'est ensemble qu\'ils prennent sens.', 'drolung-branch' ),

			// Axe 1 — Éducation (DSM default).
			'axe_1_tag'    => __( 'Éducation', 'drolung-branch' ),
			'axe_1_title'  => __( 'Apprendre, transmettre, faire grandir', 'drolung-branch' ),
			'axe_1_body'   => '<p>' . __( 'Soutenir la scolarité des enfants, accompagner les jeunes dans leurs études et leur orientation, valoriser la transmission des savoirs locaux. Parce que chaque génération a le droit de choisir son avenir en connaissance de cause.', 'drolung-branch' ) . '</p>',
			'axe_1_image'  => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&q=80&w=700&h=420',

			// Axe 2 — Santé (DSM default).
			'axe_2_tag'    => __( 'Santé', 'drolung-branch' ),
			'axe_2_title'  => __( 'Prendre soin, sans condition', 'drolung-branch' ),
			'axe_2_body'   => '<p>' . __( 'Faciliter l\'accès aux soins de base, soutenir les structures de santé locales, accompagner la santé maternelle et infantile. Parce que se soigner ne devrait jamais être un privilège.', 'drolung-branch' ) . '</p>',
			'axe_2_image'  => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=700&h=420',

			// Axe 3 — Eau & Assainissement (DSM default).
			'axe_3_tag'    => __( 'Eau & Assainissement', 'drolung-branch' ),
			'axe_3_title'  => __( 'L\'eau, avant tout', 'drolung-branch' ),
			'axe_3_body'   => '<p>' . __( 'Faciliter l\'accès à l\'eau potable, améliorer les conditions d\'hygiène et construire des infrastructures sanitaires durables. Parce que tout commence par une eau propre.', 'drolung-branch' ) . '</p>',
			'axe_3_image'  => 'https://images.unsplash.com/photo-1569511166187-97b27af41b5a?auto=format&fit=crop&q=80&w=700&h=420',

			// Axe 4 — Environnement (DSM default).
			'axe_4_tag'    => __( 'Environnement', 'drolung-branch' ),
			'axe_4_title'  => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
			'axe_4_body'   => '<p>' . __( 'Encourager l\'agriculture vivrière, soutenir les coopératives et les artisans, préserver les écosystèmes dont dépendent les familles. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ) . '</p>',
			'axe_4_image'  => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=700&h=420',

			// Dark section — Principes (DSM defaults).
			'principes_eyebrow' => __( 'Nos principes', 'drolung-branch' ),
			'principes_title'   => __( 'Quatre <em>repères</em>', 'drolung-branch' ),
			'principes_body'    => __( 'Quatre repères qui guident chacune de nos décisions, sur le terrain comme dans nos choix d\'organisation.', 'drolung-branch' ),

			'principe_1_label'  => __( 'Au plus près', 'drolung-branch' ),
			'principe_1_body'   => __( 'Une présence directe sur le terrain, en lien permanent avec les familles et les acteurs locaux.', 'drolung-branch' ),
			'principe_2_label'  => __( 'Avec humilité', 'drolung-branch' ),
			'principe_2_body'   => __( 'Écouter et apprendre des partenaires locaux avant de proposer. Les solutions justes viennent toujours du terrain.', 'drolung-branch' ),
			'principe_3_label'  => __( 'Dans la durée', 'drolung-branch' ),
			'principe_3_body'   => __( 'Privilégier les engagements longs aux opérations ponctuelles. Le changement réel demande du temps.', 'drolung-branch' ),
			'principe_4_label'  => __( 'En transparence', 'drolung-branch' ),
			'principe_4_body'   => __( 'Rendre des comptes sur chaque action menée et chaque euro reçu.', 'drolung-branch' ),
		];
		foreach ( $fields as $key => $val ) {
			update_field( $key, $val, $action->ID );
		}
	}
}

/**
 * DSM override — replaces the "Faire un don" section on the S'engager page.
 * DSM doesn't collect donations directly; it redirects donors to DSF.
 * The don_body field receives a redirect notice box instead of the cost list.
 * Portée 2026-06-16.
 * To replay: delete_option( 'drolung_dsm_engager_v1' ) on dsm.drolung.local.
 */
function drolung_seed_dsm_engager() {
	$engager = get_page_by_path( 's-engager' );
	if ( ! $engager ) {
		return;
	}

	$fields = [
		// Hero — DSM framing.
		'engager_hero_title' => __( 'Soutenez <em>notre action de terrain</em>', 'drolung-branch' ),
		'engager_hero_sub'   => __( 'Un don via notre association sœur DSF, un partenariat local, un relai — chaque soutien compte pour les communautés malgaches.', 'drolung-branch' ),

		// Don section — DSM redirects to DSF.
		'engager_don_title'     => __( 'Vos dons passent <em>par DSF</em>', 'drolung-branch' ),
		'engager_don_intro'     => __( 'Les dons destinés à nos projets sont collectés depuis la France par notre association sœur Drolung Solidarité France. C\'est elle qui reçoit, gère et transfère les fonds vers nos actions à Madagascar.', 'drolung-branch' ),
		'engager_don_body'      => '<p style="font-size:14px;color:var(--text-muted);line-height:1.6;margin:0 0 16px">'
			. __( 'Ce choix garantit une traçabilité complète des fonds et une gouvernance transparente pour les donateurs français et européens.', 'drolung-branch' )
			. '</p>'
			. '<div style="background:var(--saffron-pale);border-left:3px solid var(--saffron);padding:20px 24px;margin-top:28px;border-radius:0 2px 2px 0">'
			. '<div style="font-weight:600;color:var(--charcoal);margin-bottom:8px;font-size:15px">'
			. __( 'Pour faire un don, rendez-vous sur le site de DSF', 'drolung-branch' )
			. '</div>'
			. '<p style="font-size:14px;color:var(--text-muted);line-height:1.6;margin:0 0 16px">'
			. __( 'Drolung Solidarité France — association loi 1901, équipe entièrement bénévole. 100 % des fonds collectés vont aux projets DSM.', 'drolung-branch' )
			. '</p>'
			. '</div>',
		'engager_don_cta_label' => __( 'Faire un don sur DSF →', 'drolung-branch' ),
		'engager_don_cta_url'   => function_exists( 'drolung_branch_blog_id' )
			? get_home_url( drolung_branch_blog_id( 'dsf' ), '/s-engager/' )
			: '',

		// Partenariat — DSM-specific wording.
		'engager_partenariat_body' => __( 'Nous sommes ouverts à des partenariats avec des organisations locales, des ONG malgaches, des institutions ou des entreprises. Contactez-nous pour en discuter.', 'drolung-branch' ),
	];

	foreach ( $fields as $key => $val ) {
		update_field( $key, $val, $engager->ID );
	}
}

/**
 * DSF override — replaces hero, intro, 4 axes and principes with DSF-specific copy.
 * Touches only the Notre action page.
 * Updated 2026-06-16: now covers the full page content (was axes only).
 * To replay: delete_option( 'drolung_dsf_axes_v1' ) on dsf.drolung.local.
 */
function drolung_seed_dsf_axes() {
	$action = get_page_by_path( 'notre-action' );
	if ( ! $action ) {
		return;
	}

	$fields = [
		// Hero — DSF-specific framing.
		'hero_eyebrow' => __( 'Notre action', 'drolung-branch' ),
		'hero_title'   => __( 'Soutenir <em>l\'action de terrain</em>', 'drolung-branch' ),
		'hero_sub'     => __( 'Depuis la France, nous mobilisons les ressources, le temps et l\'attention nécessaires pour faire grandir les projets que Drolung Solidarité Madagascar mène au plus près des communautés.', 'drolung-branch' ),

		// Intro two-col — "Notre rôle / Un pont entre deux rives".
		'intro_eyebrow' => __( 'Notre rôle', 'drolung-branch' ),
		'intro_title'   => __( 'Un pont <em>entre deux rives</em>', 'drolung-branch' ),
		'intro_body'    => '<p>' . __( 'Drolung Solidarité France n\'agit pas seule sur le terrain. Notre vocation est de construire, depuis la France, le soutien matériel et humain qui permet à Drolung Solidarité Madagascar de conduire ses actions.', 'drolung-branch' ) . '</p><p style="margin-top:16px">' . __( 'L\'intégralité des fonds que nous collectons est destinée aux projets portés par notre association sœur à Madagascar. Pour qu\'à chaque don, à chaque mobilisation, corresponde une action concrète et identifiée sur le terrain.', 'drolung-branch' ) . '</p>',

		// Axes section header — DSF.
		'axes_eyebrow' => __( 'Les actions que nous soutenons', 'drolung-branch' ),
		'axes_title'   => __( 'Quatre axes <em>indissociables</em>', 'drolung-branch' ),
		'axes_body'    => __( 'Notre soutien finance quatre domaines d\'intervention que nous tenons pour inséparables : l\'éducation, la santé, l\'environnement et l\'accès à l\'eau. Chacun se renforce des autres.', 'drolung-branch' ),

		// Axe 1 — Éducation (DSF order: Éducation first).
		'axe_1_tag'   => __( 'Éducation', 'drolung-branch' ),
		'axe_1_title' => __( 'Apprendre, transmettre, faire grandir', 'drolung-branch' ),
		'axe_1_body'  => '<p>' . __( 'Donner aux enfants les moyens d\'aller à l\'école, accompagner les jeunes dans leur parcours, soutenir les passeurs de savoirs locaux. Notre engagement porte sur l\'avenir d\'une génération.', 'drolung-branch' ) . '</p>',
		'axe_1_image' => 'https://images.unsplash.com/photo-jEEYZsaxbH4?auto=format&fit=crop&q=80&w=700&h=420',

		// Axe 2 — Santé.
		'axe_2_tag'   => __( 'Santé', 'drolung-branch' ),
		'axe_2_title' => __( 'Prendre soin, sans condition', 'drolung-branch' ),
		'axe_2_body'  => '<p>' . __( 'Soutenir l\'accès aux soins de base, les structures de santé locales et l\'accompagnement de la santé maternelle et infantile. Parce que se soigner ne devrait jamais relever du privilège.', 'drolung-branch' ) . '</p>',
		'axe_2_image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=700&h=420',

		// Axe 3 — Eau & Assainissement.
		'axe_3_tag'   => __( 'Eau & Assainissement', 'drolung-branch' ),
		'axe_3_title' => __( 'L\'eau, avant tout', 'drolung-branch' ),
		'axe_3_body'  => '<p>' . __( 'Financer l\'accès à l\'eau potable et aux infrastructures sanitaires là où elles manquent le plus. Parce que sans eau, rien d\'autre n\'est possible.', 'drolung-branch' ) . '</p>',
		'axe_3_image' => 'https://images.unsplash.com/photo-1569511166187-97b27af41b5a?auto=format&fit=crop&q=80&w=700&h=420',

		// Axe 4 — Environnement.
		'axe_4_tag'   => __( 'Environnement', 'drolung-branch' ),
		'axe_4_title' => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
		'axe_4_body'  => '<p>' . __( 'Encourager l\'agriculture vivrière, soutenir les coopératives et les artisans malgaches, préserver les écosystèmes dont dépendent les familles. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ) . '</p>',
		'axe_4_image' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=700&h=420',

		// Dark section — Nos engagements (DSF-specific).
		'principes_eyebrow' => __( 'Nos engagements', 'drolung-branch' ),
		'principes_title'   => __( 'Quatre <em>engagements</em>', 'drolung-branch' ),
		'principes_body'    => __( 'Quatre engagements qui structurent notre relation avec nos donateurs et avec notre association sœur.', 'drolung-branch' ),

		'principe_1_label' => __( 'L\'essentiel vers le terrain', 'drolung-branch' ),
		'principe_1_body'  => __( 'La quasi-totalité des dons collectés va aux projets à Madagascar. Les frais incompressibles (banque, obligations associatives) représentent environ 100 € par mois — soit moins de 3 % à l\'échelle annuelle.', 'drolung-branch' ),
		'principe_2_label' => __( 'Une équipe bénévole', 'drolung-branch' ),
		'principe_2_body'  => __( 'Le bureau et l\'ensemble des contributeurs réguliers travaillent sans rémunération.', 'drolung-branch' ),
		'principe_3_label' => __( 'Transparence intégrale', 'drolung-branch' ),
		'principe_3_body'  => __( 'Chaque euro engagé est suivi, documenté et rendu public dans nos comptes annuels.', 'drolung-branch' ),
		'principe_4_label' => __( 'Un lien direct', 'drolung-branch' ),
		'principe_4_body'  => __( 'Pas d\'intermédiaire entre le don à DSF et l\'action à Madagascar. Une seule association sœur, une seule destination.', 'drolung-branch' ),
	];

	foreach ( $fields as $key => $val ) {
		update_field( $key, $val, $action->ID );
	}
}

/**
 * Home page — champs manquants du seed initial.
 * Couvre : chiffres clés, préview projets, engagements, newsletter, exemples de dons.
 * Valeurs par défaut DSM (DSF overrides via drolung_seed_dsf_home).
 * To replay: delete_option( 'drolung_front_v2' ) on each site.
 */
function drolung_seed_front_v2() {
	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) {
		return;
	}

	$fields = [
		// Chiffres clés (dark section).
		'chiffres_eyebrow'  => __( 'La réalité du terrain', 'drolung-branch' ),
		'chiffres_title'    => __( 'Madagascar en chiffres', 'drolung-branch' ),
		'chiffre_1_num'     => '80 %',
		'chiffre_1_label'   => __( 'de la population vit sous le seuil de pauvreté', 'drolung-branch' ),
		'chiffre_2_num'     => '44 %',
		'chiffre_2_label'   => __( 'n\'ont pas accès à une eau potable améliorée', 'drolung-branch' ),
		'chiffre_3_num'     => '39,8 %',
		'chiffre_3_label'   => __( 'des enfants souffrent de malnutrition chronique', 'drolung-branch' ),
		'chiffre_4_num'     => '177',
		'chiffre_4_label'   => __( 'sur 193 pays à l\'Indice de Développement Humain', 'drolung-branch' ),
		'chiffre_5_num'     => '54 %',
		'chiffre_5_label'   => __( 'de défécation à l\'air libre en zones rurales', 'drolung-branch' ),
		'chiffre_6_num'     => '1/16',
		'chiffre_6_label'   => __( 'enfants ne survit pas jusqu\'à ses 5 ans', 'drolung-branch' ),
		'chiffres_cta'      => __( 'C\'est cette réalité que nos projets cherchent à changer — concrètement, durablement, depuis le terrain.', 'drolung-branch' ),

		// Zones d'intervention — titre et préview projets.
		'map_title'         => __( 'Quatre projets <em>en cours de montage</em>', 'drolung-branch' ),
		'map_body'          => __( 'Adduction d\'eau gravitaire à Ambohitrolomahitsy, relance de l\'École des Femmes d\'Anjozorobe, reprise de la forêt comestible d\'Anjozorobe, reconstruction d\'une école sinistrée à Tamatave — des projets concrets, ancrés dans les besoins réels des communautés malgaches.', 'drolung-branch' ),
		'region_1_name'     => __( 'Eau potable', 'drolung-branch' ),
		'region_1_count'    => __( 'Ambohitrolomahitsy · 1 300 bénéficiaires', 'drolung-branch' ),
		'region_2_name'     => __( 'École des Femmes', 'drolung-branch' ),
		'region_2_count'    => __( 'Anjozorobe · 50–100 femmes/mois', 'drolung-branch' ),
		'region_3_name'     => __( 'Forêt comestible', 'drolung-branch' ),
		'region_3_count'    => __( 'Anjozorobe · 15 familles', 'drolung-branch' ),
		'region_4_name'     => __( 'École post-cyclone', 'drolung-branch' ),
		'region_4_count'    => __( 'Tamatave · en évaluation', 'drolung-branch' ),

		// Nos engagements (piliers DSM — base commune).
		'engagement_1_label' => __( 'L\'essentiel vers le terrain', 'drolung-branch' ),
		'engagement_1_body'  => __( 'La quasi-totalité des dons collectés va aux projets à Madagascar. Les frais incompressibles (banque, obligations associatives) représentent environ 100 € par mois — soit moins de 3 % à l\'échelle annuelle.', 'drolung-branch' ),
		'engagement_2_label' => __( 'Un bureau bénévole', 'drolung-branch' ),
		'engagement_2_body'  => __( 'Le bureau et l\'ensemble des contributeurs réguliers travaillent sans rémunération.', 'drolung-branch' ),
		'engagement_3_label' => __( 'Transparence intégrale', 'drolung-branch' ),
		'engagement_3_body'  => __( 'Chaque euro engagé est suivi, documenté et rendu public dans nos comptes annuels.', 'drolung-branch' ),
		'engagement_4_label' => __( 'Un lien direct', 'drolung-branch' ),
		'engagement_4_body'  => __( 'Pas d\'intermédiaire entre le don et l\'action sur le terrain. Une seule chaîne, identifiable de bout en bout.', 'drolung-branch' ),

		// Newsletter.
		'newsletter_title'  => __( 'Suivez nos avancées', 'drolung-branch' ),
		'newsletter_body'   => __( 'Soyez informés en avant-première du lancement de nos projets.', 'drolung-branch' ),

		// Faire un don — exemples de coûts.
		'donate_title'         => __( 'Votre don <em>agit directement</em>', 'drolung-branch' ),
		'don_exemple_1_montant' => __( '11 000 €', 'drolung-branch' ),
		'don_exemple_1_desc'    => __( 'le coût d\'un captage de source gravitaire desservant 1 300 personnes en eau potable', 'drolung-branch' ),
		'don_exemple_2_montant' => __( '140 €', 'drolung-branch' ),
		'don_exemple_2_desc'    => __( 'une session mensuelle de l\'École des Femmes pour 50 à 100 participantes', 'drolung-branch' ),
		'don_exemple_3_montant' => __( '365 €', 'drolung-branch' ),
		'don_exemple_3_desc'    => __( 'un mois de formation et de suivi pour une famille de la forêt comestible d\'Anjozorobe', 'drolung-branch' ),
	];

	foreach ( $fields as $key => $val ) {
		update_field( $key, $val, $front_id );
	}
}

/**
 * DSF — corrige axe 3/4 (swap : 3=Eau, 4=Environnement), principe_1 (wording honnête),
 * engager_don_intro (hors frais incompressibles). Nécessaire car drolung_dsf_axes_v1
 * avait déjà seedé les anciennes valeurs en DB.
 * To replay: delete_option( 'drolung_dsf_home_copy_v1' ) on dsf.drolung.local.
 */
function drolung_seed_dsf_home_copy() {
	$front_id = (int) get_option( 'page_on_front' );
	$action   = get_page_by_path( 'notre-action' );
	$engager  = get_page_by_path( 's-engager' );

	// Axe 3/4 swap + principe_1 sur Notre action.
	if ( $action ) {
		$axe_fields = [
			'axe_3_tag'    => __( 'Eau & Assainissement', 'drolung-branch' ),
			'axe_3_title'  => __( 'L\'eau, avant tout', 'drolung-branch' ),
			'axe_3_body'   => '<p>' . __( 'Financer l\'accès à l\'eau potable et aux infrastructures sanitaires là où elles manquent le plus. Parce que sans eau, rien d\'autre n\'est possible.', 'drolung-branch' ) . '</p>',
			'axe_3_image'  => 'https://images.unsplash.com/photo-1569511166187-97b27af41b5a?auto=format&fit=crop&q=80&w=700&h=420',
			'axe_4_tag'    => __( 'Environnement', 'drolung-branch' ),
			'axe_4_title'  => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
			'axe_4_body'   => '<p>' . __( 'Encourager l\'agriculture vivrière, soutenir les coopératives et les artisans malgaches, préserver les écosystèmes dont dépendent les familles. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ) . '</p>',
			'axe_4_image'  => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=700&h=420',
			'principe_1_label' => __( 'L\'essentiel vers le terrain', 'drolung-branch' ),
			'principe_1_body'  => __( 'La quasi-totalité des dons collectés va aux projets à Madagascar. Les frais incompressibles (banque, obligations associatives) représentent environ 100 € par mois — soit moins de 3 % à l\'échelle annuelle.', 'drolung-branch' ),
		];
		foreach ( $axe_fields as $key => $val ) {
			update_field( $key, $val, $action->ID );
		}
	}

	// Engagements + donate_body sur la home page (drolung_dsf_home_v1 avait seedé les anciennes valeurs).
	if ( $front_id ) {
		$home_fields = [
			'engagement_1_label' => __( 'L\'essentiel vers le terrain', 'drolung-branch' ),
			'engagement_1_body'  => __( 'La quasi-totalité des dons collectés va aux projets à Madagascar. Les frais incompressibles (banque, obligations associatives) représentent environ 100 € par mois — soit moins de 3 % à l\'échelle annuelle.', 'drolung-branch' ),
			'engagement_2_label' => __( 'Un bureau bénévole', 'drolung-branch' ),
			'engagement_2_body'  => __( 'Le bureau de DSF et tous ses contributeurs sont bénévoles. À terme, DSM emploiera une équipe salariée sur place à Madagascar pour piloter les projets — c\'est précisément ce que nos dons rendent possible.', 'drolung-branch' ),
			'donate_body'        => '<p>' . __( 'Chaque euro versé à DSF est affecté aux projets portés par Drolung Solidarité Madagascar, hors frais administratifs incompressibles (banque + obligations légales, de l\'ordre de 100 € par mois). Les comptes de l\'association sont publiés chaque année dans un souci de transparence totale.', 'drolung-branch' ) . '</p>',
		];
		foreach ( $home_fields as $key => $val ) {
			update_field( $key, $val, $front_id );
		}
	}

	// engager_don_intro sur S'engager.
	if ( $engager ) {
		update_field(
			'engager_don_intro',
			__( 'Chaque euro versé à DSF est affecté aux projets portés par Drolung Solidarité Madagascar, hors frais administratifs incompressibles (banque + obligations légales, de l\'ordre de 100 € par mois).', 'drolung-branch' ),
			$engager->ID
		);
	}
}

/**
 * DSM — corrige axe 3/4 (swap : 3=Eau, 4=Environnement) et texte de la boîte
 * redirection vers DSF sur la page S'engager. Nécessaire car drolung_acf_seeded
 * avait déjà seedé les anciennes valeurs en DB.
 * To replay: delete_option( 'drolung_dsm_axes_v1' ) on dsm.drolung.local.
 */
function drolung_seed_dsm_axes_v1() {
	$front_id = (int) get_option( 'page_on_front' );
	$action   = get_page_by_path( 'notre-action' );
	$engager  = get_page_by_path( 's-engager' );

	// Engagements + donate_body sur la home page (drolung_front_v2 avait seedé les anciennes valeurs).
	if ( $front_id ) {
		$home_fields = [
			'engagement_1_label' => __( 'L\'essentiel vers le terrain', 'drolung-branch' ),
			'engagement_1_body'  => __( 'La quasi-totalité des dons collectés va aux projets à Madagascar. Les frais incompressibles (banque, obligations associatives) représentent environ 100 € par mois — soit moins de 3 % à l\'échelle annuelle.', 'drolung-branch' ),
			'engagement_2_label' => __( 'Un bureau bénévole', 'drolung-branch' ),
			'engagement_2_body'  => __( 'Le bureau et l\'ensemble des contributeurs réguliers travaillent sans rémunération.', 'drolung-branch' ),
			'donate_body'        => '<p>' . __( 'Chaque euro reçu est affecté aux projets portés par Drolung Solidarité Madagascar, hors frais administratifs incompressibles (banque + obligations légales, de l\'ordre de 100 € par mois). Les comptes de l\'association sont publiés chaque année.', 'drolung-branch' ) . '</p>',
		];
		foreach ( $home_fields as $key => $val ) {
			update_field( $key, $val, $front_id );
		}
	}

	if ( $action ) {
		$axe_fields = [
			'axe_3_tag'    => __( 'Eau & Assainissement', 'drolung-branch' ),
			'axe_3_title'  => __( 'L\'eau, avant tout', 'drolung-branch' ),
			'axe_3_body'   => '<p>' . __( 'Faciliter l\'accès à l\'eau potable, améliorer les conditions d\'hygiène et construire des infrastructures sanitaires durables. Parce que tout commence par une eau propre.', 'drolung-branch' ) . '</p>',
			'axe_3_image'  => 'https://images.unsplash.com/photo-1569511166187-97b27af41b5a?auto=format&fit=crop&q=80&w=700&h=420',
			'axe_4_tag'    => __( 'Environnement', 'drolung-branch' ),
			'axe_4_title'  => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
			'axe_4_body'   => '<p>' . __( 'Encourager l\'agriculture vivrière, soutenir les coopératives et les artisans, préserver les écosystèmes dont dépendent les familles. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ) . '</p>',
			'axe_4_image'  => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=700&h=420',
		];
		foreach ( $axe_fields as $key => $val ) {
			update_field( $key, $val, $action->ID );
		}
	}

	// Boîte redirection DSF dans S'engager DSM — wording honnête.
	if ( $engager ) {
		update_field(
			'engager_don_body',
			'<p style="font-size:14px;color:var(--text-muted);line-height:1.6;margin:0 0 16px">'
			. __( 'Ce choix garantit une traçabilité complète des fonds et une gouvernance transparente pour les donateurs français et européens.', 'drolung-branch' )
			. '</p>'
			. '<div style="background:var(--saffron-pale);border-left:3px solid var(--saffron);padding:20px 24px;margin-top:28px;border-radius:0 2px 2px 0">'
			. '<div style="font-weight:600;color:var(--charcoal);margin-bottom:8px;font-size:15px">'
			. __( 'Pour faire un don, rendez-vous sur le site de DSF', 'drolung-branch' )
			. '</div>'
			. '<p style="font-size:14px;color:var(--text-muted);line-height:1.6;margin:0 0 16px">'
			. __( 'Drolung Solidarité France — association loi 1901, équipe entièrement bénévole. La quasi-totalité des fonds collectés va aux projets DSM (frais bancaires et légaux incompressibles : env. 100 €/mois).', 'drolung-branch' )
			. '</p>'
			. '</div>',
			$engager->ID
		);
	}
}

/**
 * DSF — overrides des engagements et du chapeau donate sur la home page.
 * DSF collecte les dons qui vont aux projets de DSM : le wording doit le préciser.
 * To replay: delete_option( 'drolung_dsf_home_v1' ) on dsf.drolung.local.
 */
function drolung_seed_dsf_home() {
	$front_id = (int) get_option( 'page_on_front' );
	if ( ! $front_id ) {
		return;
	}

	$fields = [
		// Engagements — wording honnête DSF.
		'engagement_1_label' => __( 'L\'essentiel vers le terrain', 'drolung-branch' ),
		'engagement_1_body'  => __( 'La quasi-totalité des dons collectés va aux projets à Madagascar. Les frais incompressibles (banque, obligations associatives) représentent environ 100 € par mois — soit moins de 3 % à l\'échelle annuelle.', 'drolung-branch' ),
		'engagement_2_label' => __( 'Un bureau bénévole', 'drolung-branch' ),
		'engagement_2_body'  => __( 'Le bureau de DSF et tous ses contributeurs sont bénévoles. À terme, DSM emploiera une équipe salariée sur place à Madagascar pour piloter les projets — c\'est précisément ce que nos dons rendent possible.', 'drolung-branch' ),
		'engagement_3_label' => __( 'Transparence intégrale', 'drolung-branch' ),
		'engagement_3_body'  => __( 'Chaque euro engagé est suivi, documenté et rendu public dans nos comptes annuels.', 'drolung-branch' ),
		'engagement_4_label' => __( 'Un lien direct', 'drolung-branch' ),
		'engagement_4_body'  => __( 'Pas d\'intermédiaire entre le don à DSF et l\'action à Madagascar. Une seule association sœur, une seule destination.', 'drolung-branch' ),

		// Donate section — hors frais incompressibles.
		'donate_body'        => '<p>' . __( 'Chaque euro versé à DSF est affecté aux projets portés par Drolung Solidarité Madagascar, hors frais administratifs incompressibles (banque + obligations légales, de l\'ordre de 100 € par mois). Les comptes de l\'association sont publiés chaque année dans un souci de transparence totale.', 'drolung-branch' ) . '</p>',
	];

	foreach ( $fields as $key => $val ) {
		update_field( $key, $val, $front_id );
	}
}
