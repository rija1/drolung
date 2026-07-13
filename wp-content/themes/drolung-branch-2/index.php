<?php
/**
 * Generic fallback template — pages without a dedicated template and
 * anything else (archives, search…). Simple page-hero + prose content.
 *
 * @package drolung-branch-2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<section class="page-hero">
			<div class="container">
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
			</div>
		</section>

		<section class="entry-content">
			<div class="container">
				<div class="prose"><?php the_content(); ?></div>
			</div>
		</section>

	<?php endwhile; ?>
<?php else : ?>

	<section class="page-hero">
		<div class="container">
			<h1 class="page-hero__title"><?php esc_html_e( 'Rien ici pour le moment', 'drolung-branch-2' ); ?></h1>
			<p class="page-hero__sub"><?php esc_html_e( 'Le contenu demandé est introuvable.', 'drolung-branch-2' ); ?></p>
		</div>
	</section>

<?php endif; ?>

<?php
get_footer();
