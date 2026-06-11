<?php
/**
 * single-projet.php — individual project page.
 *
 * Minimal scaffold. Uses the post's title, content, featured image, and
 * taxonomy terms (type, statut) registered by drolung-base.
 *
 * Once Pods/ACF custom fields (budget, location, updates collection, etc.)
 * are defined on the Projet CPT, replace the stub blocks below with
 * get_field() / pods() calls.
 *
 * @package drolung-branch
 */

get_header();

while ( have_posts() ) :
	the_post();

	$type_terms   = get_the_terms( get_the_ID(), 'projet_type' );
	$statut_terms = get_the_terms( get_the_ID(), 'projet_statut' );
	$type   = ( $type_terms && ! is_wp_error( $type_terms ) ) ? $type_terms[0] : null;
	$statut = ( $statut_terms && ! is_wp_error( $statut_terms ) ) ? $statut_terms[0] : null;
	?>

	<div class="page-breadcrumb">
		<div class="container">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
			<span>›</span>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>"><?php esc_html_e( 'Projets', 'drolung-branch' ); ?></a>
			<span>›</span>
			<span><?php the_title(); ?></span>
		</div>
	</div>

	<section class="page-hero" style="<?php echo has_post_thumbnail() ? '--hero-bg: url(' . esc_url( get_the_post_thumbnail_url( null, 'full' ) ) . ');' : ''; ?>">
		<style>.page-hero::before { background-image: var(--hero-bg); }</style>
		<div class="page-hero__line"></div>
		<div class="container">
			<div class="page-hero__eyebrow">
				<?php if ( $type ) : ?>
					<?php echo esc_html( $type->name ); ?>
					<?php if ( $statut ) : ?> · <?php echo esc_html( $statut->name ); ?><?php endif; ?>
				<?php else : ?>
					<?php esc_html_e( 'Projet', 'drolung-branch' ); ?>
				<?php endif; ?>
			</div>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="page-hero__sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="inner-section">
		<div class="container" style="max-width: 820px; margin: 0 auto;">
			<?php the_content(); ?>
		</div>
	</section>

	<?php
endwhile;

get_footer();
