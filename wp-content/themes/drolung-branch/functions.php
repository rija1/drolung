<?php
/**
 * drolung-branch — child theme bootstrap.
 *
 * Shared across DSM, DSF, and any future French branch. Per-site identity
 * (brand name, tagline, donate URL) is read from the Customizer; helpers
 * are defined in the parent (drolung-base/inc/branding.php).
 *
 * Header design: single sticky nav (top-bar + site-nav), matching DUK.
 * The parent's big-logo / compact-scroll header is replaced by branch/header.php.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DROLUNG_BRANCH_VERSION', '0.2.8' );
define( 'DROLUNG_BRANCH_URI', get_stylesheet_directory_uri() );

/**
 * URL correcte, dans la langue courante, d'une page interne identifiée par
 * son slug FRANÇAIS (celui du post original — toujours le même quelle que
 * soit la langue affichée, contrairement au slug qui change par langue :
 * "s-engager" → "get-involved" en anglais, etc.).
 *
 * `home_url( '/' . $slug . '/' )` ne suffit pas : il ne préfixe jamais la
 * langue (`/en/`, `/zh/`) ni ne connaît le bon slug traduit — un lien ainsi
 * construit sur une page anglaise pointe vers une URL sans préfixe, que
 * Polylang détecte au clic comme française par défaut (bug rapporté
 * 2026-07-14 : navigation entière renvoyant vers le FR depuis les pages
 * EN/ZH, cf. journal technique §15). `pll_get_post()` est l'API Polylang
 * documentée pour résoudre l'ID traduit dans la langue courante ; on lit
 * ensuite son vrai permalien via `get_permalink()`, qui gère lui-même le
 * bon slug ET le bon préfixe — pas besoin de connaître le slug par langue.
 *
 * @param string $fr_slug     Slug de la page en français (ex. 'a-propos').
 * @param string $default_url Repli si la page n'existe pas sur ce site.
 */
function drolung_lang_url( $fr_slug, $default_url = '' ) {
	/*
	 * Pas de cache statique ici : sous PHP-FPM, les variables `static`
	 * survivent aux requêtes tant que le worker reste vivant — un premier
	 * appel calculé pour le français resterait alors collé en mémoire et
	 * serait servi à tort sur une requête anglaise suivante traitée par le
	 * même worker (bug rencontré et corrigé pendant cette même session :
	 * les liens "Voir tous les projets" / breadcrumb restaient parfois en
	 * français malgré la page courante en anglais). Le lookup est bon
	 * marché (une requête indexée sur le slug) — pas besoin d'optimiser.
	 */
	$fr_slug = trim( $fr_slug, '/' );

	/*
	 * Archives de CPT réseau (ex. 'projets') : jamais de vraie "page" à
	 * chercher, juste un préfixe de langue courante à ajouter. Traitées
	 * en premier, avant get_page_by_path() — un vieux post 'page' orphelin
	 * portant par coïncidence le même slug (ID 5, "Projets", legacy
	 * d'avant le CPT) a autrement été trouvé à sa place et a fait
	 * ressortir son URL française non traduite, peu importe la langue
	 * courante (bug trouvé et corrigé le 2026-07-14).
	 */
	$archive_slugs = array( 'projets', 'articles' );
	if ( in_array( $fr_slug, $archive_slugs, true ) ) {
		$prefix = '';
		if ( function_exists( 'pll_current_language' ) && function_exists( 'pll_default_language' ) ) {
			$cur = pll_current_language();
			$def = pll_default_language();
			if ( $cur && $cur !== $def ) {
				$prefix = trailingslashit( $cur );
			}
		}
		return $default_url ?: home_url( '/' . $prefix . $fr_slug . '/' );
	}

	$page = get_page_by_path( $fr_slug );
	if ( ! $page ) {
		return $default_url ?: home_url( '/' . $fr_slug . '/' );
	}

	$post_id = $page->ID;
	if ( function_exists( 'pll_get_post' ) ) {
		$translated_id = pll_get_post( $post_id );
		if ( $translated_id ) {
			$post_id = $translated_id;
		}
	}

	return get_permalink( $post_id );
}

/**
 * Enqueue branch-nav.css (header overrides) after base.css,
 * and branch-nav.js (hamburger) in place of base.js.
 */
