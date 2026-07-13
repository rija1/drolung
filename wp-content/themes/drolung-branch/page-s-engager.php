<?php
/**
 * Template Name: S'engager
 *
 * Template for the "S'engager" page.
 * Mirrors mockups/mockup-dsf/get-involved.html (canonical source).
 * DSF and DSM share this template; per-site copy is controlled via ACF fields.
 *
 * Structure:
 *   1. Breadcrumb + Page hero
 *   2. Section "Faire un don" (two-col)  — copy differs between DSF and DSM
 *   3. Section "Partagez" (two-col, --tint)
 *   4. Section "Partenariat" (two-col, --dark) with two info cards
 *
 * Key field differences DSF vs DSM (seeded via 05-drolung-acf-seed.php):
 *   DSF  hero_title  = "Agissez avec nous, de plusieurs façons"
 *   DSM  hero_title  = "Soutenez notre action de terrain"
 *   DSF  don_body    = project cost list + "Paiement en ligne bientôt disponible" box
 *   DSM  don_body    = redirect-to-DSF notice box
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="page-breadcrumb">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
    <span>›</span>
    <span><?php esc_html_e( 'S\'engager', 'drolung-branch' ); ?></span>
  </div>
</div>

<section class="page-hero" style="--hero-bg: url('https://images.unsplash.com/photo-1659944984855-776187144baf?auto=format&fit=crop&q=80&w=1600&h=700');">
  <style>.page-hero::before { background-image: var(--hero-bg); }</style>
  <div class="page-hero__line"></div>
  <div class="container">
    <div class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'engager_hero_eyebrow', __( 'S\'engager', 'drolung-branch' ) ) ); ?></div>
    <h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'engager_hero_title', __( 'Agissez avec nous, <em>de plusieurs façons</em>', 'drolung-branch' ) ) ); ?></h1>
    <p class="page-hero__sub"><?php echo esc_html( drolung_field( 'engager_hero_sub', __( 'Un don, un partenariat, un partage — chaque geste compte pour faire avancer les projets à Madagascar.', 'drolung-branch' ) ) ); ?></p>
  </div>
</section>

<?php
/*
 * AssoConnect — lien de redirection, PAS d'intégration en iframe.
 *
 * L'iframe embarquée a été abandonnée (bug confirmé côté AssoConnect :
 * leur widget de paiement Adyen échoue même en accès direct sur leur
 * propre domaine, hors de tout contexte d'intégration — voir journal
 * technique §15, session 2026-07-13). Le visiteur est donc redirigé vers
 * le formulaire AssoConnect dans un nouvel onglet plutôt que de l'afficher
 * intégré sur cette page.
 */
$asc_donate_url = drolung_field( 'engager_assoconnect_url', '' );
if ( ! $asc_donate_url && function_exists( 'drolung_current_branch' ) && drolung_current_branch() === 'dsf' ) {
	$asc_donate_url = 'https://drolung-solidarite-france.assoconnect.com/collect/description/726668-x-soutenir-dsf-drolung-solidarite-france';
}
?>

