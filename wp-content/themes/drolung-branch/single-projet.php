<?php
/**
 * Template for a single Projet CPT post.
 * Mirrors mockups/mockup-dsf/projet-foret-comestible.html.
 * Editable via the matching ACF group (group_drolung_single_projet).
 *
 * TODO: migrer vers drolung_item() quand drolung-network est actif.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

if ( ! have_posts() ) {
	get_footer();
	exit;
}

the_post();

/* ── Hero image: ACF override > featured image > empty ─────────── */
$hero_image_acf = drolung_field( 'hero_image_url', '' );
$hero_image     = $hero_image_acf
	? $hero_image_acf
	: get_the_post_thumbnail_url( get_the_ID(), 'large' );

/* ── Project meta fields ────────────────────────────────────────── */
$projet_domaine          = drolung_field( 'projet_domaine', '' );
$projet_statut           = drolung_field( 'projet_statut', '' );
$projet_pays             = drolung_field( 'projet_pays', '' );
$projet_budget_eur       = drolung_field( 'projet_budget_eur', '' );
$projet_montant_collecte = drolung_field( 'projet_montant_collecte_eur', '' );
$projet_beneficiaires    = drolung_field( 'projet_beneficiaires', '' );
$projet_date_debut       = drolung_field( 'projet_date_debut', '' );
$projet_date_fin         = drolung_field( 'projet_date_fin', '' );
$projet_partenaires      = drolung_field( 'projet_partenaires', '' );

/* ── Build dates string ─────────────────────────────────────────── */
$dates_str = '';
if ( $projet_date_debut || $projet_date_fin ) {
	$dates_str = trim( $projet_date_debut . ( $projet_date_fin ? '–' . $projet_date_fin : '' ) );
}

/* ── Editorial sections (ACF-editable per project) ──────────────── */
$sp_badge_1_num   = drolung_field( 'single_projet_stat_1_num',   '' );
$sp_badge_1_label = drolung_field( 'single_projet_stat_1_label', '' );
$sp_badge_2_num   = drolung_field( 'single_projet_stat_2_num',   '' );
$sp_badge_2_label = drolung_field( 'single_projet_stat_2_label', '' );
$sp_badge_3_num   = drolung_field( 'single_projet_stat_3_num',   '' );
$sp_badge_3_label = drolung_field( 'single_projet_stat_3_label', '' );
$sp_badge_4_num   = drolung_field( 'single_projet_stat_4_num',   '' );
$sp_badge_4_label = drolung_field( 'single_projet_stat_4_label', '' );

$sp_recit_eyebrow = drolung_field( 'single_projet_recit_eyebrow', __( 'Le projet', 'drolung-branch' ) );
$sp_recit_title   = drolung_field( 'single_projet_recit_title',   '' );
$sp_recit_body    = drolung_field( 'single_projet_recit_body',    '' );

$sp_defi_eyebrow  = drolung_field( 'single_projet_defi_eyebrow',  __( 'Le défi structurel', 'drolung-branch' ) );
$sp_defi_title    = drolung_field( 'single_projet_defi_title',    '' );
$sp_defi_body     = drolung_field( 'single_projet_defi_body',     '' );

$sp_galerie_eyebrow = drolung_field( 'single_projet_galerie_eyebrow', __( 'En images', 'drolung-branch' ) );
$sp_galerie_title   = drolung_field( 'single_projet_galerie_title',   '' );
$sp_galerie_sub     = drolung_field( 'single_projet_galerie_sub',     '' );

$sp_budget_eyebrow  = drolung_field( 'single_projet_budget_eyebrow', __( 'Budget', 'drolung-branch' ) );
$sp_budget_title    = drolung_field( 'single_projet_budget_title',   '' );
$sp_budget_intro    = drolung_field( 'single_projet_budget_intro',   '' );
$sp_budget_lines    = drolung_field( 'single_projet_budget_lines',   '' ); // HTML <ul>

$sp_timeline_eyebrow = drolung_field( 'single_projet_timeline_eyebrow', __( 'Nouvelles du terrain', 'drolung-branch' ) );
$sp_timeline_title   = drolung_field( 'single_projet_timeline_title',   __( 'La chronologie', 'drolung-branch' ) );
$sp_timeline_items   = drolung_field( 'single_projet_timeline_items',   '' ); // HTML <ul>

