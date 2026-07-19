<?php
/**
 * Template Name: Don (debug iframe AssoConnect)
 *
 * Page cachée (non liée dans les menus, non indexée) — reprend l'intégration
 * en iframe du formulaire AssoConnect telle qu'on l'avait faite (voir
 * `git show 625a8d7:.../page-s-engager.php`), pour permettre au support
 * AssoConnect d'inspecter directement le bug d'iframe signalé (Adyen /
 * crypto.subtle / CSP frame-ancestors — voir docs/tech-network.md §15,
 * session 2026-07-13/14). La page `/s-engager/` en production reste sur le
 * bouton de redirection ; ne pas la modifier suite à cette page de debug.
 *
 * @package drolung-branch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', function () {
	echo '<meta name="robots" content="noindex,nofollow">' . "\n";
} );

$asc_collect_id = drolung_field( 'engager_assoconnect_id', '' );
if ( ! $asc_collect_id && function_exists( 'drolung_current_branch' ) && drolung_current_branch() === 'dsf' ) {
	$asc_collect_id = '01KVYT98B8F32ER528VJ6CFMPG';
}
if ( $asc_collect_id ) {
	wp_enqueue_script(
		'assoconnect-iframe',
		'https://drolung-solidarite-france.assoconnect.com/public/build/js/iframe.js',
		array(),
		null,
		true /* footer */
	);
}

get_header();
?>

<section class="inner-section">
  <div class="container">

    <div style="max-width:600px;margin:0 auto 32px;padding:16px 20px;background:var(--cream-dark);border:1px solid var(--border);border-radius:2px;font-size:14px;color:var(--text-muted)">
      Page de test interne — intégration du formulaire de don AssoConnect en iframe, pour reproduire et diagnostiquer le bug signalé (le formulaire de paiement ne se charge pas correctement en iframe). Cette page n'est pas liée dans la navigation du site.
    </div>

    <?php if ( $asc_collect_id ) : ?>
      <div class="asc-don-embed fade-up" style="max-width:600px;margin:0 auto;">
        <div class="iframe-asc-container" data-type="collect" data-collect-id="<?php echo esc_attr( $asc_collect_id ); ?>"></div>
      </div>
    <?php else : ?>
      <p style="text-align:center;color:var(--text-muted)">Aucun identifiant de formulaire AssoConnect configuré (champ ACF <code>engager_assoconnect_id</code>).</p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
