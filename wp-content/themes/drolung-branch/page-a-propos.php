<?php
/**
 * Template for the "À propos" page.
 * Auto-generated from about.html of the mockup.
 * Static text will be moved into ACF in a follow-up.
 *
 * @package drolung-branch
 */

get_header();
?>

<div class="page-breadcrumb">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
    <span>›</span>
    <span><?php esc_html_e( 'À propos', 'drolung-branch' ); ?></span>
  </div>
</div>

<section class="page-hero" style="--hero-bg: url('https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=1600&h=700');">
  <style>.page-hero::before { background-image: var(--hero-bg); }</style>
  <div class="page-hero__line"></div>
  <div class="container">
    <div class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'hero_eyebrow', __( 'À propos', 'drolung-branch' ) ) ); ?></div>
    <h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'hero_title', __( 'Au service <em>des communautés</em>', 'drolung-branch' ) ) ); ?></h1>
    <p class="page-hero__sub"><?php echo esc_html( drolung_field( 'hero_sub', __( 'Drolung Solidarité accompagne les communautés dans une démarche d\'engagement durable, ancrée localement et portée par des bénévoles.', 'drolung-branch' ) ) ); ?></p>
  </div>
</section>

<section class="inner-section">
  <div class="container">
    <div class="two-col fade-up">
      <div>
        <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'mission_eyebrow', __( 'Notre histoire', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'mission_title', __( 'Deux assos, <em>une même intention</em>', 'drolung-branch' ) ) ); ?></h2>
        <div class="section-body"><?php echo wp_kses_post( drolung_field( 'mission_body', '<p>' . __( 'En 2025, plusieurs membres du réseau Drolung — bouddhistes pratiquants franco-malgaches et leurs proches — ont décidé de structurer leur engagement par la création de deux associations sœurs : Drolung Solidarité Madagascar pour porter directement les actions auprès des communautés sur l\'île, Drolung Solidarité France pour mobiliser depuis l\'Hexagone les ressources nécessaires.', 'drolung-branch' ) . '</p><p>' . __( 'Le constat d\'origine est simple : ce que nous voulons faire à Madagascar a besoin d\'être ancré là-bas, et ce que nous voulons offrir comme soutien depuis la France a besoin d\'un cadre clair, transparent et juridiquement adapté. Deux entités, une seule intention.', 'drolung-branch' ) . '</p>' ) ); ?></div>
      </div>
      <img src="https://images.unsplash.com/photo-1624272909636-4995421e37e7?auto=format&fit=crop&q=80&w=700&h=500" alt="" class="img-full" loading="lazy">
    </div>
  </div>
</section>
<section class="inner-section inner-section--dark">
  <div class="container">
    <div class="section-header fade-up" style="max-width:680px;margin:0 auto 48px;text-align:center;">
      <div class="section-eyebrow">Nos valeurs</div>
      <h2 class="section-title">Quatre <em>repères</em></h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:36px;max-width:1100px;margin:0 auto;">
      <div class="fade-up" style="text-align:center;padding:0 8px;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:24px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">Compassion</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Reconnaître la peine des autres comme sienne, et y répondre par l'action.</p>
      </div>
      <div class="fade-up" style="text-align:center;padding:0 8px;transition-delay:0.08s;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:24px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">Humilité</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Écouter avant de parler, apprendre avant de proposer.</p>
      </div>
      <div class="fade-up" style="text-align:center;padding:0 8px;transition-delay:0.16s;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:24px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">Transmission</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Faire passer les savoirs et les responsabilités, sans rien retenir pour soi.</p>
      </div>
      <div class="fade-up" style="text-align:center;padding:0 8px;transition-delay:0.24s;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:24px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">Interdépendance</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Aucun bien-être n'est isolé : nos vies sont liées, nos actions le rappellent.</p>
      </div>
    </div>
  </div>
</section>
<section class="inner-section inner-section--tint">
  <div class="container">
    <div class="section-header fade-up" style="max-width:780px;margin:0 auto;text-align:center;">
      <div class="section-eyebrow">Notre vision</div>
      <h2 class="section-title">Aider, <em>sans imposer</em></h2>
      <p class="section-body">Notre engagement s'enracine dans une conviction simple : on ne peut accompagner durablement les autres qu'en respectant ce qui les fait grandir. Ni messianisme, ni paternalisme — une présence patiente, une écoute exigeante, et l'humilité de reconnaître que les solutions les plus justes viennent presque toujours du terrain.</p>
      <p class="section-body" style="margin-top:16px;">Le bouddhisme, qui irrigue le réseau Drolung, nous rappelle que rien n'existe en isolation. Notre santé dépend de celle des autres, notre prospérité de la leur, notre dignité de leur dignité. Cette interdépendance n'est pas un slogan : elle est le fondement de notre manière d'agir.</p>
    </div>
  </div>
