<?php
/**
 * Template for the Projet CPT archive — "Nos projets" listing page.
 * Mirrors mockups/mockup-dsf/projets.html (canonical DSF reference).
 * DSM uses the same template; per-site copy editable via ACF.
 *
 * Boucle de projets : WP_Query directe sur le CPT 'projet'.
 * TODO: migrer vers drolung_get_projets( $branch, $args ) quand drolung-network est actif.
 *
 * Filtres statut/type : HTML statique depuis le mockup, JS client-side ci-dessous.
 * L'état initial affiche toutes les cards ; le JS les filtre à la volée.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// TODO: migrer vers drolung_get_projets( $branch, $args ) quand drolung-network est actif.
$projets = new WP_Query( array(
	'post_type'      => 'projet',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
) );

/* Collect taxonomy term slugs actually used so the filter bar stays
 * in sync with real content. Falls back to seeded defaults if empty. */
$used_types   = array();
$used_statuts = array();
if ( $projets->have_posts() ) {
	foreach ( $projets->posts as $p ) {
		foreach ( (array) get_the_terms( $p->ID, 'projet_type' ) as $t ) {
			if ( $t && ! is_wp_error( $t ) ) {
				$used_types[ $t->slug ] = $t->name;
			}
		}
		foreach ( (array) get_the_terms( $p->ID, 'projet_statut' ) as $t ) {
			if ( $t && ! is_wp_error( $t ) ) {
				$used_statuts[ $t->slug ] = $t->name;
			}
		}
	}
}
if ( empty( $used_types ) ) {
	$used_types = array(
		'eau'           => __( 'Eau', 'drolung-branch' ),
		'education'     => __( 'Éducation', 'drolung-branch' ),
		'environnement' => __( 'Environnement', 'drolung-branch' ),
		'sante'         => __( 'Santé', 'drolung-branch' ),
		'agriculture'   => __( 'Agriculture', 'drolung-branch' ),
	);
}
if ( empty( $used_statuts ) ) {
	$used_statuts = array(
		'en-preparation' => __( 'En préparation', 'drolung-branch' ),
		'en-evaluation'  => __( 'En évaluation', 'drolung-branch' ),
		'en-cours'       => __( 'En cours', 'drolung-branch' ),
		'termine'        => __( 'Terminé', 'drolung-branch' ),
	);
}
?>

<div class="page-breadcrumb">
	<div class="container">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
		<span>›</span>
		<span><?php esc_html_e( 'Nos projets', 'drolung-branch' ); ?></span>
	</div>
</div>

<?php
$hero_image_url = drolung_field(
	'projets_hero_image',
	'https://images.unsplash.com/photo-1570742544137-3a469196c32b?auto=format&fit=crop&q=80&w=1600&h=700'
);
?>
<section class="page-hero" style="--hero-bg: url('<?php echo esc_url( $hero_image_url ); ?>');">
	<style>.page-hero::before { background-image: var(--hero-bg); }</style>
	<div class="page-hero__line"></div>
	<div class="container">
		<div class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'projets_hero_eyebrow', __( 'Nos projets', 'drolung-branch' ) ) ); ?></div>
		<h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'projets_hero_title', __( 'Quatre projets, <em>une même conviction</em>', 'drolung-branch' ) ) ); ?></h1>
		<p class="page-hero__sub"><?php echo esc_html( drolung_field( 'projets_hero_sub', __( 'Les projets que Drolung Solidarité finance et accompagne, portés sur le terrain par notre association sœur.', 'drolung-branch' ) ) ); ?></p>
	</div>
</section>

