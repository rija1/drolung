<?php
/**
 * drolung-branch-2 — child theme bootstrap ("Terrain" design).
 *
 * Sibling of drolung-branch: same parent (drolung-base), same data layer
 * (branding helpers, drolung_field(), network CPT "projet"), different design.
 * The parent's base.css / base.js / Playfair fonts are dequeued entirely —
 * this theme ships its own self-contained design system (assets/css/theme.css).
 *
 * @package drolung-branch-2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DROLUNG_BRANCH2_VERSION', '0.1.0' );
define( 'DROLUNG_BRANCH2_URI', get_stylesheet_directory_uri() );

/**
 * Replace the parent's asset stack with our own.
 * Priority 20 runs after drolung_base_enqueue_assets (10).
 */
add_action( 'wp_enqueue_scripts', 'drolung_branch2_enqueue_assets', 20 );
function drolung_branch2_enqueue_assets() {
	/* Drop the old design system entirely. */
	wp_dequeue_style( 'drolung-base-css' );
	wp_deregister_style( 'drolung-base-css' );
	wp_dequeue_style( 'drolung-fonts' );
	wp_deregister_style( 'drolung-fonts' );
	wp_dequeue_style( 'drolung-child' );
	wp_deregister_style( 'drolung-child' );
	wp_dequeue_script( 'drolung-base-js' );
	wp_deregister_script( 'drolung-base-js' );

	/* DM Sans only — single family, bold weights for headings. */
	wp_enqueue_style(
		'drolung-branch2-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,700;9..40,800&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'drolung-branch2-css',
		DROLUNG_BRANCH2_URI . '/assets/css/theme.css',
		[ 'drolung-branch2-fonts' ],
		DROLUNG_BRANCH2_VERSION
	);

	wp_enqueue_script(
		'drolung-branch2-js',
		DROLUNG_BRANCH2_URI . '/assets/js/theme.js',
		[],
		DROLUNG_BRANCH2_VERSION,
		true
	);
}

/**
 * Donate link — points to the s'engager page on this subsite.
 */
add_filter( 'drolung_donate_url', function () {
	return home_url( '/s-engager/' );
} );

/**
 * Language switcher — same Polylang integration as drolung-branch.
 * Returns one entry per configured language; single-language sites
 * get a one-item array and the header hides the switcher.
 */
add_filter( 'drolung_topbar_langs', 'drolung_branch2_pll_lang_switcher', 5 );
function drolung_branch2_pll_lang_switcher( $langs ) {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return $langs;
	}

	$pll_list = pll_the_languages( array(
		'raw'              => 1,
		'hide_current'     => 0,
		'display_names_as' => 'slug',
	) );

	if ( empty( $pll_list ) ) {
		return $langs;
	}

	$out = array();
	foreach ( $pll_list as $lang ) {
		if ( ! empty( $lang['no_translation'] ) && empty( $lang['current_lang'] ) ) {
			$out[] = array(
				'code'   => strtoupper( $lang['slug'] ),
				'url'    => '',
				'active' => false,
			);
		} else {
			$out[] = array(
				'code'   => strtoupper( $lang['slug'] ),
				'url'    => esc_url( $lang['url'] ),
				'active' => ! empty( $lang['current_lang'] ),
			);
		}
	}
	return $out;
}

/**
 * Shared footer columns — rendered by footer.php.
 * Kept as a function so page templates never duplicate markup.
 */
function drolung_branch2_footer_columns() {
	?>
	<div class="footer-col">
		<div class="footer-col__title"><?php esc_html_e( 'L\'association', 'drolung-branch-2' ); ?></div>
		<ul>
			<li><a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>"><?php esc_html_e( 'À propos', 'drolung-branch-2' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>"><?php esc_html_e( 'Notre action', 'drolung-branch-2' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/projets/' ) ); ?>"><?php esc_html_e( 'Nos projets', 'drolung-branch-2' ); ?></a></li>
		</ul>
	</div>
	<div class="footer-col">
		<div class="footer-col__title"><?php esc_html_e( 'Participer', 'drolung-branch-2' ); ?></div>
		<ul>
			<li><a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) ) ); ?>"><?php esc_html_e( 'Faire un don', 'drolung-branch-2' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/s-engager/' ) ); ?>"><?php esc_html_e( 'S\'engager', 'drolung-branch-2' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'drolung-branch-2' ); ?></a></li>
		</ul>
	</div>
	<?php
}

/**
 * Reusable "Faire un don" flat band (saffron). Used at the bottom of most pages.
 *
 * @param string $title Band headline.
 * @param string $body  One short supporting sentence.
 * @param string $cta   Button label.
 * @param string $url   Button target (defaults to the donate URL).
 */
function drolung_branch2_donate_band( $title = '', $body = '', $cta = '', $url = '' ) {
	$title = $title ?: __( 'Votre don agit directement.', 'drolung-branch-2' );
	$body  = $body ?: __( 'Chaque euro collecté va intégralement aux projets à Madagascar.', 'drolung-branch-2' );
	$cta   = $cta ?: apply_filters( 'drolung_donate_label', __( 'Faire un don', 'drolung-branch-2' ) );
	$url   = $url ?: apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) );
	?>
	<section class="donate">
		<div class="container">
			<div class="donate__inner">
				<div>
					<h2 class="donate__title"><?php echo esc_html( $title ); ?></h2>
					<p class="donate__body"><?php echo esc_html( $body ); ?></p>
				</div>
				<div class="donate__cta">
					<a href="<?php echo esc_url( $url ); ?>" class="btn"><?php echo esc_html( $cta ); ?></a>
				</div>
			</div>
		</div>
	</section>
	<?php
}
