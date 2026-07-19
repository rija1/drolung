<?php
/**
 * front-page.php — branch landing page.
 *
 * Mirrors mockups/mockup-dsf/index.html (canonical) and mockup-dsm/index.html.
 * Per-site content differences (hero copy, intro text) are resolved via ACF
 * overrides seeded in mu-plugins/05-drolung-acf-seed.php.
 * Editable via the "Page d'accueil" ACF group (group_drolung_front).
 *
 * Sections:
 *   1. Hero
 *   2. Chiffres clés Madagascar
 *   3. Intro — qui nous sommes
 *   4. Nos axes d'action (4 axes, pulled from notre-action ACF fields)
 *   5. Nos projets preview (4 cards, projets cochés "featured_home")
 *   6. Nos engagements (4 pillars, static — editable via ACF)
 *   7. Newsletter
 *   8. Faire un don
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Resolve the donate URL once. Filter is set in drolung-branch functions.php. */
$donate_url = apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) );

/* Resolve the notre-action page ID once (for pulling axe fields). */
$notre_action_page = get_page_by_path( 'notre-action' );
$notre_action_id   = $notre_action_page ? $notre_action_page->ID : 0;

/*
 * Nos projets preview — 4 projets cochés "featured_home" sur leur fiche
 * (ACF, mu-plugins/drolung-network/inc/fields.php). Tant qu'aucun projet
 * n'est coché, on retombe sur les 4 plus récents pour ne pas laisser le
 * bloc vide (doc §6 : le site n'affiche jamais un trou de contenu).
 */
$featured_projets = array();
if ( function_exists( 'drolung_get_projets' ) ) {
	$featured_projets = drolung_get_projets( null, array(
		'posts_per_page' => 4,
		'meta_query'      => array(
			array(
				'key'   => 'featured_home',
				'value' => '1',
			),
		),
	) );
	if ( empty( $featured_projets ) ) {
		$featured_projets = drolung_get_projets( null, array( 'posts_per_page' => 4 ) );
	}
}
?>

<!-- SCROLL PROGRESS BAR (cosmetic) -->
<div id="scroll-progress" aria-hidden="true"></div>

<!-- HERO -->
<section class="hero" id="hero">
	<div class="hero-bg"<?php
		$hero_img = drolung_field( 'hero_image', '' );
		echo $hero_img ? ' style="background-image:url(' . esc_url( $hero_img ) . ');"' : '';
	?>></div>
	<div class="hero-overlay"></div>
	<div class="hero-saffron-line"></div>
	<div class="hero-content">
		<!-- <div class="hero-eyebrow"><?php echo esc_html( drolung_field( 'hero_eyebrow', __( 'Notre engagement', 'drolung-branch' ) ) ); ?></div> -->
		<h1 class="hero-title"><?php
			echo wp_kses_post( drolung_field( 'hero_title', __( 'Agir <em>localement</em>,<br>changer durablement.', 'drolung-branch' ) ) );
		?></h1>
		<p class="hero-sub"><?php echo esc_html( drolung_field( 'hero_sub', __( 'Une association de proximité qui soutient des projets concrets en éducation, santé et environnement, en partenariat avec les communautés locales.', 'drolung-branch' ) ) ); ?></p>
		<div class="hero-actions">
			<a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) ) ); ?>" class="btn-hero-primary"><?php echo esc_html( drolung_field( 'hero_cta1_label', __( 'Faire un don', 'drolung-branch' ) ) ); ?></a>
			<a href="<?php echo esc_url( drolung_field( 'hero_cta2_url', drolung_lang_url( 'notre-action' ) ) ); ?>" class="btn-hero-secondary"><?php echo esc_html( drolung_field( 'hero_cta2_label', __( 'Découvrir nos projets →', 'drolung-branch' ) ) ); ?></a>
		</div>
	</div>
	<div class="hero-scroll">
		<div class="hero-scroll__line"></div>
		<span><?php esc_html_e( 'Défiler', 'drolung-branch' ); ?></span>
	</div>
