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
		[ 'code' => 'FR', 'url' => '#', 'active' => true  ],
		[ 'code' => 'EN', 'url' => '#', 'active' => false ],
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

				if ( $topbar_langs ) : ?>
					<div class="lang-sel">
						<?php foreach ( $topbar_langs as $lang ) : ?>
							<?php if ( ! empty( $lang['url'] ) ) : ?>
								<a href="<?php echo esc_url( $lang['url'] ); ?>"<?php echo $lang['active'] ? ' class="active"' : ''; ?>><?php echo esc_html( $lang['code'] ); ?></a>
							<?php else : ?>
								<span<?php echo $lang['active'] ? ' class="active"' : ' class="unavailable"'; ?>><?php echo esc_html( $lang['code'] ); ?></span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<a href="<?php echo esc_url( $donate_url ); ?>" class="nav-donate"><?php echo esc_html( $donate_label ); ?></a>
			</div>
		</div>
	</nav>
</header>

<main id="site-content">
