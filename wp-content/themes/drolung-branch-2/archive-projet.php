<?php
/**
 * Archive « Nos projets » — "Terrain" design.
 * Mirrors mockup-dsf-2/projets.html : simple two-column card grid, no
 * client-side filters (design condensé). Projets chargés depuis le site
 * central via drolung_get_projets() (tableaux plats, voir mu-plugin).
 *
 * @package drolung-branch-2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$items = function_exists( 'drolung_get_projets' ) ? (array) drolung_get_projets() : [];
?>

<section class="page-hero">
	<div class="container">
		<span class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'projets_hero_eyebrow', __( 'Nos projets', 'drolung-branch-2' ) ) ); ?></span>
		<h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'projets_hero_title', __( 'Des projets concrets, <strong>une même conviction</strong>.', 'drolung-branch-2' ) ) ); ?></h1>
		<p class="page-hero__sub"><?php echo esc_html( drolung_field( 'projets_hero_sub', __( 'En cours de montage ou en recherche de financement, tous sont portés sur le terrain par notre association sœur. Vos dons les rendent possibles.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<?php if ( $items ) : ?>
			<div class="projects-grid projects-grid--two">
				<?php foreach ( $items as $item ) :
					$type_slugs   = array_keys( $item['types'] );
					$statut_slugs = array_keys( $item['statut'] );
					$type_name    = $type_slugs ? ( $item['types'][ $type_slugs[0] ] ?? '' ) : '';
					$statut_name  = $statut_slugs ? ( $item['statut'][ $statut_slugs[0] ] ?? '' ) : '';
					$thumb_url    = $item['thumbnail']['large'] ?? '';
					$permalink    = home_url( '/projets/' . $item['slug'] . '/' );
					$budget       = $item['meta']['budget'] ?? '';
					?>
					<a href="<?php echo esc_url( $permalink ); ?>" class="project-card">
						<?php if ( $thumb_url ) : ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy">
						<?php endif; ?>
						<div class="project-card__body">
							<div class="project-card__tag">
								<?php echo esc_html( $type_name ); ?>
								<?php if ( $statut_name ) : ?>
									<span class="status">· <?php echo esc_html( $statut_name ); ?></span>
								<?php endif; ?>
							</div>
							<div class="project-card__title"><?php echo esc_html( $item['title'] ); ?></div>
							<?php if ( ! empty( $item['excerpt'] ) ) : ?>
								<p class="project-card__desc">
									<?php
									echo esc_html( wp_trim_words( $item['excerpt'], 24, '…' ) );
									if ( $budget ) {
										/* translators: %s is the project budget. */
										echo ' ' . esc_html( sprintf( __( 'Budget : %s.', 'drolung-branch-2' ), $budget ) );
									}
									?>
								</p>
							<?php endif; ?>
							<span class="link-more"><?php esc_html_e( 'Découvrir →', 'drolung-branch-2' ); ?></span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="prose"><?php esc_html_e( 'Les projets seront bientôt présentés ici.', 'drolung-branch-2' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="pledge">
	<div class="container">
		<h2 class="pledge__title"><?php echo wp_kses_post( drolung_field( 'projets_pledge_title', __( 'Des projets ancrés dans les <strong>besoins réels</strong>.', 'drolung-branch-2' ) ) ); ?></h2>
		<p class="pledge__sub"><?php echo esc_html( drolung_field( 'projets_pledge_sub', __( 'Chaque projet est identifié, suivi et documenté, du premier euro au rapport final.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<?php
drolung_branch2_donate_band(
	__( 'Soutenez un projet.', 'drolung-branch-2' ),
	__( 'Votre don finance directement l\'un de ces projets, sans intermédiaire.', 'drolung-branch-2' )
);

get_footer();
