<?php
/**
 * Drolung Branch header — single sticky nav with inline language switcher.
 *
 * Replaces the parent (drolung-base) header which uses a big centered logo +
 * cross-fading compact bar.
 *
 * Filterable hooks:
 *   drolung_topbar_langs    — array of ['code' => 'XX', 'url' => '#', 'active' => bool]
 *   drolung_donate_url      — donate / s'engager URL
 *   drolung_donate_label    — CTA label (default: 'Faire un don')
 *
 * @package drolung-branch
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e( 'Aller au contenu', 'drolung-branch' ); ?></a>

<header>
	<?php
	$topbar_langs = apply_filters( 'drolung_topbar_langs', [
		[ 'code' => 'FR', 'name' => 'Français', 'url' => '#', 'active' => true  ],
		[ 'code' => 'EN', 'name' => 'English',  'url' => '#', 'active' => false ],
	] );
	?>

	<nav class="site-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'drolung-branch' ); ?>">
		<div class="site-nav__inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-wrap" rel="home">
				<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="height:48px;width:auto;">
				<div class="logo-text-block">
					<span class="logo-name"><?php echo esc_html( drolung_get_brand_name() ); ?></span>
					<?php $tag = drolung_get_brand_tag(); if ( $tag ) : ?>
						<span class="logo-sub"><?php echo esc_html( $tag ); ?></span>
					<?php endif; ?>
				</div>
			</a>
			<button class="nav-hamburger" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'drolung-branch' ); ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
			<div class="nav-links">
				<?php
				$donate_url    = apply_filters( 'drolung_donate_url', function_exists( 'drolung_lang_url' ) ? drolung_lang_url( 's-engager' ) : home_url( '/s-engager/' ) );
				$donate_label_default = function_exists( 'pll__' ) ? pll__( 'Faire un don' ) : __( 'Faire un don', 'drolung-branch' );
				$donate_label  = apply_filters( 'drolung_donate_label', $donate_label_default );

				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( [
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'walker'         => class_exists( 'Drolung_Flat_Nav_Walker' ) ? new Drolung_Flat_Nav_Walker() : null,
					] );
				} else {
					?>
					<?php
					$_current_url = untrailingslashit( home_url( add_query_arg( [] ) ) );
					$_fb_links    = [
						__( 'Accueil', 'drolung-branch' )              => home_url( '/' ),
						__( 'À propos', 'drolung-branch' )             => drolung_lang_url( 'a-propos' ),
						__( 'Notre action', 'drolung-branch' )         => drolung_lang_url( 'notre-action' ),
						__( 'Où nous intervenons', 'drolung-branch' )  => drolung_lang_url( 'ou-nous-intervenons' ),
						__( 'Ressources', 'drolung-branch' )           => drolung_lang_url( 'ressources' ),
						__( 'Contact', 'drolung-branch' )              => drolung_lang_url( 'contact' ),
					];
					foreach ( $_fb_links as $_label => $_url ) {
						$_active = untrailingslashit( $_url ) === $_current_url;
						echo '<a href="' . esc_url( $_url ) . '"' . ( $_active ? ' class="active" aria-current="page"' : '' ) . '>' . esc_html( $_label ) . '</a>';
					}
					unset( $_current_url, $_fb_links, $_label, $_url, $_active );
					?>
					<?php
				}

				if ( $topbar_langs ) :
					$_current_lang = current( array_filter( $topbar_langs, function ( $l ) { return ! empty( $l['active'] ); } ) );
					$_current_code = $_current_lang ? $_current_lang['code'] : '';
					?>
					<div class="lang-switch">
						<button type="button" class="lang-switch__btn" aria-haspopup="true" aria-expanded="false">
							<svg class="lang-switch__globe" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<circle cx="12" cy="12" r="9"/>
								<path d="M3 12h18"/>
								<path d="M12 3c2.5 2.6 3.8 5.7 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.7-3.8-9s1.3-6.4 3.8-9Z"/>
							</svg>
							<span class="lang-switch__code"><?php echo esc_html( $_current_code ); ?></span>
							<svg class="lang-switch__chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M6 9l6 6 6-6"/>
							</svg>
						</button>
						<ul class="lang-switch__menu">
							<?php foreach ( $topbar_langs as $lang ) :
								$_lang_label = ! empty( $lang['name'] ) ? $lang['name'] : $lang['code'];
								?>
								<li>
									<?php if ( ! empty( $lang['url'] ) ) : ?>
										<a href="<?php echo esc_url( $lang['url'] ); ?>"<?php echo $lang['active'] ? ' class="active" aria-current="true"' : ''; ?>><?php echo esc_html( $_lang_label ); ?></a>
									<?php else : ?>
										<span<?php echo $lang['active'] ? ' class="active"' : ' class="unavailable"'; ?>><?php echo esc_html( $_lang_label ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<a href="<?php echo esc_url( $donate_url ); ?>" class="nav-donate"><?php echo esc_html( $donate_label ); ?></a>
			</div>
		</div>
	</nav>
</header>

<main id="site-content">
