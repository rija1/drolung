<?php
/**
 * Template Name: Notre action
 *
 * Template for the "Notre action" page.
 * Mirrors mockups/mockup-dsf/what-we-do.html and mockups/mockup-dsm/what-we-do.html.
 * DSF and DSM share this template; per-site copy is controlled via ACF fields
 * seeded by mu-plugins/05-drolung-acf-seed.php.
 * Editable via the "Notre action — contenu éditable" ACF group on this page.
 *
 * Structure:
 *   1. Breadcrumb + Page hero  (always above <main> — header.php opens <main id="site-content">)
 *   2. Intro two-col section   (Notre rôle / Notre approche)
 *   3. Four axes cards (four-col)
 *   4. Dark section — 4 principles / commitments
 *
 * @package drolung-branch
 */

get_header();
?>

<div class="page-breadcrumb">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
    <span>›</span>
    <span><?php esc_html_e( 'Notre action', 'drolung-branch' ); ?></span>
  </div>
</div>

<section class="page-hero" style="--hero-bg: url('https://images.unsplash.com/photo-1659944984855-776187144baf?auto=format&fit=crop&q=80&w=1600&h=700');">
  <style>.page-hero::before { background-image: var(--hero-bg); }</style>
  <div class="page-hero__line"></div>
  <div class="container">
    <div class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'hero_eyebrow', __( 'Notre action', 'drolung-branch' ) ) ); ?></div>
    <h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'hero_title', __( 'Agir <em>au plus près</em>', 'drolung-branch' ) ) ); ?></h1>
    <p class="page-hero__sub"><?php echo esc_html( drolung_field( 'hero_sub', __( 'Aux côtés des familles, des écoles et des soignants. Pour un changement durable, choisi de l\'intérieur.', 'drolung-branch' ) ) ); ?></p>
  </div>
</section>

<!-- Intro — Notre rôle / Notre approche (two-col, editable per site via ACF) -->
<section class="inner-section inner-section--tint">
  <div class="container">
    <div class="grid-2-equal fade-up">
      <div>
        <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'intro_eyebrow', __( 'Notre approche', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title" style="margin-bottom:0"><?php echo wp_kses_post( drolung_field( 'intro_title', __( 'Le terrain <em>d\'abord</em>', 'drolung-branch' ) ) ); ?></h2>
      </div>
      <div>
        <?php echo wp_kses_post( drolung_field( 'intro_body', '<p>' . __( 'Nous croyons qu\'un développement juste ne se décrète pas depuis l\'extérieur. Il s\'enracine dans les besoins exprimés par les communautés elles-mêmes, dans le respect de leurs savoirs, de leur rythme et de leur dignité.', 'drolung-branch' ) . '</p><p style="margin-top:16px">' . __( 'Nous travaillons en lien direct avec les familles, les enseignants, les soignants et les acteurs locaux. Nous avançons à leurs côtés, jamais à leur place.', 'drolung-branch' ) . '</p>' ) ); ?>
      </div>
    </div>
  </div>
</section>

<!-- Quatre axes d'action (four-col cards) -->
<section class="inner-section">
  <div class="container">
    <div class="section-header fade-up">
      <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'axes_eyebrow', __( 'Les actions que nous soutenons', 'drolung-branch' ) ) ); ?></div>
      <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'axes_title', __( 'Quatre axes <em>indissociables</em>', 'drolung-branch' ) ) ); ?></h2>
      <p class="section-body"><?php echo esc_html( drolung_field( 'axes_body', __( 'Notre soutien finance quatre domaines d\'intervention que nous tenons pour inséparables : l\'éducation, la santé, l\'environnement et l\'accès à l\'eau. Chacun se renforce des autres.', 'drolung-branch' ) ) ); ?></p>
    </div>
    <div class="four-col">
      <?php
      $axe_defaults = [
          1 => [
              'title' => __( 'Apprendre, transmettre, faire grandir', 'drolung-branch' ),
              'body'  => '<p>' . __( 'Donner aux enfants les moyens d\'aller à l\'école, accompagner les jeunes dans leur parcours, soutenir les passeurs de savoirs locaux. Notre engagement porte sur l\'avenir d\'une génération.', 'drolung-branch' ) . '</p>',
              'tag'   => __( 'Éducation', 'drolung-branch' ),
              'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&q=80&w=700&h=420',
              'alt'   => __( 'Éducation et transmission', 'drolung-branch' ),
          ],
          2 => [
              'title' => __( 'Prendre soin, sans condition', 'drolung-branch' ),
              'body'  => '<p>' . __( 'Soutenir l\'accès aux soins de base, les structures de santé locales et l\'accompagnement de la santé maternelle et infantile. Parce que se soigner ne devrait jamais relever du privilège.', 'drolung-branch' ) . '</p>',
              'tag'   => __( 'Santé', 'drolung-branch' ),
              'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=700&h=420',
              'alt'   => __( 'Santé et accès aux soins', 'drolung-branch' ),
          ],
          3 => [
              'title' => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
              'body'  => '<p>' . __( 'Soutenir l\'agriculture vivrière, les coopératives et les artisans, et la préservation des écosystèmes. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ) . '</p>',
              'tag'   => __( 'Environnement', 'drolung-branch' ),
              'image' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=700&h=420',
              'alt'   => __( 'Environnement et économies locales', 'drolung-branch' ),
          ],
          4 => [
              'title' => __( 'L\'eau, avant tout', 'drolung-branch' ),
              'body'  => '<p>' . __( 'Financer l\'accès à l\'eau potable et aux infrastructures sanitaires là où elles manquent le plus. Parce que sans eau, rien d\'autre n\'est possible.', 'drolung-branch' ) . '</p>',
              'tag'   => __( 'Eau &amp; Assainissement', 'drolung-branch' ),
              'image' => 'https://images.unsplash.com/photo-1569511166187-97b27af41b5a?auto=format&fit=crop&q=80&w=700&h=420',
              'alt'   => __( 'Eau et assainissement', 'drolung-branch' ),
          ],
      ];
      foreach ( $axe_defaults as $i => $d ) :
          $tag   = drolung_field( "axe_{$i}_tag",   $d['tag'] );
          $title = drolung_field( "axe_{$i}_title", $d['title'] );
          $body  = drolung_field( "axe_{$i}_body",  $d['body'] );
          $image = drolung_field( "axe_{$i}_image", $d['image'] );
          $delay = ( $i - 1 ) * 0.08;
          ?>
          <div class="card fade-up"<?php echo $delay > 0 ? ' style="transition-delay:' . esc_attr( $delay ) . 's"' : ''; ?>>
              <div class="card-img"><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $d['alt'] ); ?>" loading="lazy"></div>
              <div class="card-body">
                  <span class="card-tag"><?php echo esc_html( $tag ); ?></span>
                  <div class="card-title"><?php echo esc_html( $title ); ?></div>
                  <div class="card-desc"><?php echo wp_kses_post( $body ); ?></div>
              </div>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section sombre — 4 principes / engagements (editable per site via ACF) -->