</section>
<section class="inner-section inner-section--tint">
  <div class="container">
    <div class="section-header fade-up" style="max-width:680px;margin-bottom:48px;">
      <div class="section-eyebrow">Le bureau</div>
      <h2 class="section-title">Notre <em>équipe</em></h2>
      <p class="section-body">Cinq membres bénévoles, ancrés dans la réalité du terrain franco-malgache et engagés dans une gouvernance collégiale.</p>
    </div>
    <div class="three-col">
      <div class="card fade-up">
        <div class="card-img" style="aspect-ratio:1/1;height:auto;"><img src="bureau/Rija.JPEG" alt="Rija Ratinahirana" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center top;"></div>
        <div class="card-body"><span class="card-tag">Président</span><div class="card-title">Rija Ratinahirana</div><p class="card-desc">Franco-malgache, titulaire d'un master en informatique. Sa pratique du bouddhisme tibétain et ses origines malgaches ont forgé sa conviction qu'un développement juste vient de l'intérieur des communautés. Il assure le lien stratégique et opérationnel entre Madagascar et la France.</p></div>
      </div>
      <div class="card fade-up" style="transition-delay:0.08s">
        <div class="card-img" style="aspect-ratio:1/1;height:auto;"><img src="bureau/Hajasoa.jpg" alt="Hajasoa Ravololonirina" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center top;"></div>
        <div class="card-body"><span class="card-tag">Vice-Présidente</span><div class="card-title">Hajasoa Ravololonirina</div><p class="card-desc">Docteure en Sciences du Langage et agrégée de lettres, chercheuse associée au LCF de l'Université de La Réunion. Son parcours entre Madagascar et La Réunion nourrit sa réflexion sur la diversité linguistique et la double appartenance culturelle.</p></div>
      </div>
      <div class="card fade-up" style="transition-delay:0.16s">
        <div class="card-img" style="aspect-ratio:1/1;height:auto;"><img src="bureau/Francine.jpg" alt="Francine Ratsimbazafy" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center top;"></div>
        <div class="card-body"><span class="card-tag">Trésorière</span><div class="card-title">Francine Ratsimbazafy</div><p class="card-desc" style="font-style:italic;color:var(--ink-light);">Biographie à venir.</p></div>
      </div>
      <div class="card fade-up">
        <div class="card-img" style="aspect-ratio:1/1;height:auto;"><img src="bureau/Hajatiana.jpg" alt="Hajatiana Randriamialisoa" loading="lazy" style="width:100%;height:100%;object-fit:cover;object-position:center top;"></div>
        <div class="card-body"><span class="card-tag">Secrétaire</span><div class="card-title">Hajatiana Randriamialisoa</div><p class="card-desc" style="font-style:italic;color:var(--ink-light);">Biographie à venir.</p></div>
      </div>
      <div class="card fade-up" style="transition-delay:0.08s">
        <div class="card-img" style="aspect-ratio:1/1;height:auto;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--saffron-pale,#f5e6c8),var(--parchment,#f0e6d2));"><span style="font-family:var(--font-serif);font-size:84px;font-weight:700;color:var(--maroon);letter-spacing:0.04em;">SR</span></div>
        <div class="card-body"><span class="card-tag">Conseillère</span><div class="card-title">Suzy Ratsimbazafy</div><p class="card-desc" style="font-style:italic;color:var(--ink-light);">Biographie à venir.</p></div>
      </div>
    </div>
  </div>
</section>
<section class="inner-section">
  <div class="container">
    <div class="two-col fade-up">
      <img src="https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=700&h=500" alt="Réseau Drolung" class="img-full" loading="lazy">
      <div>
        <div class="section-eyebrow">Le réseau Drolung</div>
        <h2 class="section-title">Une famille <em>internationale</em></h2>
        <p class="section-body">Drolung est un réseau international d'organisations indépendantes partageant un même héritage spirituel et un même engagement humanitaire. À ses côtés, on trouve Drolung UK, Drolung Nepal, Drolung Hong Kong et plusieurs autres entités sœurs, présentes dans plus de vingt pays.</p>
        <p class="section-body" style="margin-top:16px;">DSM et DSF sont les deux entités franco-malgaches du réseau. Autonomes dans leur gouvernance et leurs actions, elles restent reliées au reste de la famille Drolung par les valeurs partagées, le partage d'expérience et l'entraide.</p>
      </div>
    </div>
  </div>
</section>

<?php get_footer();