add_action( 'wp_enqueue_scripts', 'drolung_branch_enqueue_assets', 20 );
function drolung_branch_enqueue_assets() {
	/* Load branch header CSS after base.css so our overrides win. */
	wp_enqueue_style(
		'drolung-branch-nav',
		DROLUNG_BRANCH_URI . '/assets/css/branch-nav.css',
		[ 'drolung-base-css' ],
		DROLUNG_BRANCH_VERSION
	);

	/* Load hamburger / fade-up JS */
	wp_enqueue_script(
		'drolung-branch-nav-js',
		DROLUNG_BRANCH_URI . '/assets/js/branch-nav.js',
		[],
		DROLUNG_BRANCH_VERSION,
		true
	);

	/* Newsletter form (home) — AJAX submit to MailPoet, see below. */
	wp_enqueue_script(
		'drolung-newsletter-js',
		DROLUNG_BRANCH_URI . '/assets/js/newsletter.js',
		[],
		DROLUNG_BRANCH_VERSION,
		true
	);
	wp_localize_script( 'drolung-newsletter-js', 'drolungNewsletter', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'drolung_newsletter_subscribe' ),
		'i18n'    => array(
			'success' => drolung_pll__( 'Merci ! Vérifiez votre boîte mail pour confirmer votre inscription.' ),
			'exists'  => drolung_pll__( 'Cette adresse est déjà inscrite à notre newsletter.' ),
			'invalid' => drolung_pll__( 'Merci de saisir une adresse e-mail valide.' ),
			'error'   => drolung_pll__( 'Une erreur est survenue, merci de réessayer plus tard.' ),
		),
	) );
}

/**
 * Newsletter — inscription MailPoet (section home "Suivez nos avancées").
 * Un contact base par entité (DSF/DSM séparées, cf. CLAUDE.md § Stack
 * decisions) : chaque branche a sa propre liste MailPoet, retrouvée par nom
 * exact ou créée automatiquement au premier appel (find-or-create — pas
 * besoin de configuration manuelle ni de script de synchronisation prod,
 * contrairement aux correctifs Polylang de cette même session). L'ID
 * trouvé/créé est mis en cache dans une option du site pour éviter un
 * aller-retour API MailPoet à chaque requête.
 */
function drolung_mailpoet_list_name_for_branch( $branch ) {
	$names = array(
		'dsf' => 'Drolung Solidarité France — Newsletter',
		'dsm' => 'Drolung Solidarité Madagascar — Newsletter',
	);
	return isset( $names[ $branch ] ) ? $names[ $branch ] : 'Drolung — Newsletter';
}

function drolung_get_or_create_mailpoet_list_id() {
	if ( ! class_exists( '\MailPoet\API\API' ) ) {
		return 0;
	}

	$branch     = function_exists( 'drolung_current_branch' ) ? drolung_current_branch() : 'org';
	$option_key = 'drolung_mailpoet_list_id_' . $branch;
	$cached     = get_option( $option_key );
	if ( $cached ) {
		return (int) $cached;
	}

	$list_name = drolung_mailpoet_list_name_for_branch( $branch );

	try {
		$mailpoet = \MailPoet\API\API::MP( 'v1' );
		foreach ( $mailpoet->getLists() as $list ) {
			if ( isset( $list['name'] ) && $list['name'] === $list_name ) {
				update_option( $option_key, $list['id'] );
				return (int) $list['id'];
			}
		}
		$created = $mailpoet->addList( array( 'name' => $list_name ) );
		if ( ! empty( $created['id'] ) ) {
			update_option( $option_key, $created['id'] );
			return (int) $created['id'];
		}
	} catch ( \Exception $e ) {
		return 0;
	}

	return 0;
}

