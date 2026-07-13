<?php
/**
 * Page « Contact » — "Terrain" design.
 * Mirrors mockup-dsf-2/contact.html. The form slot renders the page content
 * (where a CF7/WPForms shortcode lives) when present, otherwise a static
 * placeholder form.
 *
 * @package drolung-branch-2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="page-hero">
	<div class="container">
		<span class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'ct_hero_eyebrow', __( 'Contact', 'drolung-branch-2' ) ) ); ?></span>
		<h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'ct_hero_title', __( 'Écrivez-<strong>nous</strong>.', 'drolung-branch-2' ) ) ); ?></h1>
		<p class="page-hero__sub"><?php echo esc_html( drolung_field( 'ct_hero_sub', __( 'Une question, une envie de s\'impliquer, une idée de partenariat — nous vous répondons sous 48 h.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="contact-grid">
			<div class="contact-info">
				<div class="contact-info__item">
					<div class="contact-info__label"><?php esc_html_e( 'E-mail', 'drolung-branch-2' ); ?></div>
					<div class="contact-info__value">
						<?php $email = drolung_field( 'contact_email', 'contact@drolung.org' ); ?>
						<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
					</div>
				</div>
				<div class="contact-info__item">
					<div class="contact-info__label"><?php esc_html_e( 'Association', 'drolung-branch-2' ); ?></div>
					<div class="contact-info__value"><?php echo wp_kses_post( drolung_field( 'contact_asso', get_bloginfo( 'name' ) . '<br>' . __( 'Association loi 1901, entièrement bénévole', 'drolung-branch-2' ) ) ); ?></div>
				</div>
				<div class="contact-info__item">
					<div class="contact-info__label"><?php esc_html_e( 'Délai de réponse', 'drolung-branch-2' ); ?></div>
					<div class="contact-info__value"><?php echo esc_html( drolung_field( 'contact_delai', __( 'Sous 48 h', 'drolung-branch-2' ) ) ); ?></div>
				</div>
			</div>
			<div class="contact-form-slot">
				<?php
				/* If the page has content (e.g. a CF7 shortcode), render it. */
				if ( have_posts() ) {
					the_post();
					$content = trim( get_the_content() );
					if ( $content ) {
						echo '<div class="form">';
						the_content();
						echo '</div>';
					} else {
						get_template_part( 'template-parts/contact-form-placeholder' );
					}
				} else {
					get_template_part( 'template-parts/contact-form-placeholder' );
				}
				?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
