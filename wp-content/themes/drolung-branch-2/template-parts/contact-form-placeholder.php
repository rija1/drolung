<?php
/**
 * Static placeholder contact form — shown until a real form plugin
 * (CF7 / WPForms) shortcode is placed in the Contact page content.
 *
 * @package drolung-branch-2
 */
?>
<form class="form" action="#" method="post" novalidate>
	<div>
		<label for="c-name"><?php esc_html_e( 'Votre nom', 'drolung-branch-2' ); ?></label>
		<input id="c-name" type="text" name="name" required>
	</div>
	<div>
		<label for="c-email"><?php esc_html_e( 'Votre e-mail', 'drolung-branch-2' ); ?></label>
		<input id="c-email" type="email" name="email" required>
	</div>
	<div>
		<label for="c-msg"><?php esc_html_e( 'Votre message', 'drolung-branch-2' ); ?></label>
		<textarea id="c-msg" name="message" required></textarea>
	</div>
	<div>
		<button type="submit" class="btn btn--accent"><?php esc_html_e( 'Envoyer', 'drolung-branch-2' ); ?></button>
	</div>
</form>
