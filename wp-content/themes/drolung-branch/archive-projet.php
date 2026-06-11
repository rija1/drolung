<?php
/**
 * archive-projet.php — listing of Projet CPT posts for the branch sites.
 *
 * Data sources:
 *  - WP core: title, excerpt/content, featured image.
 *  - Taxonomies: projet_type (eau/ecole/sante/agriculture/environnement)
 *               projet_statut (a-venir/en-cours/termine).
 *  - Post meta: `budget` and `location` are read with get_post_meta() — these
 *    will be populated once you define them as Pods fields on the Projet CPT.
 *    Missing values are silently skipped, so the template works before Pods
 *    is configured.
 *
 * The filters (statut + type) are client-side: each card carries
 * data-status="…" and data-type="…" matching the term slugs, and the JS at
 * the bottom toggles visibility on click.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Fetch all published projets on this branch site. */
$projets = new WP_Query( [
	'post_type'      => 'projet',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order date',
	'order'          => 'DESC',
] );

/* Collect the term slugs actually used on this site so the filter bar only
 * shows buttons that match real content. Falls back to the seeded full list
 * if nothing has been tagged yet. */
$used_types   = [];
$used_statuts = [];
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
	$used_types = [
		'eau'           => __( 'Eau', 'drolung-branch' ),
		'ecole'         => __( 'École', 'drolung-branch' ),
		'sante'         => __( 'Santé', 'drolung-branch' ),
		'agriculture'   => __( 'Agriculture', 'drolung-branch' ),
		'environnement' => __( 'Environnement', 'drolung-branch' ),
	];
}
if ( empty( $used_statuts ) ) {
	$used_statuts = [
		'a-venir'  => __( 'À venir', 'drolung-branch' ),
		'en-cours' => __( 'En cours', 'drolung-branch' ),
		'termine'  => __( 'Terminé', 'drolung-branch' ),
	];
}
?>

<div class="page-breadcrumb">
	<div class="container">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
		<span>›</span>
		<span><?php esc_html_e( 'Projets', 'drolung-branch' ); ?></span>
	</div>
</div>

<section class="page-hero" style="--hero-bg: url('https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=1600&h=700');">
	<style>.page-hero::before { background-image: var(--hero-bg); }</style>
	<div class="page-hero__line"></div>
	<div class="container">
		<div class="page-hero__eyebrow"><?php esc_html_e( 'Projets', 'drolung-branch' ); ?></div>
		<h1 class="page-hero__title"><?php
			/* translators: %s wraps the emphasised word "projets" in <em>. */
			printf( esc_html__( 'Nos %s', 'drolung-branch' ), '<em>' . esc_html__( 'projets', 'drolung-branch' ) . '</em>' );
		?></h1>
		<p class="page-hero__sub"><?php esc_html_e( "Tous les projets pilotés sur le terrain, classés par statut et par type d'intervention.", 'drolung-branch' ); ?></p>
	</div>
</section>

<section class="inner-section inner-section--tint">
	<div class="container">
		<div class="section-header fade-up" style="max-width:780px;margin:0 auto 12px;text-align:center;">
			<div class="section-eyebrow"><?php esc_html_e( 'Notre engagement', 'drolung-branch' ); ?></div>
			<h2 class="section-title"><?php esc_html_e( 'Voir, suivre, comprendre', 'drolung-branch' ); ?></h2>
			<p class="section-body"><?php esc_html_e( "Chaque projet est documenté de bout en bout : ses objectifs, son budget, son état d'avancement et les actualités du terrain. Vous pouvez filtrer par statut ou par domaine d'action.", 'drolung-branch' ); ?></p>
		</div>

		<?php if ( $projets->have_posts() ) : ?>
		<div class="project-filters fade-up">
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
		<?php endif; ?>
	</div>
</section>

<section class="inner-section">
	<div class="container">

		<?php if ( $projets->have_posts() ) : ?>

			<div class="three-col">
			<?php
			$i = 0;
			while ( $projets->have_posts() ) :
				$projets->the_post();

				$type_terms   = get_the_terms( get_the_ID(), 'projet_type' );
				$statut_terms = get_the_terms( get_the_ID(), 'projet_statut' );
				$type   = ( $type_terms && ! is_wp_error( $type_terms ) ) ? $type_terms[0] : null;
				$statut = ( $statut_terms && ! is_wp_error( $statut_terms ) ) ? $statut_terms[0] : null;

				$type_slug   = $type   ? $type->slug   : '';
				$statut_slug = $statut ? $statut->slug : '';

				$budget   = trim( (string) get_post_meta( get_the_ID(), 'budget', true ) );
				$location = trim( (string) get_post_meta( get_the_ID(), 'location', true ) );

				$delay = ( $i % 3 ) * 0.08;
				$style = $delay > 0 ? sprintf( 'transition-delay:%.2fs', $delay ) : '';

				$thumb_url = get_the_post_thumbnail_url( null, 'large' );
				?>

				<a class="card project-card fade-up"
				   href="<?php the_permalink(); ?>"
				   data-type="<?php echo esc_attr( $type_slug ); ?>"
				   data-status="<?php echo esc_attr( $statut_slug ); ?>"
				   <?php echo $style ? 'style="' . esc_attr( $style ) . '"' : ''; ?>>

					<div class="card-img" style="position:relative;height:240px;overflow:hidden;background:var(--cream);">
						<?php if ( $thumb_url ) : ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
						<?php endif; ?>

						<?php if ( $type ) : ?>
							<span class="card-tag project-type" style="position:absolute;top:14px;left:14px;background:rgba(255,255,255,0.95);color:var(--maroon);padding:6px 12px;border-radius:2px;font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;">
								<?php echo esc_html( $type->name ); ?>
							</span>
						<?php endif; ?>

						<?php if ( $statut ) : ?>
							<span class="project-status project-status--<?php echo esc_attr( $statut_slug ); ?>" style="position:absolute;top:14px;right:14px;padding:6px 12px;border-radius:2px;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;">
								<?php echo esc_html( $statut->name ); ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="card-body">
						<div class="card-title"><?php the_title(); ?></div>
						<?php if ( has_excerpt() || get_the_content() ) : ?>
							<p class="card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></p>
						<?php endif; ?>

						<?php if ( $budget || $location ) : ?>
							<div class="project-meta" style="display:flex;justify-content:space-between;align-items:center;margin-top:18px;padding-top:14px;border-top:1px solid var(--border);font-size:12px;color:var(--stone);font-family:var(--font-mono);letter-spacing:0.04em;">
								<span><?php if ( $budget ) : ?><strong style="color:var(--maroon);"><?php echo esc_html( $budget ); ?></strong><?php endif; ?></span>
								<span><?php echo esc_html( $location ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</a>

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