add_action( 'wp_ajax_drolung_newsletter_subscribe', 'drolung_ajax_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_drolung_newsletter_subscribe', 'drolung_ajax_newsletter_subscribe' );
function drolung_ajax_newsletter_subscribe() {
	check_ajax_referer( 'drolung_newsletter_subscribe', 'nonce' );

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! $email || ! is_email( $email ) ) {
		wp_send_json_error( array( 'code' => 'invalid' ) );
	}

	if ( ! class_exists( '\MailPoet\API\API' ) ) {
		wp_send_json_error( array( 'code' => 'error' ) );
	}

	$list_id = drolung_get_or_create_mailpoet_list_id();

	try {
		$mailpoet = \MailPoet\API\API::MP( 'v1' );
		$mailpoet->addSubscriber(
			array( 'email' => $email ),
			$list_id ? array( $list_id ) : array()
		);
		wp_send_json_success( array( 'code' => 'success' ) );
	} catch ( \MailPoet\API\MP\v1\APIException $e ) {
		/*
		 * CONFIRMATION_FAILED_TO_SEND (10) et WELCOME_FAILED_TO_SEND (17) sont
		 * levées APRÈS que l'abonné a déjà été enregistré dans la liste (voir
		 * Subscribers::_sendConfirmationEmail() / _scheduleWelcomeNotification()
		 * dans le plugin) — seul l'envoi de l'email a échoué (ex. méthode
		 * d'envoi MailPoet non configurée). Traiter comme un succès plutôt que
		 * de dire à la personne que son inscription a échoué alors qu'elle a
		 * bien été prise en compte.
		 */
		$code = $e->getCode();
		if ( \MailPoet\API\MP\v1\APIException::SUBSCRIBER_EXISTS === $code ) {
			wp_send_json_success( array( 'code' => 'exists' ) );
		}
		if ( in_array( $code, array( \MailPoet\API\MP\v1\APIException::CONFIRMATION_FAILED_TO_SEND, \MailPoet\API\MP\v1\APIException::WELCOME_FAILED_TO_SEND ), true ) ) {
			wp_send_json_success( array( 'code' => 'success' ) );
		}
		wp_send_json_error( array( 'code' => 'error' ) );
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'code' => 'error' ) );
	}
}

/**
 * Dequeue parent base.js — its compact-header scroll logic conflicts with
 * the new single-nav design and is no longer needed.
 */
add_action( 'wp_enqueue_scripts', 'drolung_branch_dequeue_parent_js', 25 );
function drolung_branch_dequeue_parent_js() {
	wp_dequeue_script( 'drolung-base-js' );
	wp_deregister_script( 'drolung-base-js' );
}

/**
 * Donate link — points to the s'engager page on this subsite.
 */
add_filter( 'drolung_donate_url', function () {
	return drolung_lang_url( 's-engager' );
} );

/**
 * Language switcher — uses Polylang when configured, otherwise shows nothing.
 *
 * pll_the_languages( raw=1 ) returns one entry per configured language with:
 *   'slug', 'url', 'current_lang' (bool), 'no_translation' (bool).
 * When there is only one language configured (no translated content yet) this
 * returns a single-item array, so the switcher shows only the active language
 * with no dead links.
 */
add_filter( 'drolung_topbar_langs', 'drolung_branch_pll_lang_switcher', 5 );
function drolung_branch_pll_lang_switcher( $langs ) {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return $langs;
	}

	$pll_list = pll_the_languages( array(
		'raw'              => 1,
		'hide_current'     => 0,
		'display_names_as' => 'name', // native name (Français / English / 中文) for the dropdown list.
	) );

	if ( empty( $pll_list ) ) {
		return $langs;
	}

	/*
	 * Code court affiché dans le bouton une fois une langue sélectionnée :
	 * FR / EN pour le latin, mais le chinois s'affiche en "中文" plutôt
	 * qu'en "ZH" (demande explicite — plus lisible/naturel pour cette langue).
	 * Même chose pour le nom complet du menu déroulant : Polylang renvoie
	 * "中文 (中国)" (nom + pays) pour le chinois, on force juste "中文".
	 */
	$short_codes  = array( 'zh' => '中文' );
	$display_name = array( 'zh' => '中文' );

	$out = array();
	foreach ( $pll_list as $lang ) {
		$code = isset( $short_codes[ $lang['slug'] ] ) ? $short_codes[ $lang['slug'] ] : strtoupper( $lang['slug'] );
		$name = isset( $display_name[ $lang['slug'] ] ) ? $display_name[ $lang['slug'] ] : $lang['name'];

		/* Skip entries that have no translation and are not the current page language. */
		if ( ! empty( $lang['no_translation'] ) && empty( $lang['current_lang'] ) ) {
			$out[] = array(
				'code'   => $code,
				'name'   => $name,
				'url'    => '',   // no target — rendered as plain text in the header
				'active' => false,
			);
		} else {
			$out[] = array(
				'code'   => $code,
				'name'   => $name,
				'url'    => esc_url( $lang['url'] ),
				'active' => ! empty( $lang['current_lang'] ),
			);
		}
	}
	return $out;
}

