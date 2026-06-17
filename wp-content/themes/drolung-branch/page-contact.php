<?php
/**
 * Template for the "Contact" page.
 * Mirrors mockups/mockup-dsf/contact.html and mockups/mockup-dsm/contact.html.
 *
 * Formulaire : Contact Form 7. Le formulaire est seedé sur chaque subsite par
 * mu-plugins/06-drolung-cf7.php (gate drolung_cf7_form_dsf_v1 / _dsm_v1).
 * Il est référencé par slug (post_name = 'contact') — pas par ID — pour être
 * portable entre subsites.
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
		<div class="contact-layout">

			<!-- Colonne gauche : coordonnées -->
			<div class="fade-up">
				<div class="section-eyebrow"><?php echo wp_kses_post( drolung_field( 'contact_eyebrow', __( 'Restons en contact', 'drolung-branch' ) ) ); ?></div>
				<h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'contact_title', __( 'Une question ?<br><em>Écrivez-nous.</em>', 'drolung-branch' ) ) ); ?></h2>
				<p class="section-body" style="margin-bottom:32px"><?php echo wp_kses_post( drolung_field( 'contact_sub', __( 'Nous vous répondons sous 48h.', 'drolung-branch' ) ) ); ?></p>

				<div style="display:flex;flex-direction:column;gap:20px">

					<!-- Email -->
					<div style="display:flex;align-items:center;gap:14px">
						<span style="font-size:22px">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color:var(--saffron);display:inline-block;vertical-align:-0.12em" aria-hidden="true">
								<rect x="3.5" y="5.5" width="17" height="13" rx="1.5"/>
								<path d="M4.5 7.5 12 13l7.5-5.5"/>
							</svg>
						</span>
						<div>
							<div style="font-weight:600;color:var(--charcoal);font-size:14px"><?php esc_html_e( 'Email', 'drolung-branch' ); ?></div>
							<?php
							$contact_email = drolung_field( 'contact_email', 'contact@drolung.org' );
							?>
							<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" style="color:var(--maroon);font-size:14px"><?php echo esc_html( $contact_email ); ?></a>
						</div>
					</div>

					<!-- Réseau Drolung -->
					<div style="display:flex;align-items:center;gap:14px">
						<span style="font-size:22px">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color:var(--saffron);display:inline-block;vertical-align:-0.12em" aria-hidden="true">
								<circle cx="12" cy="12" r="8.5"/>
								<ellipse cx="12" cy="12" rx="4" ry="8.5"/>
								<path d="M3.5 12h17"/>
							</svg>
						</span>
						<div>
							<div style="font-weight:600;color:var(--charcoal);font-size:14px"><?php echo esc_html( drolung_field( 'contact_network_label', __( 'Réseau Drolung', 'drolung-branch' ) ) ); ?></div>
							<?php
							$contact_network_url = drolung_field( 'contact_network_url', 'https://drolung.org' );
							?>
							<a href="<?php echo esc_url( $contact_network_url ); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--maroon);font-size:14px"><?php echo esc_html( drolung_field( 'contact_network_display', 'drolung.org' ) ); ?></a>
						</div>
					</div>

				</div><!-- /.coords -->
			</div><!-- /.col-left -->

			<!-- Colonne droite : formulaire CF7 -->
			<div class="card fade-up" style="transition-delay:0.15s;padding:40px">
				<?php
				$cf7_form = get_page_by_path( 'contact', OBJECT, 'wpcf7_contact_form' );
				if ( $cf7_form && function_exists( 'wpcf7' ) ) {
					echo do_shortcode( '[contact-form-7 id="' . absint( $cf7_form->ID ) . '"]' );
				} else {
					// CF7 pas encore activé / formulaire pas encore seedé.
					// Visiter /wp-admin/ sur ce subsite pour déclencher le seed.
					echo '<p style="color:var(--stone);font-size:14px">'
						. esc_html__( 'Formulaire en cours de configuration. Revenez dans quelques instants.', 'drolung-branch' )
						. '</p>';
				}
				?>
			</div><!-- /.card -->

		</div><!-- /.grid -->
	</div><!-- /.container -->
</section>

<?php get_footer();