<!-- Section 1 — Faire un don -->
<section class="inner-section">
  <div class="container">

    <div class="two-col fade-up">
      <div>
        <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'engager_don_eyebrow', __( 'Faire un don', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'engager_don_title', __( 'Votre don agit <em>directement</em>', 'drolung-branch' ) ) ); ?></h2>
        <p class="section-body"><?php echo esc_html( drolung_field( 'engager_don_intro', __( 'Chaque euro versé à DSF est affecté aux projets portés par Drolung Solidarité Madagascar, hors frais administratifs incompressibles (banque + obligations légales, de l\'ordre de 100 € par mois).', 'drolung-branch' ) ) ); ?></p>
        <?php echo wp_kses_post( drolung_field( 'engager_don_body', '<ul style="margin:24px 0;list-style:none;display:flex;flex-direction:column;gap:14px">
          <li style="display:flex;gap:12px;align-items:flex-start"><span style="color:var(--saffron);font-size:18px">&#10022;</span><span style="font-size:15px;color:var(--text-muted)"><strong style="color:var(--charcoal)">' . __( '11–14 800 €', 'drolung-branch' ) . '</strong> — ' . __( 'le coût d\'un captage de source gravitaire desservant 1 300 personnes en eau potable à Ambohitrolomahitsy', 'drolung-branch' ) . '</span></li>
          <li style="display:flex;gap:12px;align-items:flex-start"><span style="color:var(--saffron);font-size:18px">&#10022;</span><span style="font-size:15px;color:var(--text-muted)"><strong style="color:var(--charcoal)">' . __( '4 830 €', 'drolung-branch' ) . '</strong> — ' . __( 'le budget d\'une année complète de l\'École des Femmes à Anjozorobe (12 sessions, 50 à 100 femmes)', 'drolung-branch' ) . '</span></li>
          <li style="display:flex;gap:12px;align-items:flex-start"><span style="color:var(--saffron);font-size:18px">&#10022;</span><span style="font-size:15px;color:var(--text-muted)"><strong style="color:var(--charcoal)">' . __( '4 380 €', 'drolung-branch' ) . '</strong> — ' . __( 'le démarrage de la forêt comestible d\'Anjozorobe pour 15 familles', 'drolung-branch' ) . '</span></li>
        </ul>' ) ); ?>
        <?php if ( $asc_donate_url ) :
          $asc_btn_label = function_exists( 'pll__' ) ? pll__( 'Faire un don via AssoConnect' ) : __( 'Faire un don via AssoConnect', 'drolung-branch' );
          ?>
          <a href="<?php echo esc_url( $asc_donate_url ); ?>" class="btn-page btn-page--primary" style="margin-top:28px;font-style:normal;font-family:var(--font-body);" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $asc_btn_label ); ?></a>
        <?php else : ?>
          <a href="<?php echo esc_url( drolung_field( 'engager_don_cta_url', drolung_lang_url( 'contact' ) ) ); ?>" class="btn-page btn-page--primary" style="margin-top:28px"><?php echo esc_html( drolung_field( 'engager_don_cta_label', __( 'Nous contacter pour un don', 'drolung-branch' ) ) ); ?></a>
        <?php endif; ?>
      </div>
      <img src="<?php echo esc_url( drolung_field( 'engager_don_image', 'https://images.unsplash.com/photo-1627580206975-ede73a2ca147?auto=format&fit=crop&q=80&w=700&h=480' ) ); ?>" alt="<?php echo esc_attr( drolung_field( 'engager_don_image_alt', __( 'Madagascar, terrain', 'drolung-branch' ) ) ); ?>" class="img-full" loading="lazy">
    </div>

  </div>
</section>

<!-- Section 2 — Partagez (two-col, tint) -->
<!-- <section class="inner-section inner-section--tint">
  <div class="container">
    <div class="two-col fade-up">
      <div>
        <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'engager_partage_eyebrow', __( 'Partagez', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'engager_partage_title', __( 'Parlez de nous, <em>partagez nos projets</em>', 'drolung-branch' ) ) ); ?></h2>
        <p class="section-body"><?php echo esc_html( drolung_field( 'engager_partage_body', __( 'Le plus simple des engagements — et l\'un des plus puissants. Mentionner DSF et DSM autour de vous, partager nos publications, relayer nos projets : chaque partage élargit notre portée.', 'drolung-branch' ) ) ); ?></p>
        <div style="display:flex;gap:12px;margin-top:28px;flex-wrap:wrap">
          <a href="<?php echo esc_url( drolung_field( 'engager_facebook_url', '#' ) ); ?>" class="btn-page btn-page--saffron"><?php esc_html_e( 'Facebook', 'drolung-branch' ); ?></a>
          <a href="<?php echo esc_url( drolung_field( 'engager_linkedin_url', '#' ) ); ?>" class="btn-page btn-page--saffron"><?php esc_html_e( 'LinkedIn', 'drolung-branch' ); ?></a>
          <a href="<?php echo esc_url( drolung_field( 'engager_instagram_url', '#' ) ); ?>" class="btn-page btn-page--saffron"><?php esc_html_e( 'Instagram', 'drolung-branch' ); ?></a>
        </div>
      </div>
      <img src="<?php echo esc_url( drolung_field( 'engager_partage_image', 'https://images.unsplash.com/photo-1659944984855-776187144baf?auto=format&fit=crop&q=80&w=700&h=480' ) ); ?>" alt="<?php echo esc_attr( drolung_field( 'engager_partage_image_alt', __( 'Partager', 'drolung-branch' ) ) ); ?>" class="img-full" loading="lazy">
    </div>
  </div>
</section> -->

<!-- Section 3 — Partenariat (two-col, dark) -->
<section class="inner-section inner-section--dark">
  <div class="container">
    <div class="two-col fade-up">
      <div>
        <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'engager_partenariat_eyebrow', __( 'Partenariat', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'engager_partenariat_title', __( 'Vous êtes une entreprise <em>ou une fondation ?</em>', 'drolung-branch' ) ) ); ?></h2>
        <p class="section-body" style="color:rgba(255,255,255,0.7)"><?php echo esc_html( drolung_field( 'engager_partenariat_body', __( 'Nous sommes ouverts à des partenariats de mécénat, de compétences ou de financement de projet. Toute collaboration est traitée avec transparence et fait l\'objet d\'un rapport dédié.', 'drolung-branch' ) ) ); ?></p>
        <a href="<?php echo esc_url( drolung_lang_url( 'contact' ) ); ?>" class="btn-page btn-page--saffron" style="margin-top:28px"><?php echo esc_html( drolung_field( 'engager_partenariat_cta_label', __( 'Nous contacter', 'drolung-branch' ) ) ); ?></a>
      </div>
      <div class="fade-up" style="transition-delay:0.15s;display:flex;flex-direction:column;gap:20px;justify-content:center">
        <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:2px;padding:28px;">
          <div style="font-family:var(--font-serif);font-size:1.1rem;font-weight:600;color:var(--saffron-lt);margin-bottom:10px"><?php echo esc_html( drolung_field( 'engager_mecenat_1_title', __( 'Mécénat', 'drolung-branch' ) ) ); ?></div>
          <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0"><?php echo esc_html( drolung_field( 'engager_mecenat_1_body', __( 'Financement direct d\'un projet identifié, avec rapport de suivi dédié.', 'drolung-branch' ) ) ); ?></p>
        </div>
        <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:2px;padding:28px;">
          <div style="font-family:var(--font-serif);font-size:1.1rem;font-weight:600;color:var(--saffron-lt);margin-bottom:10px"><?php echo esc_html( drolung_field( 'engager_mecenat_2_title', __( 'Mécénat de compétences', 'drolung-branch' ) ) ); ?></div>
          <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0"><?php echo esc_html( drolung_field( 'engager_mecenat_2_body', __( 'Mise à disposition d\'expertise (santé, agronomie, éducation, logistique).', 'drolung-branch' ) ) ); ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer();
