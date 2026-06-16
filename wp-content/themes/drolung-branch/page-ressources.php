<?php
/**
 * Template for the "Ressources" page.
 * Mirrors mockups/mockup-dsf/resources.html and mockups/mockup-dsm/resources.html.
 * Contenu identique sur les deux mockups — placeholder "Bientôt disponible".
 * Editable via le groupe ACF group_drolung_ressources sur cette page.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="inner-section">
	<div class="container">
		<div style="max-width:640px;margin:0 auto;text-align:center;padding:80px 0;">
			<div class="section-eyebrow"><?php echo esc_html( drolung_field( 'ressources_eyebrow', __( 'Bientôt disponible', 'drolung-branch' ) ) ); ?></div>
			<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'ressources_title', __( 'Ressources &amp; <em>publications</em>', 'drolung-branch' ) ) ); ?></h2>
			<p class="section-body" style="margin-bottom:40px"><?php echo wp_kses_post( drolung_field( 'ressources_body', __( 'Cette section rassemblera nos rapports annuels, comptes de résultats, fiches projets et actualités de terrain. Elle sera mise en ligne dès le démarrage de nos premiers projets.', 'drolung-branch' ) ) ); ?></p>
			<div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
				<a href="<?php echo esc_url( drolung_field( 'ressources_cta1_url', home_url( '/projets/' ) ) ); ?>" class="btn-page btn-page--primary"><?php echo esc_html( drolung_field( 'ressources_cta1_label', __( 'Voir nos projets', 'drolung-branch' ) ) ); ?></a>
				<a href="<?php echo esc_url( drolung_field( 'ressources_cta2_url', home_url( '/' ) ) ); ?>" class="btn-page btn-page--saffron"><?php echo esc_html( drolung_field( 'ressources_cta2_label', __( 'Retour à l\'accueil', 'drolung-branch' ) ) ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php get_footer();
