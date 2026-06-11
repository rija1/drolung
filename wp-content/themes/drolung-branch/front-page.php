<?php
/**
 * front-page.php — branch landing page.
 *
 * Editable via the "Page d'accueil" ACF group on the front page.
 * Lists that need true repeaters (programmes, news) are still static
 * here — they'll move to CPTs (`programme`, `news`) in Phase 4.
 *
 * @package drolung-branch
 */

get_header();

/* Resolve the donate URL once. Filter is set in drolung-branch functions.php. */
$donate_url = apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) );

/* All static defaults below are French and branch-agnostic. ACF overrides
 * any of these on a per-site basis when the admin enters values. */
?>

<!-- HERO -->
<section class="hero" id="hero">
	<div class="hero-bg"<?php
		$hero_img = drolung_field( 'hero_image', '' );
		echo $hero_img ? ' style="background-image:url(' . esc_url( $hero_img ) . ');"' : '';
	?>></div>
	<div class="hero-overlay"></div>
	<div class="hero-saffron-line"></div>
	<div class="hero-content">
		<div class="hero-eyebrow"><?php echo esc_html( drolung_field( 'hero_eyebrow', __( 'Notre engagement', 'drolung-branch' ) ) ); ?></div>
		<h1 class="hero-title"><?php
			echo wp_kses_post( drolung_field( 'hero_title', __( 'Agir <em>localement</em>,<br>changer durablement.', 'drolung-branch' ) ) );
		?></h1>
		<p class="hero-sub"><?php echo esc_html( drolung_field( 'hero_sub', __( 'Une association de proximité qui soutient des projets concrets en éducation, santé et environnement, en partenariat avec les communautés locales.', 'drolung-branch' ) ) ); ?></p>
		<div class="hero-actions">
			<a href="<?php echo esc_url( drolung_field( 'hero_cta1_url', $donate_url ) ); ?>" class="btn-hero-primary"><?php echo esc_html( drolung_field( 'hero_cta1_label', __( 'Soutenir notre action', 'drolung-branch' ) ) ); ?></a>
			<a href="<?php echo esc_url( drolung_field( 'hero_cta2_url', home_url( '/notre-action/' ) ) ); ?>" class="btn-hero-secondary"><?php echo esc_html( drolung_field( 'hero_cta2_label', __( 'Découvrir nos actions →', 'drolung-branch' ) ) ); ?></a>
		</div>
	</div>
	<div class="hero-scroll">
		<div class="hero-scroll__line"></div>
		<span><?php esc_html_e( 'Défiler', 'drolung-branch' ); ?></span>
	</div>
</section>

