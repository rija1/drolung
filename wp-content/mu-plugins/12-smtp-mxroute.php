<?php
/**
 * Envoi des mails WordPress via SMTP authentifié (MXroute) au lieu de la
 * fonction mail() du serveur Plesk.
 *
 * Pourquoi : les domaines ont SPF/DKIM/DMARC (p=quarantine, alignement strict)
 * côté MXroute. Un mail parti de PHP mail() n'est pas signé DKIM pour le domaine
 * et finit en spam ou rejeté. En passant par MXroute, le mail est signé et aligné.
 *
 * Inactif tant que SMTP_HOST / SMTP_USER / SMTP_PASS ne sont pas définis dans
 * wp-config.php (donc sans effet en local). L'adresse d'expédition est forcée
 * sur SMTP_USER (seule adresse autorisée à envoyer) ; l'expéditeur d'origine
 * est conservé en Reply-To pour que « Répondre » fonctionne.
 *
 * Mis en place par René 404 le 2026-10-07.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SMTP_HOST' ) || ! defined( 'SMTP_USER' ) || ! defined( 'SMTP_PASS' ) ) {
	return;
}

add_action( 'phpmailer_init', 'drolung_smtp_mxroute', 999 );
function drolung_smtp_mxroute( $phpmailer ) {
	$phpmailer->isSMTP();
	$phpmailer->Host       = SMTP_HOST;
	$phpmailer->Port       = defined( 'SMTP_PORT' ) ? (int) SMTP_PORT : 465;
	$phpmailer->SMTPSecure = 465 === $phpmailer->Port ? 'ssl' : 'tls';
	$phpmailer->SMTPAuth   = true;
	$phpmailer->Username   = SMTP_USER;
	$phpmailer->Password   = SMTP_PASS;

	$original_from = $phpmailer->From;
	$from_name     = $phpmailer->FromName;
	if ( ! $from_name || 'WordPress' === $from_name ) {
		$from_name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	}

	$phpmailer->setFrom( SMTP_USER, $from_name, false );
	$phpmailer->Sender = SMTP_USER;

	if ( $original_from && strcasecmp( $original_from, SMTP_USER ) !== 0
		&& ! preg_match( '/^wordpress@/i', $original_from )
		&& empty( $phpmailer->getReplyToAddresses() ) ) {
		$phpmailer->addReplyTo( $original_from );
	}
}
