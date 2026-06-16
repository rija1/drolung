<?php
/**
 * Template for the "Contact" page.
 * Mirrors mockups/mockup-dsf/contact.html and mockups/mockup-dsm/contact.html.
 * Both DSF and DSM share this template; per-site content (email, objet options)
 * is editable via the ACF group group_drolung_contact on each site.
 *
 * Form handling: the mockup uses a plain HTML <form action="#">
 * which has no server-side handler. Replace with Contact Form 7 shortcode
 * once the plugin is installed. Until then the shortcode comment is kept.
 * TODO: install Contact Form 7 and replace the comment block below.
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
		<div style="display:grid;grid-template-columns:1fr 520px;gap:72px;align-items:start;max-width:1000px;margin:0 auto;">

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

			<!-- Colonne droite : formulaire -->
			<div class="card fade-up" style="transition-delay:0.15s;padding:40px">
				<?php
				/*
				 * TODO: Contact Form 7 — installer le plugin et remplacer ce bloc
				 * par le shortcode du formulaire créé dans WP-admin.
				 * Exemple : echo do_shortcode( '[contact-form-7 id="XXX" title="Contact"]' );
				 *
				 * En attendant, formulaire HTML statique non fonctionnel.
				 */
				?>
				<!-- TODO: remplacer par shortcode CF7 une fois le plugin installé -->
				<form action="#" method="post" novalidate style="display:flex;flex-direction:column;gap:20px">
					<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
						<div>
							<label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--stone);margin-bottom:6px" for="ct-prenom"><?php esc_html_e( 'Prénom', 'drolung-branch' ); ?></label>
							<input id="ct-prenom" type="text" name="prenom" required style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:2px;font-family:inherit;font-size:14px;outline:none;transition:border-color 0.2s;box-sizing:border-box" placeholder="">
						</div>
						<div>
							<label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--stone);margin-bottom:6px" for="ct-nom"><?php esc_html_e( 'Nom', 'drolung-branch' ); ?></label>
							<input id="ct-nom" type="text" name="nom" required style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:2px;font-family:inherit;font-size:14px;outline:none;transition:border-color 0.2s;box-sizing:border-box" placeholder="">
						</div>
					</div>
					<div>
						<label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--stone);margin-bottom:6px" for="ct-email"><?php esc_html_e( 'Votre adresse e-mail', 'drolung-branch' ); ?></label>
						<input id="ct-email" type="email" name="email" required style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:2px;font-family:inherit;font-size:14px;outline:none;transition:border-color 0.2s;box-sizing:border-box" placeholder="">
					</div>
					<div>
						<label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--stone);margin-bottom:6px" for="ct-objet"><?php esc_html_e( 'Objet', 'drolung-branch' ); ?></label>
						<?php
						/*
						 * Les options du menu déroulant diffèrent entre DSF et DSM.
						 * DSF : don, partenariat/mécénat, presse, bénévolat, autre.
						 * DSM : partenariat local, collaboration terrain, presse, autre.
						 * Éditable via ACF (contact_objet_options — texte JSON ou textarea séparée par sauts de ligne).
						 * Fallback : options génériques communes aux deux sites.
						 */
						$default_options = [
							'partenariat' => __( 'Partenariat / mécénat', 'drolung-branch' ),
							'presse'      => __( 'Presse / média', 'drolung-branch' ),
							'benevole'    => __( 'Bénévolat / collaboration', 'drolung-branch' ),
							'autre'       => __( 'Autre', 'drolung-branch' ),
						];
						?>
						<select id="ct-objet" name="objet" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:2px;font-family:inherit;font-size:14px;outline:none;background:#fff;box-sizing:border-box">
							<?php foreach ( $default_options as $val => $label ) : ?>
								<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div>
						<label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:var(--stone);margin-bottom:6px" for="ct-message"><?php esc_html_e( 'Message', 'drolung-branch' ); ?></label>
						<textarea id="ct-message" name="message" rows="5" required style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:2px;font-family:inherit;font-size:14px;outline:none;resize:vertical;box-sizing:border-box"></textarea>
					</div>
					<button type="submit" class="btn-page btn-page--primary" style="align-self:flex-start"><?php esc_html_e( 'Envoyer', 'drolung-branch' ); ?></button>
					<p style="font-size:12px;color:var(--stone);margin:0"></p>
				</form>
			</div><!-- /.card -->

		</div><!-- /.grid -->
	</div><!-- /.container -->
</section>

<?php get_footer();