<section class="inner-section inner-section--tint">
	<div class="projets-intro fade-up">
		<div>
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'projets_intro_eyebrow', __( 'Notre soutien', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'projets_intro_title', __( 'Nos projets <em>en cours de montage</em>', 'drolung-branch' ) ) ); ?></h2>
			<p class="section-body" style="margin-top:16px;"><?php echo esc_html( drolung_field( 'projets_intro_body', __( 'Ces projets sont en cours de montage ou en recherche de financement. Tous sont portés sur le terrain par nos associations sœurs. Vos dons les rendent possibles, directement et sans intermédiaire.', 'drolung-branch' ) ) ); ?></p>
		</div>
		<div class="projets-intro__filters">
			<div class="project-filters">
				<div class="filter-group">
					<div class="filter-group__label"><?php esc_html_e( 'Statut', 'drolung-branch' ); ?></div>
					<div class="filter-group__btns">
						<button class="filter-btn active" data-status="all"><?php esc_html_e( 'Tous', 'drolung-branch' ); ?></button>
						<?php foreach ( $used_statuts as $slug => $name ) : ?>
							<button class="filter-btn" data-status="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="filter-group">
					<div class="filter-group__label"><?php esc_html_e( 'Type', 'drolung-branch' ); ?></div>
					<div class="filter-group__btns">
						<button class="filter-btn active" data-type="all"><?php esc_html_e( 'Tous', 'drolung-branch' ); ?></button>
						<?php foreach ( $used_types as $slug => $name ) : ?>
							<button class="filter-btn" data-type="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="inner-section">
	<div class="container">

		<?php if ( $projets->have_posts() ) : ?>

			<div class="four-col" id="projetsGrid">
			<?php
			$i = 0;
			while ( $projets->have_posts() ) :
				$projets->the_post();

				$type_terms   = get_the_terms( get_the_ID(), 'projet_type' );
				$statut_terms = get_the_terms( get_the_ID(), 'projet_statut' );
				$type   = ( $type_terms && ! is_wp_error( $type_terms ) )   ? $type_terms[0]   : null;
				$statut = ( $statut_terms && ! is_wp_error( $statut_terms ) ) ? $statut_terms[0] : null;

				$type_slug   = $type   ? $type->slug   : '';
				$statut_slug = $statut ? $statut->slug : '';

				$budget   = trim( (string) get_post_meta( get_the_ID(), 'budget', true ) );
				$location = trim( (string) get_post_meta( get_the_ID(), 'location', true ) );
				$partner  = trim( (string) get_post_meta( get_the_ID(), 'partenaire', true ) );

				$delay = ( $i % 4 ) * 0.08;
				$style = $delay > 0 ? sprintf( 'transition-delay:%.2fs', $delay ) : '';

				$thumb_url = get_the_post_thumbnail_url( null, 'large' );
				?>

				<div class="card project-card fade-up"
				     data-type="<?php echo esc_attr( $type_slug ); ?>"
				     data-status="<?php echo esc_attr( $statut_slug ); ?>"
				     <?php echo $style ? 'style="' . esc_attr( $style ) . '"' : ''; ?>>

					<div class="card-img" style="position:relative;height:240px;overflow:hidden;background:var(--cream);">
						<?php if ( $thumb_url ) : ?>
							<a href="<?php the_permalink(); ?>">
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
							</a>
						<?php endif; ?>

						<?php if ( $type ) : ?>
							<span class="card-tag project-type" style="position:absolute;top:14px;left:14px;background:rgba(255,255,255,0.95);color:var(--maroon);padding:6px 12px;border-radius:2px;font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;">
								<?php echo esc_html( $type->name ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $statut ) : ?>
							<span class="project-status" style="position:absolute;top:14px;right:14px;padding:6px 12px;border-radius:2px;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;background:rgba(193,125,10,0.15);color:var(--saffron);border:1px solid var(--saffron);">
								<?php echo esc_html( $statut->name ); ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="card-body">
						<div class="card-title">
							<a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
						</div>
						<?php if ( has_excerpt() || get_the_content() ) : ?>
							<p class="card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></p>
						<?php endif; ?>

						<?php if ( $budget || $location ) : ?>
							<div class="project-meta" style="display:flex;justify-content:space-between;align-items:center;margin-top:18px;padding-top:14px;border-top:1px solid var(--border);font-size:12px;color:var(--stone);font-family:var(--font-mono);letter-spacing:0.04em;">
								<span><?php if ( $budget ) : ?><strong style="color:var(--maroon);"><?php echo esc_html( $budget ); ?></strong><?php endif; ?></span>
								<span><?php echo esc_html( $location ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $partner ) : ?>
							<div class="project-meta" style="margin-top:8px;font-size:12px;color:var(--stone);font-family:var(--font-mono);">
								<?php
								/* translators: %s is the partner organisation name. */
								printf( esc_html__( 'Partenaire : %s', 'drolung-branch' ), esc_html( $partner ) );
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
			</div>

			<div class="projects-empty" id="projectsEmpty" style="display:none;text-align:center;padding:60px 20px;color:var(--stone);">
				<?php esc_html_e( 'Aucun projet ne correspond à ces filtres.', 'drolung-branch' ); ?>
			</div>

		<?php else : /* No projet posts at all yet. */ ?>

			<div class="projects-coming-soon fade-up" style="max-width:640px;margin:40px auto;text-align:center;padding:60px 30px;border:1px solid var(--border);background:var(--cream);">
				<div class="section-eyebrow" style="margin-bottom:14px;"><?php esc_html_e( 'Bientôt', 'drolung-branch' ); ?></div>
				<h3 style="font-family:var(--font-serif);font-size:28px;line-height:1.2;margin:0 0 14px;color:var(--maroon);">
					<?php esc_html_e( 'Nos premiers projets seront publiés ici très bientôt.', 'drolung-branch' ); ?>
				</h3>
				<p style="color:var(--stone);margin:0 0 24px;">
					<?php esc_html_e( "L'association finalise actuellement les étapes administratives de sa création. Dès que les premiers projets démarrent sur le terrain, ils apparaîtront sur cette page avec leur budget, leur localisation et l'état d'avancement.", 'drolung-branch' ); ?>
				</p>
				<?php if ( current_user_can( 'edit_posts' ) ) : ?>
					<a class="btn-link" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=projet' ) ); ?>" style="font-family:var(--font-mono);font-size:12px;letter-spacing:0.1em;text-transform:uppercase;color:var(--maroon);">
						<?php esc_html_e( 'Ajouter un projet →', 'drolung-branch' ); ?>
					</a>
				<?php endif; ?>
			</div>

		<?php endif; ?>

	</div>
</section>

<?php if ( $projets->have_posts() ) : ?>
<script>
/* Projet filters — client-side, matches taxonomy slugs in data-* attrs. */
(function () {
	var cards = document.querySelectorAll('.project-card');
	var empty = document.getElementById('projectsEmpty');
	var state = { type: 'all', status: 'all' };

	function apply() {
		var visible = 0;
		cards.forEach(function (c) {
			var okT = state.type   === 'all' || c.dataset.type   === state.type;
			var okS = state.status === 'all' || c.dataset.status === state.status;
			var show = okT && okS;
			c.style.display = show ? '' : 'none';
			if (show) visible++;
		});
		if (empty) empty.style.display = visible === 0 ? '' : 'none';
	}

	document.querySelectorAll('button[data-type]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			document.querySelectorAll('button[data-type]').forEach(function (b) { b.classList.remove('active'); });
			btn.classList.add('active');
			state.type = btn.dataset.type;
			apply();
		});
	});
	document.querySelectorAll('button[data-status]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			document.querySelectorAll('button[data-status]').forEach(function (b) { b.classList.remove('active'); });
			btn.classList.add('active');
			state.status = btn.dataset.status;
			apply();
		});
	});
})();
</script>
<?php endif; ?>

<?php
get_footer();
