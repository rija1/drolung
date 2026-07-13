<?php
/**
 * Template for a single Projet CPT post.
 * Mirrors mockups/mockup-dsf/projet-foret-comestible.html.
 *
 * Always loaded via the drolung-network virtual router (router.php),
 * which only includes this file once it has resolved a valid item —
 * so drolung_item() is guaranteed here. Real 'projet' posts only ever
 * exist on the central site, which uses the drolung-org theme (not
 * this one), so there is no "direct" load path to support.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$item = function_exists( 'drolung_item' ) ? drolung_item() : null;

if ( ! $item ) {
	get_header();
	get_footer();
	return;
}

get_header();

$type_slugs  = array_keys( $item['types'] );
$stat_slugs  = array_keys( $item['statut'] );
$type_name   = $type_slugs ? ( $item['types'][ $type_slugs[0] ] ?? '' ) : '';
$stat_name   = $stat_slugs ? ( $item['statut'][ $stat_slugs[0] ] ?? '' ) : '';
$commune     = $item['meta']['localisation']['commune'];
$region      = $item['meta']['localisation']['region'];

$post_title              = $item['title'];
$post_excerpt            = $item['excerpt'];
$hero_image              = $item['thumbnail']['large'] ?? '';
$projet_domaine          = $type_name;
$projet_statut           = $stat_name;

/*
 * Bénéficiaires : on privilégie le NOMBRE (chiffre clé), avec repli sur la
 * description quand aucun nombre n'est saisi. Le budget n'est volontairement
 * PAS affiché sur la fiche (décision éditoriale) — pas de variable budget ici.
 */
$benef_nombre            = (int) $item['meta']['beneficiaires_nombre'];
$benef_description       = (string) $item['meta']['beneficiaires_description'];
$projet_beneficiaires    = $benef_nombre > 0 ? number_format_i18n( $benef_nombre ) : $benef_description;

$projet_date_debut       = $item['meta']['date_debut'];
$projet_date_fin         = $item['meta']['date_fin'];

/* Partenaires : liste complète (CPT partenaire lié), repli sur le champ texte. */
$projet_partenaires_list = ! empty( $item['partenaires'] ) ? $item['partenaires'] : array();
if ( empty( $projet_partenaires_list ) && ! empty( $item['meta']['partenaire'] ) ) {
	$projet_partenaires_list = array(
		array( 'nom' => $item['meta']['partenaire'], 'logo' => null, 'url' => '', 'role' => '' ),
	);
}
$partenaires_noms        = array_filter( wp_list_pluck( $projet_partenaires_list, 'nom' ) );

/*
 * Editorial sections below (stat badges, défi, budget lines, timeline)
 * aren't wired to admin-editable data yet — no drolung-network CPT
 * field feeds them. They render only once that's built (doc §7 /
 * CLAUDE.md §9 item 7). Until then these stay empty and their sections
 * simply don't display.
 */
$sp_badge_1_num = $sp_badge_1_label = '';
$sp_badge_2_num = $sp_badge_2_label = '';
$sp_badge_3_num = $sp_badge_3_label = '';
$sp_badge_4_num = $sp_badge_4_label = '';

/* Récit: use content_html as the body; title from the post title. */
$sp_recit_eyebrow = __( 'Le projet', 'drolung-branch' );
$sp_recit_title   = '';
$sp_recit_body    = $item['content_html'] ?? '';

$sp_defi_eyebrow = __( 'Le défi structurel', 'drolung-branch' );
$sp_defi_title = $sp_defi_body = '';

$sp_galerie_eyebrow = __( 'En images', 'drolung-branch' );
$sp_galerie_title = $sp_galerie_sub = '';

$sp_budget_eyebrow = __( 'Budget', 'drolung-branch' );
$sp_budget_title = $sp_budget_intro = $sp_budget_lines = '';

$sp_timeline_eyebrow = __( 'Nouvelles du terrain', 'drolung-branch' );
$sp_timeline_title   = __( 'La chronologie', 'drolung-branch' );
$sp_timeline_items   = '';

$sp_cta_eyebrow = __( 'Soutenir ce projet', 'drolung-branch' );
$sp_cta_title = $sp_cta_body = $sp_cta_footer = '';

/* Gallery from extract (photos already resolved to URLs). */
$gallery_images = array_map( function( $img ) {
	return array( 'url' => $img['large'] ?? $img['full'] ?? '', 'alt' => $img['alt'] ?? '' );
}, $item['photos'] );

/*
 * Build a readable date range from meta. ACF stores dates as Ymd
 * (e.g. "20260301"); we render a localised "M Y" ("mars 2026" / "Mar 2026"
 * depending on the current language) instead of the raw digits.
 */
