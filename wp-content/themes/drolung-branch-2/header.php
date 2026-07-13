<?php
/**
 * drolung-branch-2 header — single white sticky nav, "Terrain" design.
 *
 * No top-bar: the language switcher (drolung_topbar_langs filter) is folded
 * into the nav when more than one language is configured.
 *
 * @package drolung-branch-2
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

<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e( 'Aller au contenu', 'drolung-branch-2' ); ?></a>

<header class="site-header">
	<div class="site-header__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-wrap" rel="home">
			<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<span class="logo-name">
				<?php echo esc_html( drolung_get_brand_name() ); ?>
				<?php $tag = drolung_get_brand_tag(); if ( $tag ) : ?>
					<br><span><?php echo esc_html( $tag ); ?></span>
				<?php endif; ?>
			</span>
		</a>
		<button class="nav-hamburger" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'drolung-branch-2' ); ?>" aria-expanded="false">
			<span></span><span></span><span></span>
		</button>
		<nav class="nav-links" aria-label="<?php esc_attr_e( 'Navigation principale', 'drolung-branch-2' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( [
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'walker'         => class_exists( 'Drolung_Flat_Nav_Walker' ) ? new Drolung_Flat_Nav_Walker() : null,
				] );
			} else {
				$_current_url = untrailingslashit( home_url( add_query_arg( [] ) ) );
				$_fb_links    = [
					__( 'Accueil', 'drolung-branch-2' )      => home_url( '/' ),
					__( 'Notre action', 'drolung-branch-2' ) => home_url( '/notre-action/' ),
					__( 'Nos projets', 'drolung-branch-2' )  => home_url( '/projets/' ),
					__( 'L\'association', 'drolung-branch-2' ) => home_url( '/a-propos/' ),
					__( 'Contact', 'drolung-branch-2' )      => home_url( '/contact/' ),
				];
				foreach ( $_fb_links as $_label => $_url ) {
					$_active = untrailingslashit( $_url ) === $_current_url;
					echo '<a href="' . esc_url( $_url ) . '"' . ( $_active ? ' class="active" aria-current="page"' : '' ) . '>' . esc_html( $_label ) . '</a>';
				}
				unset( $_current_url, $_fb_links, $_label, $_url, $_active );
			}

			/* Language switcher — only when more than one language exists. */
			$topbar_langs = apply_filters( 'drolung_topbar_langs', [] );
			if ( is_array( $topbar_langs ) && count( $topbar_langs ) > 1 ) : ?>
				<div class="lang-sel">
					<?php foreach ( $topbar_langs as $lang ) : ?>
						<?php if ( ! empty( $lang['url'] ) ) : ?>
							<a href="<?php echo esc_url( $lang['url'] ); ?>"<?php echo ! empty( $lang['active'] ) ? ' class="active"' : ''; ?>><?php echo esc_html( $lang['code'] ); ?></a>
						<?php else : ?>
							<span class="unavailable"><?php echo esc_html( $lang['code'] ); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif;

			$donate_url   = apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) );
			$donate_label = apply_filters( 'drolung_donate_label', __( 'Faire un don', 'drolung-branch-2' ) );
			?>
			<a href="<?php echo esc_url( $donate_url ); ?>" class="btn btn--accent"><?php echo esc_html( $donate_label ); ?></a>
		</nav>
	</div>
</header>

<main id="site-content">
