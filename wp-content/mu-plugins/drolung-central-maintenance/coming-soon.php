<?php
/**
 * Standalone "coming soon" page — no theme/header/footer dependency,
 * `include`d mid-request by 10-drolung-central-maintenance.php then the
 * request is `exit`ed, so this file is a complete HTML document.
 *
 * @var string $logo_url Set by the includer.
 *
 * @package drolung-network
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Drolung — Site à venir</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --saffron:      #C17D0A;
    --saffron-lt:   #E09B20;
    --maroon:       #5C1A1A;
    --maroon-lt:    #7A2020;
    --cream:        #FBF7F0;
    --charcoal:     #1A1916;
    --font-serif:   'Playfair Display', Georgia, serif;
    --font-body:    'DM Sans', -apple-system, sans-serif;
    --font-mono:    'DM Mono', monospace;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body {
    height: 100%;
  }
  body {
    font-family: var(--font-body);
    color: var(--cream);
    background:
      radial-gradient(ellipse 900px 600px at 50% -10%, rgba(193,125,10,0.20), transparent 60%),
      linear-gradient(160deg, var(--charcoal) 0%, #241f19 55%, var(--maroon) 140%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px;
    text-align: center;
  }
  .wrap {
    max-width: 560px;
  }
  .logo {
    width: 64px;
    height: 64px;
    object-fit: contain;
    margin: 0 auto 28px;
    opacity: 0.95;
  }
  .wordmark {
    font-family: var(--font-serif);
    font-weight: 700;
    font-size: clamp(1.6rem, 4vw, 2.1rem);
    letter-spacing: 0.14em;
    color: var(--cream);
  }
  .tag {
    font-family: var(--font-mono);
    font-size: 11px;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    color: var(--saffron-lt);
    margin-top: 8px;
  }
  .rule {
    width: 48px;
    height: 2px;
    margin: 32px auto;
    background: linear-gradient(90deg, var(--maroon-lt), var(--saffron), var(--maroon-lt));
    border-radius: 2px;
  }
  h1 {
    font-family: var(--font-serif);
    font-style: italic;
    font-weight: 600;
    font-size: clamp(1.8rem, 5vw, 2.6rem);
    line-height: 1.25;
    color: var(--cream);
  }
  h1 em {
    color: var(--saffron-lt);
    font-style: italic;
  }
  p.sub {
    margin-top: 18px;
    font-size: 15px;
    line-height: 1.7;
    color: rgba(251,247,240,0.68);
    font-weight: 400;
  }
  p.sub.en {
    margin-top: 10px;
    font-size: 13px;
    color: rgba(251,247,240,0.45);
  }
  .contact {
    margin-top: 40px;
    font-family: var(--font-mono);
    font-size: 12px;
    letter-spacing: 0.06em;
    color: rgba(251,247,240,0.55);
  }
  .contact a {
    color: var(--saffron-lt);
    text-decoration: none;
    border-bottom: 1px solid rgba(224,155,32,0.4);
    transition: border-color 200ms ease;
  }
  .contact a:hover {
    border-color: var(--saffron-lt);
  }
</style>
</head>
<body>
  <div class="wrap">
    <?php if ( ! empty( $logo_url ) ) : ?>
      <img class="logo" src="<?php echo esc_url( $logo_url ); ?>" alt="Drolung" loading="eager">
    <?php endif; ?>
    <div class="wordmark">DROLUNG</div>
    <div class="tag">Global Network</div>

    <div class="rule"></div>

    <h1>Un nouveau site <em>arrive bientôt</em></h1>
    <p class="sub">Le site du réseau Drolung International Foundation est en cours de construction. En attendant, retrouvez Drolung Solidarité France sur <a href="https://solidarite.drolung.fr" style="color:inherit;text-decoration:underline;">solidarite.drolung.fr</a>.</p>
    <p class="sub en">A new site is coming soon. In the meantime, visit Drolung Solidarité France at solidarite.drolung.fr.</p>

    <div class="contact">contact@drolung.org</div>
  </div>
</body>
</html>
