<?php
/**
 * Drolung UK header — top-bar + single sticky-ish nav.
 *
 * The DUK design has ONE header (not the cross-fading big/compact pair used
 * by the French branches), so we override the parent's header.php entirely.
 *
 * @package drolung-duk
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

<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e( 'Skip to content', 'drolung-duk' ); ?></a>

<header>
	<div class="top-bar">
		<div class="top-bar__inner">
			<span class="top-bar__tagline"><?php esc_html_e( 'Scottish Charity · SC054814 · Registered in Scotland', 'drolung-duk' ); ?></span>
			<div class="top-bar__links">
				<div class="lang-sel">
					<a href="#" class="active">EN</a>
					<a href="#">FR</a>
					<a href="#">中</a>
				</div>
			</div>
		</div>
	</div>

	<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'drolung-duk' ); ?>">
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
			<button class="nav-hamburger" aria-label="<?php esc_attr_e( 'Toggle menu', 'drolung-duk' ); ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
			<div class="nav-links">
				<?php
				$donate_url   = apply_filters( 'drolung_donate_url', '#' );
				$donate_label = apply_filters( 'drolung_donate_label', __( 'Donate', 'drolung-duk' ) );

				if ( has_nav_menu( 'primary' ) ) {
					/* Render WP-managed menu when set. The walker is the parent's
					   flat walker so the items render as <a> directly, matching
					   the mockup. */
					wp_nav_menu( [
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'walker'         => class_exists( 'Drolung_Flat_Nav_Walker' ) ? new Drolung_Flat_Nav_Walker() : null,
					] );
					/* Always append the Donate CTA after the menu items */
					echo '<a href="' . esc_url( $donate_url ) . '" class="nav-donate">' . esc_html( $donate_label ) . '</a>';
				} else {
					/* Fallback: hardcoded mockup nav, with the Home link as the
					   only working internal link until the EN pages are created. */
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="active">Home</a>
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Who We Are</a>
					<a href="<?php echo esc_url( home_url( '/our-work/' ) ); ?>">What We Do</a>
					<a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">Articles</a>
					<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">News</a>
					<a href="<?php echo esc_url( home_url( '/how-we-spend/' ) ); ?>">How We Spend</a>
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
					<a href="<?php echo esc_url( $donate_url ); ?>" class="nav-donate"><?php echo esc_html( $donate_label ); ?></a>
					<?php
				}
				?>
			</div>
		</div>
	</nav>
</header>

<main id="site-content">
