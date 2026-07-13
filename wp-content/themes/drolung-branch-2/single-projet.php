<?php
/**
 * Single Projet — "Terrain" design, simplified.
 *
 * Two load paths, same as drolung-branch :
 *  A) Router path (branch site) : drolung_item() est renseigné par le routeur
 *     virtuel ; pas de vrai post WP, toutes les données viennent de l'extract.
 *  B) Direct path (site central) : vrai post ; drolung_field() + featured image.
 *
 * Sections : page-hero (tag + titre + excerpt) · image · bande méta ·
 *            récit (contenu) · galerie · bandeau don.
 *
 * @package drolung-branch-2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$item = function_exists( 'drolung_item' ) ? drolung_item() : null;

if ( $item ) {

	/* ── A) Router path ── */
	$type_slugs = array_keys( $item['types'] );
	$stat_slugs = array_keys( $item['statut'] );
	$type_name  = $type_slugs ? ( $item['types'][ $type_slugs[0] ] ?? '' ) : '';
	$stat_name  = $stat_slugs ? ( $item['statut'][ $stat_slugs[0] ] ?? '' ) : '';
	$commune    = $item['meta']['localisation']['commune'] ?? '';
	$region     = $item['meta']['localisation']['region'] ?? '';

	$projet_title    = $item['title'];
	$projet_excerpt  = $item['excerpt'];
	$hero_image      = $item['thumbnail']['large'] ?? '';
	$projet_lieu     = $commune . ( $region ? ', ' . $region : '' );
	$projet_budget   = $item['meta']['budget'] ?? '';
	$projet_benef    = $item['meta']['beneficiaires_description'] ?: ( ! empty( $item['meta']['beneficiaires_nombre'] ) ? (string) $item['meta']['beneficiaires_nombre'] : '' );
	$projet_partner  = $item['partenaires'] ? $item['partenaires'][0]['nom'] : ( $item['meta']['partenaire'] ?? '' );
	$projet_body     = $item['content_html'] ?? '';

	$gallery_images = array_map( function ( $img ) {
		return [ 'url' => $img['large'] ?? $img['full'] ?? '', 'alt' => $img['alt'] ?? '' ];
	}, $item['photos'] );

} else {

	/* ── B) Direct post path (site central) ── */
	if ( ! have_posts() ) {
		get_footer();
		exit;
	}
	the_post();

	$projet_title   = get_the_title();
	$projet_excerpt = get_the_excerpt();
	$hero_image     = drolung_field( 'hero_image_url', get_the_post_thumbnail_url( null, 'large' ) ?: '' );
	$type_name      = drolung_field( 'projet_domaine', '' );
	$stat_name      = drolung_field( 'projet_statut', '' );
	$projet_lieu    = drolung_field( 'projet_pays', '' );
	$projet_budget  = drolung_field( 'projet_budget_eur', '' );
	$projet_benef   = drolung_field( 'projet_beneficiaires', '' );
	$projet_partner = drolung_field( 'projet_partenaires', '' );

	ob_start();
	the_content();
	$projet_body = ob_get_clean();

	$gallery_images = [];
}
?>

<section class="page-hero">
	<div class="container">
		<span class="page-hero__eyebrow">
			<?php
			echo esc_html( $type_name ?: __( 'Projet', 'drolung-branch-2' ) );
			if ( $stat_name ) {
				echo ' · ' . esc_html( $stat_name );
			}
			?>
		</span>
		<h1 class="page-hero__title"><?php echo esc_html( $projet_title ); ?></h1>
		<?php if ( $projet_excerpt ) : ?>
			<p class="page-hero__sub"><?php echo esc_html( $projet_excerpt ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php if ( $projet_lieu || $projet_budget || $projet_benef || $projet_partner ) : ?>
<div class="projet-meta-band">
	<div class="container">
		<div class="projet-meta-band__grid">
			<?php if ( $projet_lieu ) : ?>
				<div>
					<div class="projet-meta__label"><?php esc_html_e( 'Lieu', 'drolung-branch-2' ); ?></div>
					<div class="projet-meta__value"><?php echo esc_html( $projet_lieu ); ?></div>
				</div>
			<?php endif; ?>
			<?php if ( $projet_budget ) : ?>
				<div>
					<div class="projet-meta__label"><?php esc_html_e( 'Budget', 'drolung-branch-2' ); ?></div>
					<div class="projet-meta__value"><?php echo esc_html( $projet_budget ); ?></div>
				</div>
			<?php endif; ?>
			<?php if ( $projet_benef ) : ?>
				<div>
					<div class="projet-meta__label"><?php esc_html_e( 'Bénéficiaires', 'drolung-branch-2' ); ?></div>
					<div class="projet-meta__value"><?php echo esc_html( $projet_benef ); ?></div>
				</div>
			<?php endif; ?>
			<?php if ( $projet_partner ) : ?>
				<div>
					<div class="projet-meta__label"><?php esc_html_e( 'Partenaire', 'drolung-branch-2' ); ?></div>
					<div class="projet-meta__value"><?php echo esc_html( $projet_partner ); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<section class="section">
	<div class="container">
		<?php if ( $hero_image ) : ?>
			<img class="projet-hero-img" src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( $projet_title ); ?>" style="margin-bottom:56px">
		<?php endif; ?>

		<?php if ( $projet_body ) : ?>
			<span class="section__eyebrow"><?php esc_html_e( 'Le projet', 'drolung-branch-2' ); ?></span>
			<div class="prose" style="margin-top:16px"><?php echo wp_kses_post( $projet_body ); ?></div>
		<?php endif; ?>
	</div>
</section>

<?php if ( ! empty( $gallery_images ) ) : ?>
<section class="section section--warm">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'En images', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php esc_html_e( 'Sur le terrain', 'drolung-branch-2' ); ?></h2>
			</div>
		</div>
		<div class="gallery-grid">
			<?php foreach ( $gallery_images as $img ) :
				if ( empty( $img['url'] ) ) {
					continue;
				} ?>
				<img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>" loading="lazy">
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
drolung_branch2_donate_band(
	__( 'Soutenez ce projet.', 'drolung-branch-2' ),
	__( 'Votre don finance directement ce projet, sans intermédiaire.', 'drolung-branch-2' )
);

get_footer();