<!-- IMPACT BAND -->
<div class="impact-band">
	<div class="impact-band__inner">
		<?php
		$impact_defaults = [
			1 => [ 'num' => '3',   'label' => __( 'Axes d\'intervention',   'drolung-branch' ) ],
			2 => [ 'num' => '100%', 'label' => __( 'Bénévoles',              'drolung-branch' ) ],
			3 => [ 'num' => '0',   'label' => __( 'Projets en cours',        'drolung-branch' ) ],
			4 => [ 'num' => '2026','label' => __( 'Année de création',       'drolung-branch' ) ],
		];
		foreach ( $impact_defaults as $i => $d ) :
			$num   = drolung_field( "impact_{$i}_num",   $d['num'] );
			$label = drolung_field( "impact_{$i}_label", $d['label'] );
			$delay = ( $i - 1 ) * 0.1;
			?>
			<div class="impact-stat fade-up"<?php echo $delay > 0 ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
				<div class="impact-stat__num"><?php echo esc_html( $num ); ?></div>
				<div class="impact-stat__label"><?php echo esc_html( $label ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<!-- INTRO -->
<section class="intro-section">
	<div class="intro-grid">
		<div class="intro-visual fade-up">
			<div class="intro-accent"></div>
			<?php
			$intro_img       = drolung_field( 'intro_image', 'https://images.unsplash.com/photo-1592334934411-8c2b49989d29?auto=format&fit=crop&q=80&w=700&h=880' );
			$intro_badge_num = drolung_field( 'intro_badge_num', '2026' );
			$intro_badge_lbl = drolung_field( 'intro_badge_label', __( 'Année de création', 'drolung-branch' ) );
			?>
			<img src="<?php echo esc_url( $intro_img ); ?>" alt="" class="intro-img" loading="lazy">
			<div class="intro-badge">
				<div class="intro-badge__num"><?php echo esc_html( $intro_badge_num ); ?></div>
				<div class="intro-badge__label"><?php echo esc_html( $intro_badge_lbl ); ?></div>
			</div>
		</div>
		<div class="intro-text fade-up" style="transition-delay:0.15s">
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'intro_eyebrow', __( 'Qui nous sommes', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'intro_title', __( 'Une association ancrée dans la <em>solidarité</em>', 'drolung-branch' ) ) ); ?></h2>
			<div class="section-body"><?php
				$intro_body = drolung_field( 'intro_body', '<p>' . __( 'Drolung Solidarité réunit des bénévoles autour d\'une conviction simple : l\'aide la plus efficace est celle qui s\'enracine dans les besoins exprimés par les communautés elles-mêmes. Nous travaillons en lien direct avec les acteurs de terrain.', 'drolung-branch' ) . '</p>' );
				echo wp_kses_post( $intro_body );
			?></div>
			<a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>" class="btn-text" style="margin-top:32px">
				<?php echo esc_html( drolung_field( 'intro_cta_label', __( 'Notre histoire & mission →', 'drolung-branch' ) ) ); ?>
			</a>
		</div>
	</div>
</section>

<!-- PROGRAMMES — pulled from the Notre action page's ACF fields so editing
     happens in one place and propagates to the homepage. -->
<?php
$notre_action_page = get_page_by_path( 'notre-action' );
$notre_action_id   = $notre_action_page ? $notre_action_page->ID : 0;

/* Default trio for any branch that hasn't been seeded yet. */
$home_axe_defaults = [
	1 => [ 'tag' => __( 'Éducation',     'drolung-branch' ), 'desc' => __( 'Soutien à la scolarité, équipement d\'écoles, formation des enseignants et accès aux fournitures.', 'drolung-branch' ) ],
	2 => [ 'tag' => __( 'Santé',         'drolung-branch' ), 'desc' => __( 'Équipement de dispensaires, soutien aux soins maternels et infantiles, accès aux médicaments essentiels.', 'drolung-branch' ) ],
	3 => [ 'tag' => __( 'Environnement', 'drolung-branch' ), 'desc' => __( 'Reboisement, agriculture vivrière durable et accès à l\'eau potable dans les zones rurales.', 'drolung-branch' ) ],
];

$home_icons = [
	1 => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
	2 => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
	3 => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
];
?>
<section class="programmes-section">
	<div class="programmes-header fade-up">
		<div>
			<div class="section-eyebrow"><?php esc_html_e( 'Notre action', 'drolung-branch' ); ?></div>
			<h2 class="section-title"><?php
				printf( '%s <em>%s</em>', esc_html__( 'Trois axes d\'', 'drolung-branch' ), esc_html__( 'intervention', 'drolung-branch' ) );
			?></h2>
		</div>
		<a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>" class="btn-text"><?php esc_html_e( 'Voir tout →', 'drolung-branch' ); ?></a>
	</div>
	<div class="prog-grid">
		<?php foreach ( $home_axe_defaults as $i => $d ) :
			$tag  = $notre_action_id ? get_field( "axe_{$i}_tag",  $notre_action_id ) : '';
			$desc = $notre_action_id ? get_field( "axe_{$i}_body", $notre_action_id ) : '';
			if ( ! $tag )  { $tag  = $d['tag']; }
			if ( ! $desc ) { $desc = $d['desc']; }
			$delay = ( $i - 1 ) * 0.08;
			?>
			<div class="prog-card fade-up"<?php echo $delay > 0 ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
				<div class="prog-card__num"><?php echo esc_html( sprintf( '%02d', $i ) ); ?></div>
				<div class="prog-card__icon"><?php echo $home_icons[ $i ]; // phpcs:ignore — trusted static SVG ?></div>
				<div class="prog-card__title"><?php echo esc_html( $tag ); ?></div>
				<p class="prog-card__desc"><?php echo esc_html( wp_strip_all_tags( wp_trim_words( $desc, 28, '…' ) ) ); ?></p>
				<a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>" class="prog-card__link"><?php esc_html_e( 'En savoir plus →', 'drolung-branch' ); ?></a>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- WHERE WE WORK -->
