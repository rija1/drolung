<?php
/**
 * Template for the "Notre action" page.
 * Auto-generated from what-we-do.html of the mockup.
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
    <span><?php esc_html_e( 'Notre action', 'drolung-branch' ); ?></span>
  </div>
</div>

<section class="page-hero" style="--hero-bg: url('https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=1600&h=700');">
  <style>.page-hero::before { background-image: var(--hero-bg); }</style>
  <div class="page-hero__line"></div>
  <div class="container">
    <div class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'hero_eyebrow', __( 'Notre action', 'drolung-branch' ) ) ); ?></div>
    <h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'hero_title', __( 'Agir <em>au plus près</em>', 'drolung-branch' ) ) ); ?></h1>
    <p class="page-hero__sub"><?php echo esc_html( drolung_field( 'hero_sub', __( 'Aux côtés des familles, des écoles et des soignants. Pour un changement durable, choisi de l\'intérieur.', 'drolung-branch' ) ) ); ?></p>
  </div>
</section>

<!-- Notre approche -->
<section class="inner-section inner-section--tint">
  <div class="container">
    <div class="section-header fade-up" style="max-width:780px;margin:0 auto;text-align:center;">
      <div class="section-eyebrow"><?php esc_html_e( 'Notre approche', 'drolung-branch' ); ?></div>
      <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'intro_title', __( 'Le terrain <em>d\'abord</em>', 'drolung-branch' ) ) ); ?></h2>
      <div class="section-body"><?php echo wp_kses_post( drolung_field( 'intro_body', '<p>' . __( 'Nous croyons qu\'un développement juste ne se décrète pas depuis l\'extérieur. Il s\'enracine dans les besoins exprimés par les communautés elles-mêmes, dans le respect de leurs savoirs, de leur rythme et de leur dignité.', 'drolung-branch' ) . '</p><p>' . __( 'Nous travaillons en lien direct avec les familles, les enseignants, les soignants et les acteurs locaux. Nous avançons à leurs côtés, jamais à leur place.', 'drolung-branch' ) . '</p>' ) ); ?></div>
    </div>
  </div>
</section>

<!-- Trois axes d'action -->
<section class="inner-section">
  <div class="container">
    <div class="section-header fade-up">
      <div class="section-eyebrow"><?php esc_html_e( 'Nos axes d\'action', 'drolung-branch' ); ?></div>
      <h2 class="section-title"><?php esc_html_e( 'Trois axes', 'drolung-branch' ); ?> <em><?php esc_html_e( 'indissociables', 'drolung-branch' ); ?></em></h2>
      <p class="section-body"><?php esc_html_e( 'L\'éducation, la santé et l\'environnement ne s\'opposent jamais : ce sont les trois conditions d\'une vie digne. Nous travaillons aux trois en même temps, parce que c\'est ensemble qu\'ils prennent sens.', 'drolung-branch' ); ?></p>
    </div>
    <div class="three-col">
      <?php
      $axe_defaults = [
          1 => [
              'title' => __( 'Apprendre, transmettre, faire grandir', 'drolung-branch' ),
              'body'  => '<p>' . __( 'Soutenir la scolarité des enfants, accompagner les jeunes dans leurs études et leur orientation, valoriser la transmission des savoirs locaux. Parce que chaque génération a le droit de choisir son avenir en connaissance de cause.', 'drolung-branch' ) . '</p>',
              'tag'   => __( 'Éducation', 'drolung-branch' ),
              'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&q=80&w=700&h=420',
          ],
          2 => [
              'title' => __( 'Prendre soin, sans condition', 'drolung-branch' ),
              'body'  => '<p>' . __( 'Faciliter l\'accès aux soins de base, soutenir les structures de santé locales, accompagner la santé maternelle et infantile. Parce que se soigner ne devrait jamais être un privilège.', 'drolung-branch' ) . '</p>',
              'tag'   => __( 'Santé', 'drolung-branch' ),
              'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=700&h=420',
          ],
          3 => [
              'title' => __( 'Vivre de son sol, durablement', 'drolung-branch' ),
              'body'  => '<p>' . __( 'Encourager l\'agriculture vivrière, soutenir les coopératives et les artisans, préserver les écosystèmes dont dépendent les familles. Parce que prospérer chez soi vaut mieux que de devoir partir.', 'drolung-branch' ) . '</p>',
              'tag'   => __( 'Environnement', 'drolung-branch' ),
              'image' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&q=80&w=700&h=420',
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
              <div class="card-img"><img src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy"></div>
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

<!-- Nos principes -->
<section class="inner-section inner-section--dark">
  <div class="container">
    <div class="section-header fade-up" style="max-width:680px;margin:0 auto 48px;text-align:center;">
      <div class="section-eyebrow">Nos principes</div>
      <h2 class="section-title">Quatre <em>repères</em></h2>
      <p class="section-body">Quatre repères qui guident chacune de nos décisions, sur le terrain comme dans nos choix d'organisation.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:36px;max-width:1100px;margin:0 auto;">
      <div class="fade-up" style="text-align:center;padding:0 8px;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:22px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">Au plus près</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Une présence directe sur le terrain, en lien permanent avec les familles et les acteurs locaux.</p>
      </div>
      <div class="fade-up" style="text-align:center;padding:0 8px;transition-delay:0.08s;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:22px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">Avec humilité</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Écouter et apprendre des partenaires locaux avant de proposer. Les solutions justes viennent toujours du terrain.</p>
      </div>
      <div class="fade-up" style="text-align:center;padding:0 8px;transition-delay:0.16s;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:22px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">Dans la durée</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Privilégier les engagements longs aux opérations ponctuelles. Le changement réel demande du temps.</p>
      </div>
      <div class="fade-up" style="text-align:center;padding:0 8px;transition-delay:0.24s;">
        <div style="font-family:var(--font-serif);font-style:italic;font-size:22px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;">En transparence</div>
        <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;">Rendre des comptes sur chaque action menée et chaque euro reçu.</p>
      </div>
    </div>
  </div>
</section>

<?php get_footer();
