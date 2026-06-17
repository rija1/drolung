<?php
/**
 * CF7 — network-activation + formulaires de contact DSF/DSM.
 *
 * Deux responsabilités :
 *  1. Network-activer Contact Form 7 (gate : drolung_cf7_activated_v1).
 *  2. Seeder un formulaire "Contact" sur DSF et DSM avec les bonnes options
 *     de select propres à chaque entité (gate par subsite).
 *
 * @package drolung
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -----------------------------------------------------------------------
 * 1. Network-activation CF7 (unique, sur init)
 * --------------------------------------------------------------------- */
add_action( 'init', 'drolung_cf7_network_activate' );

function drolung_cf7_network_activate() {
	if ( ! is_multisite() ) {
		return;
	}
	if ( get_site_option( 'drolung_cf7_activated_v1' ) ) {
		return;
	}

	$plugin = 'contact-form-7/wp-contact-form-7.php';
	$active = get_site_option( 'active_sitewide_plugins', array() );

	if ( ! isset( $active[ $plugin ] ) ) {
		$active[ $plugin ] = time();
		update_site_option( 'active_sitewide_plugins', $active );
	}

	update_site_option( 'drolung_cf7_activated_v1', current_time( 'mysql' ) );
}

/* -----------------------------------------------------------------------
 * 2. Seeder formulaires DSF / DSM (admin_init — CF7 est déjà chargé)
 * --------------------------------------------------------------------- */
// init (pas admin_init) pour que le seed se déclenche sur la première requête front-end.
// La gate get_option() empêche toute exécution ultérieure.
add_action( 'init', 'drolung_cf7_seed_forms', 20 );

function drolung_cf7_seed_forms() {
	if ( ! function_exists( 'wpcf7' ) ) {
		return; // CF7 pas encore actif sur ce subsite
	}

	$blog_id = get_current_blog_id();
	$dsf_id  = get_blog_id_from_url( 'dsf.drolung.local' );
	$dsm_id  = get_blog_id_from_url( 'dsm.drolung.local' );

	if ( (int) $blog_id === (int) $dsf_id ) {
		drolung_cf7_create_form(
			'drolung_cf7_form_dsf_v1',
			'contact@solidarite.drolung.fr',
			drolung_cf7_form_body( 'dsf' ),
			drolung_cf7_mail_config( 'DSF', 'contact@solidarite.drolung.fr' )
		);
	} elseif ( (int) $blog_id === (int) $dsm_id ) {
		drolung_cf7_create_form(
			'drolung_cf7_form_dsm_v1',
			'contact@solidarite.drolung.mg',
			drolung_cf7_form_body( 'dsm' ),
			drolung_cf7_mail_config( 'DSM', 'contact@solidarite.drolung.mg' )
		);
	}
}

/**
 * Crée le post wpcf7_contact_form si la gate n'est pas déjà posée.
 *
 * @param string $gate     Clé d'option WP pour la gate one-shot.
 * @param string $email    Adresse e-mail destinataire.
 * @param string $form     Corps du formulaire CF7.
 * @param array  $mail     Config mail (tableau CF7).
 */
