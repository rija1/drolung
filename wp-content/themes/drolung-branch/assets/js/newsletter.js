/**
 * drolung-branch — newsletter form (home), AJAX submit to MailPoet via
 * `drolung_newsletter_subscribe` (functions.php). Progressive: if this
 * script fails to load, the form still has a real `action`/`method`, it
 * just won't show inline feedback.
 */
(function () {
	'use strict';

	document.addEventListener( 'submit', function ( e ) {
		var form = e.target.closest ? e.target.closest( '.newsletter-form' ) : null;
		if ( ! form || ! window.drolungNewsletter ) {
			return;
		}
		e.preventDefault();

		var input   = form.querySelector( 'input[type="email"]' );
		var email   = input ? input.value.trim() : '';
		var wrapper = form.parentElement;
		var message = wrapper ? wrapper.querySelector( '.newsletter-message' ) : null;
		var i18n    = window.drolungNewsletter.i18n || {};

		if ( ! message ) {
			return;
		}

		if ( ! email ) {
			message.textContent = i18n.invalid || '';
			message.className   = 'newsletter-message is-error';
			return;
		}

		form.classList.add( 'is-loading' );
		message.textContent = '';
		message.className   = 'newsletter-message';

		var body = new URLSearchParams();
		body.set( 'action', 'drolung_newsletter_subscribe' );
		body.set( 'nonce', window.drolungNewsletter.nonce );
		body.set( 'email', email );

		fetch( window.drolungNewsletter.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString(),
		} )
			.then( function ( response ) { return response.json(); } )
			.then( function ( json ) {
				form.classList.remove( 'is-loading' );
				var code = json && json.data && json.data.code;
				if ( json && json.success ) {
					message.textContent = ( 'exists' === code ) ? i18n.exists : i18n.success;
					message.className   = 'newsletter-message is-success';
					if ( input ) {
						input.value = '';
					}
				} else {
					message.textContent = ( 'invalid' === code ) ? i18n.invalid : i18n.error;
					message.className   = 'newsletter-message is-error';
				}
			} )
			.catch( function () {
				form.classList.remove( 'is-loading' );
				message.textContent = i18n.error || '';
				message.className   = 'newsletter-message is-error';
			} );
	} );
} )();