<section class="inner-section inner-section--dark">
  <div class="container">
    <div class="section-header fade-up" style="max-width:680px;margin:0 auto 48px;text-align:center;">
      <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'principes_eyebrow', __( 'Nos engagements', 'drolung-branch' ) ) ); ?></div>
      <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'principes_title', __( 'Quatre <em>engagements</em>', 'drolung-branch' ) ) ); ?></h2>
      <p class="section-body"><?php echo esc_html( drolung_field( 'principes_body', __( 'Quatre engagements qui structurent notre relation avec nos donateurs et avec notre association sœur.', 'drolung-branch' ) ) ); ?></p>
    </div>
    <div class="grid-4-cards">
      <?php
      $principe_defaults = [
          1 => [
              'label' => __( '100 % vers le terrain', 'drolung-branch' ),
              'body'  => __( 'L\'intégralité des fonds collectés est destinée aux projets de terrain. Aucun frais de structure prélevé sur les dons.', 'drolung-branch' ),
          ],
          2 => [
              'label' => __( 'Une équipe bénévole', 'drolung-branch' ),
              'body'  => __( 'Le bureau et l\'ensemble des contributeurs réguliers travaillent sans rémunération.', 'drolung-branch' ),
          ],
          3 => [
              'label' => __( 'Transparence intégrale', 'drolung-branch' ),
              'body'  => __( 'Chaque euro engagé est suivi, documenté et rendu public dans nos comptes annuels.', 'drolung-branch' ),
          ],
          4 => [
              'label' => __( 'Un lien direct', 'drolung-branch' ),
              'body'  => __( 'Pas d\'intermédiaire entre le don et l\'action sur le terrain. Une seule destination identifiée pour chaque collecte.', 'drolung-branch' ),
          ],
      ];
      foreach ( $principe_defaults as $i => $p ) :
          $label = drolung_field( "principe_{$i}_label", $p['label'] );
          $body  = drolung_field( "principe_{$i}_body",  $p['body'] );
          $delay = ( $i - 1 ) * 0.08;
          ?>
          <div class="fade-up" style="text-align:center;padding:0 8px;<?php echo $delay > 0 ? 'transition-delay:' . esc_attr( $delay ) . 's' : ''; ?>">
            <div style="font-family:var(--font-serif);font-style:italic;font-size:22px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;"><?php echo esc_html( $label ); ?></div>
            <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;"><?php echo esc_html( $body ); ?></p>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_footer();
