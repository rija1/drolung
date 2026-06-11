<?php
/**
 * Drolung Branch header — top-bar + single sticky nav.
 *
 * Mirrors the DUK design (drolung-duk/header.php) so DSF and DSM share the
 * same header layout. Replaces the parent (drolung-base) header which uses a
 * big centered logo + cross-fading compact bar.
 *
 * Filterable hooks:
 *   drolung_topbar_tagline  — left-hand text in the top bar (default: empty)
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
	$topbar_tagline = apply_filters( 'drolung_topbar_tagline', '' );
	$topbar_langs   = apply_filters( 'drolung_topbar_langs', [
		[ 'code' => 'FR', 'url' => '#', 'active' => true  ],
		[ 'code' => 'EN', 'url' => '#', 'active' => false ],
	] );
	if ( $topbar_tagline || $topbar_langs ) : ?>
	<div class="top-bar">
		<div class="top-bar__inner">
			<?php if ( $topbar_tagline ) : ?>
				<span class="top-bar__tagline"><?php echo esc_html( $topbar_tagline ); ?></span>
			<?php endif; ?>
			<div class="top-bar__links">
				<div class="lang-sel">
					<?php foreach ( $topbar_langs as $lang ) : ?>
						<a href="<?php echo esc_url( $lang['url'] ); ?>"<?php echo $lang['active'] ? ' class="active"' : ''; ?>><?php echo esc_html( $lang['code'] ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

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
				$donate_url   = apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) );
				$donate_label = apply_filters( 'drolung_donate_label', __( 'Faire un don', 'drolung-branch' ) );

				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( [
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'walker'         => class_exists( 'Drolung_Flat_Nav_Walker' ) ? new Drolung_Flat_Nav_Walker() : null,
					] );
					echo '<a href="' . esc_url( $donate_url ) . '" class="nav-donate">' . esc_html( $donate_label ) . '</a>';
				} else {
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="active">Accueil</a>
					<a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>">À propos</a>
					<a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>">Notre action</a>
					<a href="<?php echo esc_url( home_url( '/ou-nous-intervenons/' ) ); ?>">Où nous intervenons</a>
					<a href="<?php echo esc_url( home_url( '/ressources/' ) ); ?>">Ressources</a>
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