$sp_cta_eyebrow = drolung_field( 'single_projet_cta_eyebrow', __( 'Soutenir ce projet', 'drolung-branch' ) );
$sp_cta_title   = drolung_field( 'single_projet_cta_title',   '' );
$sp_cta_body    = drolung_field( 'single_projet_cta_body',    '' );
$sp_cta_footer  = drolung_field( 'single_projet_cta_footer',  '' );
?>

	<div class="page-breadcrumb">
		<div class="container">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
			<span>›</span>
			<a href="<?php echo esc_url( home_url( '/projets/' ) ); ?>"><?php esc_html_e( 'Nos projets', 'drolung-branch' ); ?></a>
			<span>›</span>
			<span><?php the_title(); ?></span>
		</div>
	</div>

	<!-- HERO PROJET -->
	<section class="page-hero sp-hero"<?php if ( $hero_image ) : ?> style="background-image:url('<?php echo esc_url( $hero_image ); ?>');"<?php endif; ?>>
		<div class="sp-hero__overlay"></div>
		<div class="container sp-hero__body">
			<?php if ( $projet_domaine || $projet_statut ) : ?>
			<div class="sp-hero__badges">
				<?php if ( $projet_domaine ) : ?>
				<span class="sp-badge sp-badge--cream"><?php echo esc_html( strtoupper( $projet_domaine ) ); ?></span>
				<?php endif; ?>
				<?php if ( $projet_statut ) : ?>
				<span class="sp-badge sp-badge--saffron"><?php echo esc_html( strtoupper( $projet_statut ) ); ?></span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<h1 class="page-hero__title"><?php echo wp_kses_post( get_the_title() ); ?></h1>
			<?php if ( has_excerpt() ) : ?>
			<p class="page-hero__sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- CHIFFRES CLÉS -->
	<?php
	$stats = array_filter( [
		[ 'num' => $sp_badge_1_num, 'label' => $sp_badge_1_label ],
		[ 'num' => $sp_badge_2_num, 'label' => $sp_badge_2_label ],
		[ 'num' => $sp_badge_3_num, 'label' => $sp_badge_3_label ],
		[ 'num' => $sp_badge_4_num, 'label' => $sp_badge_4_label ],
	], function( $s ) { return $s['num'] !== ''; } );

	/* Fallback: build stats from meta if ACF stat fields are empty */
	if ( empty( $stats ) ) {
		if ( $projet_beneficiaires ) {
			$stats[] = [ 'num' => esc_html( $projet_beneficiaires ), 'label' => __( 'bénéficiaires', 'drolung-branch' ) ];
		}
		if ( $dates_str ) {
			$stats[] = [ 'num' => esc_html( $dates_str ), 'label' => __( 'période du projet', 'drolung-branch' ) ];
		}
		if ( $projet_budget_eur ) {
			$stats[] = [ 'num' => esc_html( $projet_budget_eur ) . ' €', 'label' => __( 'budget', 'drolung-branch' ) ];
		}
		if ( $projet_montant_collecte ) {
			$stats[] = [ 'num' => esc_html( $projet_montant_collecte ) . ' €', 'label' => __( 'collectés', 'drolung-branch' ) ];
		}
	}
	?>
	<?php if ( ! empty( $stats ) ) : ?>
	<section class="sp-stats-band">
		<div class="container sp-stats-band__grid">
			<?php foreach ( $stats as $stat ) : ?>
			<div>
				<div class="pp-num"><?php echo wp_kses_post( $stat['num'] ); ?></div>
				<div class="pp-numlabel"><?php echo wp_kses_post( $stat['label'] ); ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- RÉCIT -->
	<?php if ( $sp_recit_title || $sp_recit_body || get_the_content() ) : ?>
	<section class="inner-section">
		<div class="container"><div style="max-width:760px">
			<?php if ( $sp_recit_eyebrow ) : ?>
			<div class="section-eyebrow"><?php echo esc_html( $sp_recit_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( $sp_recit_title ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $sp_recit_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $sp_recit_body ) : ?>
				<?php echo wp_kses_post( $sp_recit_body ); ?>
			<?php elseif ( get_the_content() ) : ?>
				<div class="section-body"><?php the_content(); ?></div>
			<?php endif; ?>
		</div></div>
	</section>
	<?php endif; ?>

	<!-- DÉFI / CONTRAINTE — dark section -->
	<?php if ( $sp_defi_title || $sp_defi_body ) : ?>
	<section class="inner-section inner-section--dark">
		<div class="container"><div style="max-width:760px">
			<?php if ( $sp_defi_eyebrow ) : ?>
			<div class="section-eyebrow"><?php echo esc_html( $sp_defi_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( $sp_defi_title ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $sp_defi_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $sp_defi_body ) : ?>
			<div class="section-body"><?php echo wp_kses_post( $sp_defi_body ); ?></div>
			<?php endif; ?>
		</div></div>
	</section>
	<?php endif; ?>

	<!-- GALERIE PHOTOS -->
	<?php
	$gallery_images = [];
	if ( function_exists( 'get_field' ) ) {
		$raw = get_field( 'photos', get_the_ID() );
		if ( is_array( $raw ) && ! empty( $raw ) ) {
			$gallery_images = $raw;
		}
	}
	?>
	<?php if ( ! empty( $gallery_images ) ) : ?>
	<section class="inner-section">
		<div class="container">
			<?php if ( $sp_galerie_eyebrow ) : ?>
			<div class="section-eyebrow"><?php echo esc_html( $sp_galerie_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( $sp_galerie_title ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $sp_galerie_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $sp_galerie_sub ) : ?>
			<p class="section-body" style="max-width:640px;"><?php echo esc_html( $sp_galerie_sub ); ?></p>
			<?php endif; ?>
			<div class="pp-gallery">
				<?php foreach ( $gallery_images as $img ) :
					if ( is_array( $img ) ) {
						$img_url = isset( $img['url'] ) ? $img['url'] : '';
						$img_alt = isset( $img['alt'] ) ? $img['alt'] : '';
					} else {
						$img_url = $img;
						$img_alt = '';
					}
					if ( ! $img_url ) continue;
				?>
				<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy">
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- BUDGET -->
	<?php if ( $sp_budget_title || $sp_budget_intro || $sp_budget_lines ) : ?>
	<section class="inner-section inner-section--tint">
		<div class="container"><div style="max-width:760px">
			<?php if ( $sp_budget_eyebrow ) : ?>
			<div class="section-eyebrow"><?php echo esc_html( $sp_budget_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( $sp_budget_title ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $sp_budget_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $sp_budget_intro ) : ?>
			<p class="section-body"><?php echo wp_kses_post( $sp_budget_intro ); ?></p>
			<?php endif; ?>
			<?php if ( $sp_budget_lines ) : ?>
			<ul class="pp-budget"><?php echo wp_kses_post( $sp_budget_lines ); ?></ul>
			<?php endif; ?>
		</div></div>
	</section>
	<?php endif; ?>

	<!-- TIMELINE -->
	<?php if ( $sp_timeline_items ) : ?>
	<section class="inner-section">
		<div class="container"><div style="max-width:760px">
			<?php if ( $sp_timeline_eyebrow ) : ?>
			<div class="section-eyebrow"><?php echo esc_html( $sp_timeline_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( $sp_timeline_title ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $sp_timeline_title ); ?></h2>
			<?php endif; ?>
			<ul class="pp-timeline"><?php echo wp_kses_post( $sp_timeline_items ); ?></ul>
		</div></div>
	</section>
	<?php endif; ?>

	<!-- CTA DON -->
	<section class="inner-section inner-section--maroon">
		<div class="container"><div style="max-width:760px">
			<?php if ( $sp_cta_eyebrow ) : ?>
			<div class="section-eyebrow"><?php echo esc_html( $sp_cta_eyebrow ); ?></div>
			<?php endif; ?>
			<?php if ( $sp_cta_title ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $sp_cta_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $sp_cta_body ) : ?>
			<p class="section-body"><?php echo wp_kses_post( $sp_cta_body ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) ) ); ?>" class="pp-cta-btn" style="margin-top:24px;"><?php echo esc_html( apply_filters( 'drolung_donate_label', __( 'Faire un don', 'drolung-branch' ) ) ); ?></a>
			<?php if ( $sp_cta_footer ) : ?>
			<p style="margin-top:36px;font-family:var(--font-mono);font-size:12px;letter-spacing:0.06em;color:rgba(255,255,255,0.55);"><?php echo esc_html( $sp_cta_footer ); ?></p>
			<?php elseif ( $projet_pays || $projet_partenaires ) : ?>
			<p style="margin-top:36px;font-family:var(--font-mono);font-size:12px;letter-spacing:0.06em;color:rgba(255,255,255,0.55);">
				<?php
				$footer_parts = [];
				if ( $projet_partenaires ) {
					$footer_parts[] = strtoupper( $projet_partenaires );
				}
				if ( $projet_pays ) {
					$footer_parts[] = strtoupper( esc_html__( 'LOCALISATION : ', 'drolung-branch' ) ) . strtoupper( $projet_pays );
				}
				echo esc_html( implode( ' · ', $footer_parts ) );
				?>
			</p>
			<?php endif; ?>
		</div></div>
	</section>

<?php get_footer();