function drolung_cf7_create_form( $gate, $email, $form, $mail ) {
	if ( get_option( $gate ) ) {
		return;
	}

	// Vérifie qu'un formulaire "Contact" n'existe pas déjà.
	$existing = get_page_by_path( 'contact', OBJECT, 'wpcf7_contact_form' );
	if ( $existing ) {
		update_option( $gate, $existing->ID );
		return;
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'wpcf7_contact_form',
		'post_title'   => 'Contact',
		'post_name'    => 'contact',
		'post_status'  => 'publish',
		'post_content' => $form,
	) );

	if ( ! $post_id || is_wp_error( $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_form', $form );
	update_post_meta( $post_id, '_mail', $mail );
	update_post_meta( $post_id, '_mail_2', drolung_cf7_mail2_defaults() );
	update_post_meta( $post_id, '_messages', drolung_cf7_messages_fr() );
	update_post_meta( $post_id, '_additional_settings', '' );

	update_option( $gate, $post_id );
}

/**
 * Corps du formulaire CF7, différencié par entité.
 *
 * Les classes btn-page/btn-page--primary sont définies dans base.css.
 * CF7 utilise la syntaxe class: pour ajouter des classes sur les champs.
 *
 * @param string $site 'dsf' ou 'dsm'.
 * @return string
 */
function drolung_cf7_form_body( $site ) {
	if ( 'dsm' === $site ) {
		$select_options = '"Partenariat local" "Collaboration terrain" "Presse / média" "Autre"';
	} else {
		// DSF par défaut
		$select_options = '"Faire un don" "Partenariat / mécénat" "Presse / média" "Bénévolat" "Autre"';
	}

	return '<div class="ct-form-row ct-form-row--two">
[text* prenom class:ct-input placeholder "Prénom"]
[text* nom class:ct-input placeholder "Nom"]
</div>
[email* email class:ct-input placeholder "Votre adresse e-mail"]
[select* objet class:ct-select ' . $select_options . ']
[textarea* message class:ct-textarea rows:5 placeholder "Votre message"]
[submit class:btn-page class:btn-page--primary "Envoyer →"]';
}

/**
 * Configuration mail standard CF7 pour DSF/DSM.
 *
 * @param string $label  Libellé de l'entité (DSF ou DSM).
 * @param string $email  Adresse destinataire.
 * @return array
 */
function drolung_cf7_mail_config( $label, $email ) {
	return array(
		'active'             => true,
		'recipient'          => $email,
		'sender'             => 'Drolung <wordpress@drolung.local>',
		'subject'            => '[' . $label . '] [objet] — message de [prenom] [nom]',
		'body'               => "De : [prenom] [nom]\nEmail : [email]\nObjet : [objet]\n\n[message]",
		'additional_headers' => 'Reply-To: [email]',
		'attachments'        => '',
		'use_html'           => false,
		'exclude_blank'      => false,
	);
}

/** Config mail_2 désactivée (valeurs CF7 par défaut). */
function drolung_cf7_mail2_defaults() {
	return array(
		'active'             => false,
		'recipient'          => '',
		'sender'             => '',
		'subject'            => '',
		'body'               => '',
		'additional_headers' => '',
		'attachments'        => '',
		'use_html'           => false,
		'exclude_blank'      => false,
	);
}

/** Messages de validation en français. */
function drolung_cf7_messages_fr() {
	return array(
		'mail_sent_ok'             => 'Merci ! Votre message a bien été envoyé.',
		'mail_sent_ng'             => 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer.',
		'validation_error'         => 'Veuillez corriger les champs indiqués.',
		'spam'                     => 'Ce message semble être du spam.',
		'accept_terms'             => 'Vous devez accepter les conditions.',
		'invalid_required'         => 'Ce champ est obligatoire.',
		'invalid_too_long'         => 'Ce champ est trop long.',
		'invalid_too_short'        => 'Ce champ est trop court.',
		'upload_failed'            => 'Le fichier n\'a pas pu être envoyé.',
		'upload_file_type_invalid' => 'Ce type de fichier n\'est pas autorisé.',
		'upload_file_too_large'    => 'Ce fichier est trop volumineux.',
		'upload_failed_php_error'  => 'Erreur PHP lors de l\'upload.',
		'invalid_email'            => 'Adresse e-mail invalide.',
		'invalid_url'              => 'URL invalide.',
		'invalid_date'             => 'Date invalide.',
		'date_too_early'           => 'Date trop ancienne.',
		'date_too_late'            => 'Date trop récente.',
		'invalid_number'           => 'Nombre invalide.',
		'number_too_small'         => 'Nombre trop petit.',
		'number_too_large'         => 'Nombre trop grand.',
		'quiz_answer_not_correct'  => 'Réponse incorrecte.',
		'invalid_captcha'          => 'CAPTCHA incorrect.',
		'invalid_consent'          => 'Consentement requis.',
	);
}