<section class="map-section">
	<div class="map-inner">
		<div class="map-text fade-up">
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'map_eyebrow', __( 'Où nous intervenons', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'map_title', __( 'Sur le <em>terrain</em>', 'drolung-branch' ) ) ); ?></h2>
			<div class="section-body"><?php echo wp_kses_post( drolung_field( 'map_body', '<p>' . __( 'Nos projets se concentrent sur quelques régions choisies pour la qualité de nos liens locaux et la pertinence des besoins identifiés.', 'drolung-branch' ) . '</p>' ) ); ?></div>
			<a href="<?php echo esc_url( home_url( '/ou-nous-intervenons/' ) ); ?>" class="btn-text" style="margin-top:36px; color: var(--saffron-lt); border-color: var(--saffron);"><?php esc_html_e( 'Voir toutes les régions →', 'drolung-branch' ); ?></a>
		</div>
		<div class="map-visual fade-up" style="transition-delay:0.15s">
			<img src="https://images.unsplash.com/photo-1666281269793-da06484657e8?auto=format&fit=crop&q=80&w=900&h=450" alt="" class="map-photo" loading="lazy">
			<img src="https://images.unsplash.com/photo-1624272909636-4995421e37e7?auto=format&fit=crop&q=80&w=440&h=340" alt="" class="map-photo" loading="lazy">
			<img src="https://images.unsplash.com/photo-1632215861513-130b66fe97f4?auto=format&fit=crop&q=80&w=440&h=340" alt="" class="map-photo" loading="lazy">
		</div>
	</div>
</section>

<!-- TESTIMONIAL -->
<?php
$test_text  = drolung_field( 'test_text', '' );
$test_name  = drolung_field( 'test_author_name', '' );
$test_role  = drolung_field( 'test_author_role', '' );
$test_photo = drolung_field( 'test_author_photo', '' );
if ( $test_text ) : ?>
<section class="testimonial-section">
	<div class="testimonial-inner fade-up">
		<p class="testimonial-text">« <?php echo esc_html( $test_text ); ?> »</p>
		<?php if ( $test_name || $test_role ) : ?>
			<div class="testimonial-divider"></div>
			<div class="testimonial-person">
				<?php if ( $test_photo ) : ?>
					<img src="<?php echo esc_url( $test_photo ); ?>" alt="" style="width:56px;height:56px;border-radius:50%;object-fit:cover">
				<?php endif; ?>
				<?php if ( $test_name ) : ?><div class="testimonial-name"><?php echo esc_html( $test_name ); ?></div><?php endif; ?>
				<?php if ( $test_role ) : ?><div class="testimonial-role"><?php echo esc_html( $test_role ); ?></div><?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<!-- DONATE -->
<section class="donate-section" id="donate">
	<div class="donate-inner">
		<div class="donate-text fade-up">
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'donate_eyebrow', __( 'Faire un don', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'donate_title', __( 'Votre don <em>change</em> les choses', 'drolung-branch' ) ) ); ?></h2>
			<div class="section-body" style="margin-bottom:32px"><?php echo wp_kses_post( drolung_field( 'donate_body', '<p>' . __( 'Chaque euro reçu est affecté directement à un projet concret. Les comptes de l\'association sont publiés chaque année dans un souci de transparence totale.', 'drolung-branch' ) . '</p>' ) ); ?></div>
			<a href="<?php echo esc_url( $donate_url ); ?>" class="btn-hero-primary"><?php esc_html_e( 'Faire un don', 'drolung-branch' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