/**
 * Footer content — brand column + 3 link columns, matching the
 * `.footer-top` grid already styled in base.css (2fr/1fr/1fr/1fr).
 *
 * Social links and contact email are pulled from the S'engager and
 * Contact pages' own ACF fields (single source of truth — no
 * duplicate data entry for the footer).
 */
add_action( 'drolung_footer_content', 'drolung_branch_footer_content' );
function drolung_branch_footer_content() {
	$engager_id = drolung_acf_page_id_by_slug( 's-engager' );

	$facebook  = drolung_field( 'engager_facebook_url',  '#', $engager_id );
	$linkedin  = drolung_field( 'engager_linkedin_url',  '#', $engager_id );
	$instagram = drolung_field( 'engager_instagram_url', '#', $engager_id );

	?>
	<div class="footer-top">

		<div class="footer-brand">
			<div class="footer-brand__logo-wrap">
				<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="" style="height:32px;width:auto;">
				<span class="footer-brand__name"><?php echo esc_html( drolung_get_brand_name() ); ?></span>
			</div>
			<p><?php echo esc_html( drolung_pll__( 'Une association de proximité qui soutient des projets concrets en éducation, santé et environnement, en partenariat avec les communautés locales.' ) ); ?></p>
			<div class="footer-social">
				<a href="<?php echo esc_url( $facebook ); ?>" class="social-btn" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h-2a5 5 0 0 0-5 5v2H6v4h2v7h4v-7h3l1-4h-4V8a1 1 0 0 1 1-1h3z"/></svg>
				</a>
				<a href="<?php echo esc_url( $linkedin ); ?>" class="social-btn" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="9" width="4" height="12"/><circle cx="5" cy="4" r="2"/><path d="M11 21v-7a3 3 0 0 1 6 0v7"/><path d="M11 21v-8"/><path d="M17 21v-7"/></svg>
				</a>
				<a href="<?php echo esc_url( $instagram ); ?>" class="social-btn" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
				</a>
			</div>
		</div>

		<div class="footer-col">
			<div class="footer-col__title"><?php echo esc_html( drolung_pll__( 'Navigation' ) ); ?></div>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( drolung_pll__( 'Accueil' ) ); ?></a></li>
				<li><a href="<?php echo esc_url( drolung_lang_url( 'a-propos' ) ); ?>"><?php echo esc_html( drolung_pll__( 'À propos' ) ); ?></a></li>
			</ul>
		</div>

		<div class="footer-col">
			<div class="footer-col__title"><?php echo esc_html( drolung_pll__( "S'engager" ) ); ?></div>
			<ul>
				<li><a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) ) ); ?>"><?php echo esc_html( drolung_pll__( 'Faire un don' ) ); ?></a></li>
				<li><a href="<?php echo esc_url( drolung_lang_url( 'projets' ) ); ?>"><?php echo esc_html( drolung_pll__( 'Nos projets' ) ); ?></a></li>
			</ul>
		</div>

		<div class="footer-col">
			<div class="footer-col__title"><?php echo esc_html( drolung_pll__( 'Contact' ) ); ?></div>
			<ul>
				<li><a href="<?php echo esc_url( drolung_lang_url( 'contact' ) ); ?>"><?php echo esc_html( drolung_pll__( 'Nous contacter' ) ); ?></a></li>
			</ul>
		</div>

	</div>
	<?php
}

/**
 * Enregistre les chaînes Polylang de la page d'archive `/projets/`, dont
 * le contenu vient d'une page d'options réseau sans post associé (voir
 * `drolung_get_network_option_translated()`, helpers.php).
 *
 * `pll_register_string()` n'agit que quand `PLL()` est une instance
 * `PLL_Admin_Base` — jamais le cas sur une requête front-end pure — donc
 * cet enregistrement doit tourner sur un hook qui s'exécute aussi côté
 * admin (`init`, ici), pas depuis le template `archive-projet.php`
 * lui-même. Ré-enregistré à chaque `init` (idempotent) pour suivre toute
 * modification faite via ACF → Réglages réseau côté central.
 *
 * Traductions à saisir : wp-admin de CHAQUE branche (dsf/dsm.drolung.local)
 * → Langues → Traduction des chaînes → groupe « Drolung — Réglages réseau ».
 */