</section>

<!-- CHIFFRES CLÉS MADAGASCAR -->
<section class="chiffres-section">
	<div class="chiffres-inner">
		<div class="chiffres-header fade-up">
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'chiffres_eyebrow', __( 'La réalité du terrain', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'chiffres_title', __( 'Madagascar en chiffres', 'drolung-branch' ) ) ); ?></h2>
		</div>
		<div class="chiffres-grid">
			<?php
			$chiffres_defaults = [
				1 => [
					'num'   => drolung_field( 'chiffre_1_num',   '80 %' ),
					'label' => drolung_field( 'chiffre_1_label', __( 'de la population vit sous le seuil de pauvreté', 'drolung-branch' ) ),
					'delay' => '',
				],
				2 => [
					'num'   => drolung_field( 'chiffre_2_num',   '44 %' ),
					'label' => drolung_field( 'chiffre_2_label', __( 'n\'ont pas accès à une eau potable améliorée', 'drolung-branch' ) ),
					'delay' => 'transition-delay:0.07s',
				],
				3 => [
					'num'   => drolung_field( 'chiffre_3_num',   '39,8 %' ),
					'label' => drolung_field( 'chiffre_3_label', __( 'des enfants souffrent de malnutrition chronique', 'drolung-branch' ) ),
					'delay' => 'transition-delay:0.14s',
				],
				4 => [
					'num'   => drolung_field( 'chiffre_4_num',   '177' ),
					'label' => drolung_field( 'chiffre_4_label', __( 'sur 193 pays à l\'Indice de Développement Humain', 'drolung-branch' ) ),
					'delay' => 'transition-delay:0.21s',
				],
				5 => [
					'num'   => drolung_field( 'chiffre_5_num',   '54 %' ),
					'label' => drolung_field( 'chiffre_5_label', __( 'de défécation à l\'air libre en zones rurales', 'drolung-branch' ) ),
					'delay' => 'transition-delay:0.28s',
				],
				6 => [
					'num'   => drolung_field( 'chiffre_6_num',   '1/16' ),
					'label' => drolung_field( 'chiffre_6_label', __( 'enfants ne survit pas jusqu\'à ses 5 ans', 'drolung-branch' ) ),
					'delay' => 'transition-delay:0.35s',
				],
			];
			foreach ( $chiffres_defaults as $chiffre ) :
				$style = $chiffre['delay'] ? ' style="' . esc_attr( $chiffre['delay'] ) . '"' : '';
				?>
				<div class="chiffre-card fade-up"<?php echo $style; ?>>
					<div class="chiffre-card__num"><?php echo wp_kses_post( $chiffre['num'] ); ?></div>
					<div class="chiffre-card__label"><?php echo esc_html( $chiffre['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="chiffres-cta fade-up"><?php echo esc_html( drolung_field( 'chiffres_cta', __( 'C\'est cette réalité que nos projets cherchent à changer — concrètement, durablement, depuis le terrain.', 'drolung-branch' ) ) ); ?></p>
		<p style="margin-top:18px;font-family:var(--font-mono);font-size:11px;letter-spacing:0.05em;color:rgba(255,255,255,0.4);text-align:center;"><?php esc_html_e( 'Sources : Banque mondiale (2022) · EDS Madagascar / UNICEF (2021) · PNUD, Rapport sur le développement humain 2023-24 · OMS-UNICEF JMP', 'drolung-branch' ); ?></p>
	</div>
</section>


