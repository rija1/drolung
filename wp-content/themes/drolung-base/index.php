<?php
/**
 * Fallback template. Child themes will typically define their own front-page.php,
 * page.php, archive.php, single.php, etc. This file is the safety net WordPress
 * uses when no more specific template is found.
 *
 * @package drolung-base
 */

get_header();
?>

<section class="inner-section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="section-header">
				<h1 class="section-title">
					<?php
					if ( is_home() && ! is_front_page() ) {
						single_post_title();
					} elseif ( is_archive() ) {
						the_archive_title();
					} else {
						esc_html_e( 'Recent posts', 'drolung-base' );
					}
					?>
				</h1>
			</div>

			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'fade-up' ); ?>>
					<header>
						<h2 class="card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
					</header>
					<div class="card-desc">
						<?php the_excerpt(); ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php the_posts_pagination(); ?>

		<?php else : ?>
			<p><?php esc_html_e( 'Aucun contenu pour l\'instant.', 'drolung-base' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer();
