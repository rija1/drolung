<?php
/**
 * Site header — big centered logo + compact sticky bar that cross-fades in
 * on scroll. The compact nav is cloned from the big nav by base.js so the
 * active state and link list stay in sync without duplicating markup.
 *
 * @package drolung-base
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

<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e( 'Skip to content', 'drolung-base' ); ?></a>

<header class="site-header" id="siteHeader" role="banner">
	<div class="header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-wrap" rel="home">
			<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="logo-img">
			<div class="logo-text-block">
				<span class="logo-name"><?php echo esc_html( drolung_get_brand_name() ); ?></span>
				<?php $tag = drolung_get_brand_tag(); if ( $tag ): ?>
					<span class="logo-tagline"><?php echo esc_html( $tag ); ?></span>
				<?php endif; ?>
			</div>
		</a>

		<nav class="main-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'drolung-base' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'menu_class'     => 'main-nav__list',
				'container'      => false,
				'items_wrap'     => '%3$s', // strip the <ul> so flat <a> rendering matches the mockups
				'fallback_cb'    => 'drolung_nav_fallback',
				'walker'         => new Drolung_Flat_Nav_Walker(),
			] );
			?>
		</nav>

		<div class="header-actions">
			<button class="btn-search" aria-label="<?php esc_attr_e( 'Rechercher', 'drolung-base' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="11" cy="11" r="7"/>
					<path d="m21 21-4.35-4.35"/>
				</svg>
			</button>
			<a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', '#donate' ) ); ?>" class="btn-donate"><?php echo esc_html( apply_filters( 'drolung_donate_label', __( 'Faire un don', 'drolung-base' ) ) ); ?></a>
		</div>
	</div>
</header>

<header class="site-header-compact" id="compactHeader" aria-hidden="true">
	<div class="compact-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="compact-logo" rel="home">
			<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="">
			<div class="compact-logo__text">
				<span class="compact-logo__name"><?php echo esc_html( drolung_get_brand_name() ); ?></span>
				<?php $tag = drolung_get_brand_tag(); if ( $tag ): ?>
					<span class="compact-logo__tag"><?php echo esc_html( $tag ); ?></span>
				<?php endif; ?>
			</div>
		</a>
		<nav class="compact-nav" aria-label="<?php esc_attr_e( 'Navigation compacte', 'drolung-base' ); ?>"></nav>
		<div class="compact-actions">
			<a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', '#donate' ) ); ?>" class="btn-donate"><?php echo esc_html( apply_filters( 'drolung_donate_label', __( 'Faire un don', 'drolung-base' ) ) ); ?></a>
		</div>
	</div>
</header>

<main id="site-content">