<!-- NOS PROJETS PREVIEW -->
<section class="inner-section inner-section--tint">
	<div class="container">
		<div class="projets-preview-header fade-up" style="display:flex;align-items:flex-end;justify-content:space-between;gap:40px;flex-wrap:wrap;margin-bottom:48px;">
			<div>
				<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'map_eyebrow', __( 'Nos projets', 'drolung-branch' ) ) ); ?></div>
				<h2 class="section-title" style="margin-bottom:0"><?php echo wp_kses_post( drolung_field( 'map_title', __( 'Quatre projets <em>en cours de montage</em>', 'drolung-branch' ) ) ); ?></h2>
			</div>
			<a href="<?php echo esc_url( drolung_lang_url( 'projets' ) ); ?>" class="btn-text"><?php echo esc_html( drolung_pll__( 'Voir tous les projets →' ) ); ?></a>
		</div>

		<?php if ( ! empty( $featured_projets ) ) : ?>
			<div class="four-col">
				<?php foreach ( $featured_projets as $i => $item ) :
					$statut_slugs = array_keys( $item['statut'] );
					$statut_slug  = $statut_slugs[0] ?? '';
					$statut_name  = $item['statut'][ $statut_slug ] ?? '';
					$thumb_url    = isset( $item['thumbnail']['large'] ) ? $item['thumbnail']['large'] : '';
					$permalink    = function_exists( 'drolung_item_permalink' ) ? drolung_item_permalink( $item ) : home_url( '/projets/' . $item['slug'] . '/' );
					$delay        = ( $i % 4 ) * 0.08;
					?>
					<div class="card project-card fade-up"<?php echo $delay > 0 ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
						<div class="card-img" style="position:relative;background:var(--cream);">
							<?php if ( $thumb_url ) : ?>
								<a href="<?php echo esc_url( $permalink ); ?>">
									<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy">
								</a>
							<?php endif; ?>
							<?php if ( $statut_name ) : ?>
								<span class="project-status" style="position:absolute;top:14px;right:14px;padding:6px 12px;border-radius:2px;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;background:rgba(255,255,255,0.95);color:var(--saffron);border:1px solid var(--saffron);">
									<?php echo esc_html( drolung_translate_term_name( $statut_name ) ); ?>
								</span>
							<?php endif; ?>
						</div>
						<div class="card-body">
							<div class="card-title">
								<a href="<?php echo esc_url( $permalink ); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html( $item['title'] ); ?></a>
							</div>
							<?php if ( $item['excerpt'] ) : ?>
								<p class="card-desc"><?php echo esc_html( wp_trim_words( $item['excerpt'], 20, '…' ) ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="section-body"><?php esc_html_e( 'Nos premiers projets seront publiés ici très bientôt.', 'drolung-branch' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<!-- INTRO — QUI NOUS SOMMES -->
<section class="intro-section">
	<div class="intro-grid">
		<div class="intro-visual fade-up">
			<div class="intro-accent"></div>
			<?php
			$intro_img = drolung_field( 'intro_image', 'https://images.unsplash.com/photo-1504598578017-40d9b776f1bc?auto=format&fit=crop&q=80&w=700&h=880' );
			?>
			<img src="<?php echo esc_url( $intro_img ); ?>" alt="" class="intro-img" loading="lazy">
			<div class="intro-badge">
				<div class="intro-badge__num"><?php echo esc_html( drolung_field( 'intro_badge_num', '2026' ) ); ?></div>
				<div class="intro-badge__label"><?php echo esc_html( drolung_field( 'intro_badge_label', __( 'Année de création', 'drolung-branch' ) ) ); ?></div>
			</div>
		</div>
		<div class="intro-text fade-up" style="transition-delay:0.15s">
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'intro_eyebrow', __( 'Qui nous sommes', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'intro_title', __( 'Une association ancrée dans la <em>solidarité</em>', 'drolung-branch' ) ) ); ?></h2>
			<div class="section-body"><?php
				$intro_body = drolung_field( 'intro_body', '<p>' . __( 'Drolung Solidarité réunit des bénévoles autour d\'une conviction simple : l\'aide la plus efficace est celle qui s\'enracine dans les besoins exprimés par les communautés elles-mêmes. Nous travaillons en lien direct avec les acteurs de terrain.', 'drolung-branch' ) . '</p>' );
				echo wp_kses_post( $intro_body );
			?></div>
			<a href="<?php echo esc_url( drolung_lang_url( 'a-propos' ) ); ?>" class="btn-text" style="margin-top:32px">
				<?php echo esc_html( drolung_field( 'intro_cta_label', __( 'Notre histoire & mission →', 'drolung-branch' ) ) ); ?>
			</a>
		</div>
	</div>
</section>



<!-- NOS AXES D'ACTION — 4 axes, pulled from page Notre action ACF fields -->
<?php
/*
 * Defaults for any branch not yet seeded.
 * DSF overrides axe_1..4 via drolung_dsf_axes_v1 seed flag.
 * See mu-plugins/05-drolung-acf-seed.php.
 */
$home_axe_defaults = [
	1 => [
		'num'    => '01',
		'domain' => __( 'Éducation', 'drolung-branch' ),
		'title'  => __( 'Apprendre, transmettre, faire grandir', 'drolung-branch' ),
		'desc'   => __( 'Donner aux enfants les moyens d\'aller à l\'école, accompagner les jeunes dans leur parcours, soutenir les passeurs de savoirs locaux.', 'drolung-branch' ),
	],
	2 => [
		'num'    => '02',
		'domain' => __( 'Santé', 'drolung-branch' ),
		'title'  => __( 'Prendre soin, sans condition', 'drolung-branch' ),
		'desc'   => __( 'Soutenir l\'accès aux soins de base, les structures de santé locales et la santé maternelle et infantile.', 'drolung-branch' ),
	],
	3 => [
		'num'    => '03',
		'domain' => __( 'Eau & Assainissement', 'drolung-branch' ),
		'title'  => __( 'L\'eau, avant tout', 'drolung-branch' ),
		'desc'   => __( 'Financer l\'accès à l\'eau potable et aux infrastructures sanitaires là où elles manquent le plus. Parce que sans eau, rien d\'autre n\'est possible.', 'drolung-branch' ),
	],
	4 => [
		'num'    => '04',
		'domain' => __( 'Environnement', 'drolung-branch' ),
		'title'  => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
		'desc'   => __( 'Encourager l\'agriculture vivrière, soutenir les coopératives et les artisans, préserver les écosystèmes dont dépendent les familles. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ),
	],
];
?>
<!-- <section class="programmes-section">
	<div class="programmes-header fade-up">
		<div>
			<div class="section-eyebrow"><?php esc_html_e( 'Notre action', 'drolung-branch' ); ?></div>
			<h2 class="section-title"><?php
				printf( '%s <em>%s</em>',
					esc_html__( 'Quatre domaines,', 'drolung-branch' ),
					esc_html__( 'une seule conviction', 'drolung-branch' )
				);
			?></h2>
		</div>
		<a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>" class="btn-text"><?php esc_html_e( 'Voir tout →', 'drolung-branch' ); ?></a>
	</div>
	<div class="prog-grid prog-grid--four">
		<?php foreach ( $home_axe_defaults as $i => $d ) :
			/* Pull from the Notre action page's ACF fields when available.
			 * get_field() only exists when ACF Pro is active (central site).
			 * On branches, drolung_field() fallback covers us. */
			$domain = ( $notre_action_id && function_exists( 'get_field' ) ) ? get_field( "axe_{$i}_tag",   $notre_action_id ) : '';
			$title  = ( $notre_action_id && function_exists( 'get_field' ) ) ? get_field( "axe_{$i}_title", $notre_action_id ) : '';
			$desc   = ( $notre_action_id && function_exists( 'get_field' ) ) ? get_field( "axe_{$i}_body",  $notre_action_id ) : '';
			if ( ! $domain ) { $domain = $d['domain']; }
			if ( ! $title )  { $title  = $d['title'];  }
			if ( ! $desc )   { $desc   = $d['desc'];   }
			$delay = ( $i - 1 ) * 0.08;
			?>
			<a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>" class="prog-card fade-up"<?php echo $delay > 0 ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
				<span class="prog-card__num"><?php echo esc_html( $d['num'] ); ?></span>
				<span class="prog-card__domain"><?php echo esc_html( $domain ); ?></span>
				<div class="prog-card__title"><?php echo esc_html( $title ); ?></div>
				<p class="prog-card__desc"><?php echo esc_html( wp_strip_all_tags( wp_trim_words( $desc, 28, '…' ) ) ); ?></p>
				<span class="prog-card__link"><?php esc_html_e( 'En savoir plus →', 'drolung-branch' ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section> -->

<!-- NOS ENGAGEMENTS (4 pillars) -->
<!-- <section class="testimonial-section">
	<div class="testimonial-inner fade-up">
		<div class="engagements-grid">
			<?php
			$engagements_defaults = [
				1 => [
					'label' => drolung_field( 'engagement_1_label', __( 'L\'essentiel vers le terrain', 'drolung-branch' ) ),
					'body'  => drolung_field( 'engagement_1_body',  __( 'La quasi-totalité des dons collectés va aux projets à Madagascar. Les frais incompressibles (banque, obligations associatives) représentent environ 100 € par mois — soit moins de 3 % à l\'échelle annuelle.', 'drolung-branch' ) ),
				],
				2 => [
					'label' => drolung_field( 'engagement_2_label', __( 'Un bureau bénévole', 'drolung-branch' ) ),
					'body'  => drolung_field( 'engagement_2_body',  __( 'Le bureau de DSF et tous ses contributeurs sont bénévoles. À terme, DSM emploiera une équipe salariée sur place à Madagascar pour piloter les projets — c\'est précisément ce que nos dons rendent possible.', 'drolung-branch' ) ),
				],
				3 => [
					'label' => drolung_field( 'engagement_3_label', __( 'Transparence intégrale', 'drolung-branch' ) ),
					'body'  => drolung_field( 'engagement_3_body',  __( 'Chaque euro engagé est suivi, documenté et rendu public dans nos comptes annuels.', 'drolung-branch' ) ),
				],
				4 => [
					'label' => drolung_field( 'engagement_4_label', __( 'Un lien direct', 'drolung-branch' ) ),
					'body'  => drolung_field( 'engagement_4_body',  __( 'Pas d\'intermédiaire entre le don à DSF et l\'action à Madagascar.', 'drolung-branch' ) ),
				],
			];
			foreach ( $engagements_defaults as $eng ) :
				?>
				<div>
					<div style="font-family:var(--font-serif);font-style:italic;font-size:1.3rem;color:var(--saffron-lt);margin-bottom:10px"><?php echo esc_html( $eng['label'] ); ?></div>
					<p style="font-size:13.5px;color:var(--text-muted);line-height:1.6;margin:0"><?php echo esc_html( $eng['body'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section> -->

<!-- FAIRE UN DON -->
<section class="donate-section" id="donate">
	<div class="donate-inner">
		<div class="donate-text fade-up">
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'donate_eyebrow', __( 'Faire un don', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'donate_title', __( 'Votre don <em>agit directement</em>', 'drolung-branch' ) ) ); ?></h2>
			<div class="section-body" style="margin-bottom:32px"><?php echo wp_kses_post( drolung_field( 'donate_body', '<p>' . __( 'Chaque euro versé à DSF est affecté aux projets portés par Drolung Solidarité Madagascar, hors frais administratifs incompressibles (banque + obligations légales, de l\'ordre de 100 € par mois). Les comptes de l\'association sont publiés chaque année dans un souci de transparence totale.', 'drolung-branch' ) . '</p>' ) ); ?></div>

			<!-- Exemples de coûts (statiques — éditables via ACF) -->
			<div style="display:flex;flex-direction:column;gap:16px;margin-bottom:40px">
				<?php
				$don_exemples = [
					1 => [
						'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color:var(--saffron);display:inline-block;vertical-align:-0.12em" aria-hidden="true"><path d="M12 3.5C12 3.5 6 10.2 6 14.5a6 6 0 0 0 12 0C18 10.2 12 3.5 12 3.5Z"/></svg>',
						'montant' => drolung_field( 'don_exemple_1_montant', __( '11 000 €', 'drolung-branch' ) ),
						'desc'    => drolung_field( 'don_exemple_1_desc',    __( 'le coût d\'un captage de source gravitaire desservant 1 300 personnes en eau potable', 'drolung-branch' ) ),
					],
					2 => [
						'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color:var(--saffron);display:inline-block;vertical-align:-0.12em" aria-hidden="true"><path d="M12 6.8C10.1 5.2 7.6 4.5 4.5 4.5v13.2c3.1 0 5.6.7 7.5 2.3 1.9-1.6 4.4-2.3 7.5-2.3V4.5c-3.1 0-5.6.7-7.5 2.3Z"/><path d="M12 6.8V20"/></svg>',
						'montant' => drolung_field( 'don_exemple_2_montant', __( '140 €', 'drolung-branch' ) ),
						'desc'    => drolung_field( 'don_exemple_2_desc',    __( 'une session mensuelle de l\'École des Femmes pour 50 à 100 participantes', 'drolung-branch' ) ),
					],
					3 => [
						'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color:var(--saffron);display:inline-block;vertical-align:-0.12em" aria-hidden="true"><path d="M12 21v-8"/><path d="M12 13c0-4-2.6-6.5-6.5-6.5C5.5 10.4 8.1 13 12 13Z"/><path d="M12 11c0-3.4 2.2-5.5 5.5-5.5C17.5 8.9 15.3 11 12 11Z"/></svg>',
						'montant' => drolung_field( 'don_exemple_3_montant', __( '365 €', 'drolung-branch' ) ),
						'desc'    => drolung_field( 'don_exemple_3_desc',    __( 'un mois de formation et de suivi pour une famille de la forêt comestible d\'Anjozorobe', 'drolung-branch' ) ),
					],
				];
				foreach ( $don_exemples as $ex ) :
					?>
					<div style="display:flex;align-items:flex-start;gap:12px">
						<div style="width:36px;height:36px;border-radius:50%;background:rgba(193,125,10,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--saffron);font-size:19px;margin-top:2px">
							<?php echo $ex['icon']; // phpcs:ignore — trusted static SVG ?>
						</div>
						<div style="font-size:14px;color:rgba(255,255,255,0.65)">
							<strong style="color:rgba(255,255,255,0.9)"><?php echo esc_html( $ex['montant'] ); ?></strong>
							<?php echo ' — ' . esc_html( $ex['desc'] ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) ) ); ?>" class="btn-hero-primary"><?php echo esc_html( drolung_field( 'donate_cta_label', apply_filters( 'drolung_donate_label', __( 'Faire un don', 'drolung-branch' ) ) ) ); ?></a>
		</div>
	</div>
</section>


<!-- NEWSLETTER -->
<section class="newsletter-section">
	<div class="newsletter-inner fade-up">
		<div class="newsletter-text">
			<h2 class="newsletter-title"><?php echo esc_html( drolung_field( 'newsletter_title', __( 'Suivez nos avancées', 'drolung-branch' ) ) ); ?></h2>
			<p class="newsletter-body"><?php echo esc_html( drolung_field( 'newsletter_body', __( 'Soyez informés en avant-première du lancement de nos projets.', 'drolung-branch' ) ) ); ?></p>
		</div>
		<div>
			<form class="newsletter-form" action="#" method="post" novalidate>
				<label style="position:absolute;left:-9999px" for="nl-email-branch"><?php esc_html_e( 'Adresse e-mail', 'drolung-branch' ); ?></label>
				<input id="nl-email-branch" type="email" name="nl_email" placeholder="<?php echo esc_attr( drolung_field( 'newsletter_placeholder', __( 'Votre adresse e-mail', 'drolung-branch' ) ) ); ?>" required>
				<button type="submit"><?php echo esc_html( drolung_field( 'newsletter_cta_label', __( 'Je m\'inscris', 'drolung-branch' ) ) ); ?></button>
			</form>
			<div class="newsletter-message" aria-live="polite"></div>
		</div>
	</div>
</section>

<?php
get_footer();