$fmt_projet_date = static function ( $ymd ) {
	$ymd = (string) $ymd;
	if ( strlen( $ymd ) < 6 ) {
		return $ymd; // already free-form or empty — leave as-is
	}
	$ts = strtotime( substr( $ymd, 0, 4 ) . '-' . substr( $ymd, 4, 2 ) . '-' . ( strlen( $ymd ) >= 8 ? substr( $ymd, 6, 2 ) : '01' ) );
	return $ts ? date_i18n( 'M Y', $ts ) : $ymd;
};
$dates_str = '';
if ( $projet_date_debut || $projet_date_fin ) {
	$deb = $projet_date_debut ? $fmt_projet_date( $projet_date_debut ) : '';
	$fin = $projet_date_fin ? $fmt_projet_date( $projet_date_fin ) : '';
	$dates_str = trim( $deb . ( $fin ? ' – ' . $fin : '' ) );
}
?>

	<div class="page-breadcrumb">
		<div class="container">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
			<span>›</span>
			<a href="<?php echo esc_url( home_url( '/projets/' ) ); ?>"><?php esc_html_e( 'Nos projets', 'drolung-branch' ); ?></a>
			<span>›</span>
			<span><?php echo esc_html( $post_title ); ?></span>
		</div>
	</div>

	<!-- HERO PROJET -->
	<section class="page-hero sp-hero"<?php if ( $hero_image ) : ?> style="background-image:url('<?php echo esc_url( $hero_image ); ?>');"<?php endif; ?>>
		<div class="sp-hero__overlay"></div>
		<div class="container sp-hero__body">
			<?php if ( $projet_domaine || $projet_statut ) : ?>
			<div class="sp-hero__badges">
				<?php if ( $projet_domaine ) : ?>
				<span class="sp-badge sp-badge--cream"><?php echo esc_html( strtoupper( drolung_translate_term_name( $projet_domaine ) ) ); ?></span>
				<?php endif; ?>
				<?php if ( $projet_statut ) : ?>
				<span class="sp-badge sp-badge--saffron"><?php echo esc_html( strtoupper( drolung_translate_term_name( $projet_statut ) ) ); ?></span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<h1 class="page-hero__title"><?php echo esc_html( $post_title ); ?></h1>
			<?php if ( $post_excerpt ) : ?>
			<p class="page-hero__sub"><?php echo esc_html( $post_excerpt ); ?></p>
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

	/*
	 * Fallback: build stats from meta if ACF stat badges are empty.
	 * Budget volontairement exclu (décision éditoriale — cf. bloc data en tête).
	 */
	if ( empty( $stats ) ) {
		if ( $commune || $region ) {
			$stats[] = [
				'num'   => esc_html( $commune ?: $region ),
				'label' => esc_html( $commune && $region ? $region : __( 'Localisation', 'drolung-branch' ) ),
			];
		}
		if ( $projet_beneficiaires ) {
			$stats[] = [ 'num' => esc_html( $projet_beneficiaires ), 'label' => __( 'bénéficiaires', 'drolung-branch' ) ];
		}
		if ( ! empty( $partenaires_noms ) ) {
			/*
			 * 'compact' : les noms de partenaires sont du texte de longueur
			 * variable (parfois une liste), pas un chiffre — la grande typo
			 * serif des autres stats les rend disproportionnés et les fait
			 * déborder sur plusieurs lignes. Voir .pp-num--compact (base.css).
			 */
			$stats[] = [
				'num'     => esc_html( implode( ', ', $partenaires_noms ) ),
				'label'   => _n( 'Partenaire', 'Partenaires', count( $partenaires_noms ), 'drolung-branch' ),
				'compact' => true,
			];
		}
		if ( $dates_str ) {
			$stats[] = [ 'num' => esc_html( $dates_str ), 'label' => __( 'période du projet', 'drolung-branch' ) ];
		}
	}
	?>
	<?php if ( ! empty( $stats ) ) : ?>
	<section class="sp-stats-band">
		<div class="container sp-stats-band__grid">
			<?php foreach ( $stats as $stat ) : ?>
			<div>
				<div class="pp-num<?php echo ! empty( $stat['compact'] ) ? ' pp-num--compact' : ''; ?>"><?php echo wp_kses_post( $stat['num'] ); ?></div>
				<div class="pp-numlabel"><?php echo wp_kses_post( $stat['label'] ); ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- RÉCIT -->
	<?php if ( $sp_recit_title || $sp_recit_body ) : ?>
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
			<?php if ( $sp_cta_footer ) : /* localisation + partenaires ont désormais leur propre affichage plus haut */ ?>
			<p style="margin-top:36px;font-family:var(--font-mono);font-size:12px;letter-spacing:0.06em;color:rgba(255,255,255,0.55);"><?php echo esc_html( $sp_cta_footer ); ?></p>
			<?php endif; ?>
		</div></div>
	</section>

<?php get_footer();