add_action( 'init', 'drolung_register_projets_archive_strings' );
function drolung_register_projets_archive_strings() {
	if ( ! function_exists( 'pll_register_string' ) || ! function_exists( 'drolung_get_network_option' ) ) {
		return;
	}

	$fields = array(
		'projets_hero_eyebrow'  => __( 'Nos projets', 'drolung-branch' ),
		'projets_hero_title'    => __( 'Quatre projets, <em>une même conviction</em>', 'drolung-branch' ),
		'projets_hero_sub'      => __( 'Les projets que Drolung Solidarité finance et accompagne, portés sur le terrain par notre association sœur.', 'drolung-branch' ),
		'projets_intro_eyebrow' => __( 'Notre soutien', 'drolung-branch' ),
		'projets_intro_title'   => __( 'Nos projets <em>en cours de montage</em>', 'drolung-branch' ),
		'projets_intro_body'    => __( 'Ces projets sont en cours de montage ou en recherche de financement. Tous sont portés sur le terrain par nos associations sœurs. Vos dons les rendent possibles, directement et sans intermédiaire.', 'drolung-branch' ),
	);

	foreach ( $fields as $key => $default ) {
		$value = drolung_get_network_option( $key, $default );
		if ( is_string( $value ) && '' !== $value ) {
			pll_register_string( $key, $value, 'Drolung — Réglages réseau', true );
		}
	}
}

/**
 * Traduit une chaîne d'interface fixe via Polylang si dispo, sinon repli sur
 * le gettext classique du thème (jamais traduit tant que Loco Translate
 * n'est pas installé — voir CLAUDE.md § i18n stack). À utiliser à la place
 * de `esc_html_e()` pour tout libellé enregistré dans
 * `drolung_register_engager_strings()` ci-dessous.
 */
function drolung_pll__( $text, $domain = 'drolung-branch' ) {
	return function_exists( 'pll__' ) ? pll__( $text ) : __( $text, $domain );
}

/**
 * Enregistre les libellés d'interface fixes (boutons, liens, titres de
 * colonnes du footer) qui ne sont rattachés à aucun champ ACF par page,
 * donc pas de traduction "par page" possible. Ces chaînes utilisaient
 * `esc_html_e()` (gettext classique) — `pll_register_string()` + `pll__()`
 * (via `drolung_pll__()`) permet de les traduire dès maintenant via
 * Polylang, sans attendre Loco Translate.
 *
 * Traductions à saisir : wp-admin de CHAQUE branche → Langues → Traduction
 * des chaînes → groupe « Drolung — Interface ».
 */
add_action( 'init', 'drolung_register_engager_strings' );
function drolung_register_engager_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	$strings = array(
		'interface_see_all_projects' => 'Voir tous les projets →',
		'interface_footer_tagline'   => 'Une association de proximité qui soutient des projets concrets en éducation, santé et environnement, en partenariat avec les communautés locales.',
		'interface_footer_nav_title' => 'Navigation',
		'interface_footer_home'      => 'Accueil',
		'interface_footer_about'     => 'À propos',
		'interface_footer_engager_title' => "S'engager",
		'interface_footer_donate'    => 'Faire un don',
		'interface_footer_contact_title' => 'Contact',
		'interface_footer_contact_us'    => 'Nous contacter',
		'interface_newsletter_success'   => 'Merci ! Vérifiez votre boîte mail pour confirmer votre inscription.',
		'interface_newsletter_exists'    => 'Cette adresse est déjà inscrite à notre newsletter.',
		'interface_newsletter_invalid'   => 'Merci de saisir une adresse e-mail valide.',
		'interface_newsletter_error'     => 'Une erreur est survenue, merci de réessayer plus tard.',
		'interface_asc_loading_text'     => 'Chargement du formulaire de don…',
	);
	foreach ( $strings as $name => $string ) {
		pll_register_string( $name, $string, 'Drolung — Interface' );
	}
}
