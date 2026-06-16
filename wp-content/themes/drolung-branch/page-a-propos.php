<?php
/**
 * Template for the "À propos" page.
 * Mirrors mockups/mockup-dsf/about.html (canonical source).
 * DSM and DSF share this template; per-site copy (hero text, team members,
 * Rinpoche section body, etc.) is controlled via ACF fields seeded by
 * mu-plugins/05-drolung-acf-seed.php.
 *
 * Structure:
 *   1. Breadcrumb + Page hero
 *   2. Notre histoire — two-col intro
 *   3. Nos valeurs — 4 values dark section
 *   4. Drupon Khen Rinpoche — maroon feature section
 *   5. Quote section
 *   6. Le bureau — member-list horizontal cards
 *   7. Le réseau Drolung — two-col outro
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<div class="page-breadcrumb">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'drolung-branch' ); ?></a>
    <span>›</span>
    <span><?php esc_html_e( 'À propos', 'drolung-branch' ); ?></span>
  </div>
</div>

<section class="page-hero" style="--hero-bg: url('<?php echo esc_url( drolung_field( 'hero_image', 'https://images.unsplash.com/photo-1627580206975-ede73a2ca147?auto=format&fit=crop&q=80&w=1600&h=700' ) ); ?>');">
  <style>.page-hero::before { background-image: var(--hero-bg); }</style>
  <div class="page-hero__line"></div>
  <div class="container">
    <div class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'hero_eyebrow', __( 'À propos', 'drolung-branch' ) ) ); ?></div>
    <h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'hero_title', __( 'Un pont <em>vers Madagascar</em>', 'drolung-branch' ) ) ); ?></h1>
    <p class="page-hero__sub"><?php echo esc_html( drolung_field( 'hero_sub', __( 'Drolung Solidarité France mobilise depuis la France les ressources et le soutien nécessaires aux actions menées par notre association sœur à Madagascar. Une équipe bénévole, un engagement transparent.', 'drolung-branch' ) ) ); ?></p>
  </div>
</section>

<!-- Notre histoire — two-col intro -->
<section class="inner-section">
  <div class="container">
    <div class="two-col fade-up">
      <div>
        <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'histoire_eyebrow', __( 'Notre histoire', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'histoire_title', __( 'Deux assos, <em>une même intention</em>', 'drolung-branch' ) ) ); ?></h2>
        <?php echo wp_kses_post( drolung_field( 'histoire_body',
          '<p class="section-body">' . __( 'En 2025, plusieurs membres du réseau Drolung — bouddhistes pratiquants franco-malgaches et leurs proches — ont décidé de structurer leur engagement. En 2026, deux associations sœurs voient le jour : Drolung Solidarité France pour mobiliser depuis l\'Hexagone les ressources et le soutien nécessaires, Drolung Solidarité Madagascar pour porter directement les actions auprès des communautés sur l\'île.', 'drolung-branch' ) . '</p>'
          . '<p class="section-body" style="margin-top:16px">' . __( 'Basée en France, Drolung Solidarité France rassemble des bénévoles engagés autour d\'une conviction simple : les fonds collectés en Europe doivent servir des projets réels, identifiés, conduits par des personnes qui connaissent le terrain. Notre rôle est de faire le lien — mobiliser ici, pour que les choses changent là-bas.', 'drolung-branch' ) . '</p>'
          . '<p class="section-body" style="margin-top:16px">' . __( 'Le constat d\'origine est simple : ce que nous voulons offrir comme soutien depuis la France a besoin d\'un cadre clair, transparent et juridiquement adapté ; ce que nous voulons faire à Madagascar a besoin d\'être ancré là-bas. Deux entités, une seule intention.', 'drolung-branch' ) . '</p>'
        ) ); ?>
      </div>
      <img src="<?php echo esc_url( drolung_field( 'histoire_image', 'https://images.unsplash.com/photo-1504598578017-40d9b776f1bc?auto=format&fit=crop&q=80&w=700&h=500' ) ); ?>" alt="<?php esc_attr_e( 'Solidarité', 'drolung-branch' ); ?>" class="img-full" loading="lazy">
    </div>
  </div>
</section>

<!-- Nos valeurs — dark section with 4 values -->
<section class="inner-section inner-section--dark">
  <div class="container">
    <div class="section-header fade-up" style="max-width:680px;margin:0 auto 48px;text-align:center;">
      <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'valeurs_eyebrow', __( 'Nos valeurs', 'drolung-branch' ) ) ); ?></div>
      <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'valeurs_title', __( 'Quatre <em>repères</em>', 'drolung-branch' ) ) ); ?></h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:36px;max-width:1100px;margin:0 auto;">
      <?php
      $valeur_defaults = [
          1 => [
              'label' => __( 'Compassion', 'drolung-branch' ),
              'body'  => __( 'Reconnaître la peine des autres comme sienne, et y répondre par l\'action.', 'drolung-branch' ),
              'delay' => 0,
          ],
          2 => [
              'label' => __( 'Humilité', 'drolung-branch' ),
              'body'  => __( 'Écouter avant de parler, apprendre avant de proposer.', 'drolung-branch' ),
              'delay' => 0.08,
          ],
          3 => [
              'label' => __( 'Transmission', 'drolung-branch' ),
              'body'  => __( 'Faire passer les savoirs et les responsabilités, sans rien retenir pour soi.', 'drolung-branch' ),
              'delay' => 0.16,
          ],
          4 => [
              'label' => __( 'Interdépendance', 'drolung-branch' ),
              'body'  => __( 'Aucun bien-être n\'est isolé : nos vies sont liées, nos actions le rappellent.', 'drolung-branch' ),
              'delay' => 0.24,
          ],
      ];
      foreach ( $valeur_defaults as $i => $v ) :
          $label = drolung_field( "valeur_{$i}_label", $v['label'] );
          $body  = drolung_field( "valeur_{$i}_body",  $v['body'] );
          $delay_style = $v['delay'] > 0 ? 'transition-delay:' . $v['delay'] . 's;' : '';
          ?>
          <div class="fade-up" style="text-align:center;padding:0 8px;<?php echo esc_attr( $delay_style ); ?>">
            <div style="font-family:var(--font-serif);font-style:italic;font-size:24px;color:var(--saffron-lt);margin-bottom:12px;line-height:1.2;"><?php echo esc_html( $label ); ?></div>
            <p style="font-size:14px;color:rgba(255,255,255,0.65);line-height:1.6;margin:0;"><?php echo esc_html( $body ); ?></p>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Drupon Khen Rinpoche — maroon feature section -->
<section class="inner-section inner-section--maroon">
  <div class="container">
    <div style="display:grid;grid-template-columns:240px 1fr;gap:64px;align-items:center;max-width:900px;margin:0 auto;" class="fade-up">
      <div style="text-align:center;">
        <div style="width:140px;height:140px;border-radius:50%;overflow:hidden;margin:0 auto 20px;border:2px solid rgba(255,255,255,0.25);">
          <img src="<?php echo esc_url( drolung_field( 'rinpoche_photo', get_template_directory_uri() . '/assets/images/dkr.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Drupon Khen Rinpoche Karma Lhabu', 'drolung-branch' ); ?>" style="width:100%;height:100%;object-fit:cover;object-position:center top;">
        </div>
        <div style="font-family:var(--font-serif);font-size:1rem;font-weight:600;color:var(--white);line-height:1.4;"><?php echo esc_html( drolung_field( 'rinpoche_name', __( 'Drupon Khen Rinpoche', 'drolung-branch' ) ) ); ?><br><em style="font-size:0.88rem;font-weight:400;color:rgba(255,255,255,0.6);"><?php echo esc_html( drolung_field( 'rinpoche_sub_name', __( 'Karma Lhabu', 'drolung-branch' ) ) ); ?></em></div>
      </div>
      <div>
        <div class="section-eyebrow" style="color:var(--saffron-lt)"><?php echo esc_html( drolung_field( 'rinpoche_eyebrow', __( 'Le fondateur du réseau Drolung', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title" style="color:var(--white)"><?php echo wp_kses_post( drolung_field( 'rinpoche_title', __( 'L\'inspiration <em>à l\'origine de tout</em>', 'drolung-branch' ) ) ); ?></h2>
        <?php echo wp_kses_post( drolung_field( 'rinpoche_body',
          '<p class="section-body" style="color:rgba(255,255,255,0.72)">' . __( 'Né au Tibet, Drupon Khen Rinpoche Karma Lhabu a reçu sa formation à l\'ermitage de Drolung — lieu sacré de la lignée Kagyüpa dont le réseau tire son nom. Sous la direction de Khenchen Thrangu Rinpoche, il est devenu drupon (maître de retraite) et dirige depuis 2004 le centre de retraite de Thrangu Sekhar, au Népal.', 'drolung-branch' ) . '</p>'
          . '<p class="section-body" style="color:rgba(255,255,255,0.72);margin-top:16px">' . __( 'En 2024, il a fondé la Drolung Fondation Bouddhiste Internationale et entrepris la construction d\'un monastère pour les moines qu\'il ordonne. C\'est son enseignement — que la compassion doit se traduire en actes concrets au service des plus vulnérables — qui inspire la création des associations Drolung Solidarité.', 'drolung-branch' ) . '</p>'
        ) ); ?>
        <a href="<?php echo esc_url( drolung_field( 'rinpoche_url', 'https://www.druponrinpoche.org' ) ); ?>" target="_blank" rel="noopener" class="btn-text" style="margin-top:28px;color:var(--saffron-lt);border-color:var(--saffron);"><?php echo esc_html( drolung_field( 'rinpoche_link_label', 'druponrinpoche.org ↗' ) ); ?></a>
      </div>
    </div>
  </div>
</section>

<!-- Citation de Drupon Khen Rinpoche -->
<section class="quote-section fade-up">
  <div class="quote-block">
    <span class="quote-mark" aria-hidden="true">«</span>
    <p class="quote-text"><?php echo esc_html( drolung_field( 'quote_text', __( 'Tous les êtres, sans exception, aspirent au bonheur et cherchent à s\'éloigner de la souffrance. La vie est précieuse — et tout ce qui la soutient l\'est également : la santé, la longévité, le bien-être. C\'est pour aider à créer les conditions du bonheur, pour restaurer et protéger la vie, qu\'une organisation caritative est fondée.', 'drolung-branch' ) ) ); ?></p>
    <div class="quote-attr"><?php echo esc_html( drolung_field( 'quote_author', __( 'Drupon Khen Rinpoche', 'drolung-branch' ) ) ); ?><em><?php echo esc_html( drolung_field( 'quote_author_sub', __( 'Karma Lhabu — fondateur du réseau Drolung', 'drolung-branch' ) ) ); ?></em></div>
  </div>
</section>

<!-- Le bureau — horizontal member list -->
<section class="inner-section inner-section--tint">
  <div class="container">
    <div class="section-header fade-up" style="max-width:680px;margin-bottom:48px;">
      <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'bureau_eyebrow', __( 'Le bureau', 'drolung-branch' ) ) ); ?></div>
      <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'bureau_title', __( 'Notre <em>équipe</em>', 'drolung-branch' ) ) ); ?></h2>
      <p class="section-body"><?php echo esc_html( drolung_field( 'bureau_intro', __( 'Trois membres bénévoles, représentant la diversité européenne du réseau et l\'ancrage franco-malgache du projet de terrain.', 'drolung-branch' ) ) ); ?></p>
    </div>
    <div class="member-list">
      <?php
      $member_defaults = [
          1 => [
              'role'   => __( 'Présidente', 'drolung-branch' ),
              'name'   => 'Petra Hoelscher',
              'bio'    => __( 'Docteure et experte en développement international, vingt années d\'expérience professionnelle, notamment à l\'UNICEF et dans la recherche académique. Ordonnée nonne dans la tradition bouddhiste tibétaine en 2018, elle vit aujourd\'hui au Népal.', 'drolung-branch' ),
              'photo'  => '',
              'alt'    => 'Petra Hoelscher',
              'delay'  => 0,
          ],
          2 => [
              'role'   => __( 'Vice-Président', 'drolung-branch' ),
              'name'   => 'Rija Ratinahirana',
              'bio'    => __( 'Franco-malgache, titulaire d\'un master en informatique. Sa pratique du bouddhisme tibétain et ses origines malgaches ont forgé sa conviction qu\'un développement juste vient de l\'intérieur des communautés.', 'drolung-branch' ),
              'photo'  => '',
              'alt'    => 'Rija Ratinahirana',
              'delay'  => 0.05,
          ],
          3 => [
              'role'   => __( 'Trésorière', 'drolung-branch' ),
              'name'   => 'Barbara Stuetz',
              'bio'    => __( 'Diplômée en Architecture du Paysage, post-master en systèmes alimentaires mondiaux. A travaillé en Belgique, Écosse et Autriche sur des questions d\'agriculture durable. Pratiquante bouddhiste depuis 2015.', 'drolung-branch' ),
              'photo'  => '',
              'alt'    => 'Barbara Stuetz',
              'delay'  => 0.10,
          ],
      ];
      foreach ( $member_defaults as $i => $m ) :
          $role  = drolung_field( "member_{$i}_role",  $m['role'] );
          $name  = drolung_field( "member_{$i}_name",  $m['name'] );
          $bio   = drolung_field( "member_{$i}_bio",   $m['bio'] );
          $photo = drolung_field( "member_{$i}_photo", $m['photo'] );
          $delay_style = $m['delay'] > 0 ? 'transition-delay:' . $m['delay'] . 's' : '';
          // Build initials from name for fallback avatar
          $parts    = explode( ' ', $name );
          $initials = '';
          foreach ( $parts as $part ) {
              $initials .= mb_substr( $part, 0, 1 );
          }
          $initials = mb_strtoupper( mb_substr( $initials, 0, 2 ) );
          ?>
          <div class="member-row fade-up"<?php echo $delay_style ? ' style="' . esc_attr( $delay_style ) . '"' : ''; ?>>
            <div class="member-avatar<?php echo $photo ? '' : ' member-avatar--initials'; ?>">
              <?php if ( $photo ) : ?>
                <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
              <?php else : ?>
                <span><?php echo esc_html( $initials ); ?></span>
              <?php endif; ?>
            </div>
            <div class="member-info">
              <span class="member-role"><?php echo esc_html( $role ); ?></span>
              <div class="member-name"><?php echo esc_html( $name ); ?></div>
              <p class="member-bio"><?php echo esc_html( $bio ); ?></p>
            </div>
          </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Le réseau Drolung — two-col outro -->
<section class="inner-section">
  <div class="container">
    <div class="two-col fade-up">
      <img src="<?php echo esc_url( drolung_field( 'reseau_image', 'https://images.unsplash.com/photo-1627900355526-f77d70cc6887?auto=format&fit=crop&q=80&w=700&h=500' ) ); ?>" alt="<?php esc_attr_e( 'Réseau Drolung', 'drolung-branch' ); ?>" class="img-full" loading="lazy">
      <div>
        <div class="section-eyebrow"><?php echo esc_html( drolung_field( 'reseau_eyebrow', __( 'Le réseau Drolung', 'drolung-branch' ) ) ); ?></div>
        <h2 class="section-title"><?php echo wp_kses_post( drolung_field( 'reseau_title', __( 'Une famille <em>internationale</em>', 'drolung-branch' ) ) ); ?></h2>
        <?php echo wp_kses_post( drolung_field( 'reseau_body',
          '<p class="section-body">' . __( 'Drolung est un réseau international d\'organisations indépendantes partageant un même héritage spirituel et un même engagement humanitaire. À ses côtés, on trouve Drolung UK, Drolung Nepal, Drolung Hong Kong et plusieurs autres entités sœurs, présentes dans plus de vingt pays.', 'drolung-branch' ) . '</p>'
          . '<p class="section-body" style="margin-top:16px">' . __( 'DSM et DSF sont les deux entités franco-malgaches du réseau. Autonomes dans leur gouvernance et leurs actions, elles restent reliées au reste de la famille Drolung par les valeurs partagées, le partage d\'expérience et l\'entraide.', 'drolung-branch' ) . '</p>'
        ) ); ?>
      </div>
    </div>
  </div>
</section>

<?php get_footer();
