<?php
/**
 * Page « S'engager » — "Terrain" design.
 * Mirrors mockup-dsf-2/get-involved.html (don, partage, entreprises).
 *
 * @package drolung-branch-2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="page-hero">
	<div class="container">
		<span class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'se_hero_eyebrow', __( 'S\'engager', 'drolung-branch-2' ) ) ); ?></span>
		<h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'se_hero_title', __( 'Chaque geste <strong>compte</strong>.', 'drolung-branch-2' ) ) ); ?></h1>
		<p class="page-hero__sub"><?php echo esc_html( drolung_field( 'se_hero_sub', __( 'Un don, un partenariat, un partage — trois façons de faire avancer les projets à Madagascar.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<section class="section" id="don">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Faire un don', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php echo wp_kses_post( drolung_field( 'se_don_title', __( 'Votre don agit directement', 'drolung-branch-2' ) ) ); ?></h2>
			</div>
		</div>
		<p class="prose" style="margin-bottom:40px"><?php echo esc_html( drolung_field( 'se_don_body', __( 'Chaque euro collecté va intégralement aux projets portés par Drolung Solidarité Madagascar. Aucun frais de structure prélevé sur les dons.', 'drolung-branch-2' ) ) ); ?></p>
		<div class="amounts-grid">
			<?php
			$montants = [
				1 => [ 'num' => __( '140 €', 'drolung-branch-2' ),    'label' => __( 'une session mensuelle de l\'École des Femmes, pour 50 à 100 participantes', 'drolung-branch-2' ) ],
				2 => [ 'num' => __( '365 €', 'drolung-branch-2' ),    'label' => __( 'un mois de formation et de suivi pour une famille de la forêt comestible', 'drolung-branch-2' ) ],
				3 => [ 'num' => __( '11 000 €', 'drolung-branch-2' ), 'label' => __( 'un captage de source gravitaire desservant 1 300 personnes en eau potable', 'drolung-branch-2' ) ],
			];
			foreach ( $montants as $i => $montant ) : ?>
				<div class="amount-card">
					<div class="amount-card__num"><?php echo esc_html( drolung_field( "don_exemple_{$i}_montant", $montant['num'] ) ); ?></div>
					<p class="amount-card__label"><?php echo esc_html( drolung_field( "don_exemple_{$i}_desc", $montant['label'] ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<p style="margin-top:32px;font-size:15px;color:var(--ink-soft)">
			<?php echo esc_html( drolung_field( 'se_don_note', __( 'Notre système de paiement en ligne sera disponible prochainement. En attendant, contactez-nous pour contribuer dès maintenant.', 'drolung-branch-2' ) ) ); ?>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="link-more"><?php esc_html_e( 'Nous contacter →', 'drolung-branch-2' ); ?></a>
		</p>
	</div>
</section>

<section class="section section--warm">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Autrement', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php esc_html_e( 'Deux autres façons d\'aider', 'drolung-branch-2' ); ?></h2>
			</div>
		</div>
		<div class="actions-grid actions-grid--two">
			<div class="action-card">
				<div class="action-card__title"><?php echo esc_html( drolung_field( 'se_partage_title', __( 'Parlez de nous', 'drolung-branch-2' ) ) ); ?></div>
				<p class="action-card__desc"><?php echo esc_html( drolung_field( 'se_partage_body', __( 'Le plus simple des engagements, et l\'un des plus puissants : mentionner l\'association autour de vous, partager nos publications, relayer nos projets.', 'drolung-branch-2' ) ) ); ?></p>
			</div>
			<div class="action-card">
				<div class="action-card__title"><?php echo esc_html( drolung_field( 'se_entreprises_title', __( 'Entreprises & fondations', 'drolung-branch-2' ) ) ); ?></div>
				<p class="action-card__desc"><?php echo esc_html( drolung_field( 'se_entreprises_body', __( 'Mécénat, compétences (santé, agronomie, éducation, logistique) ou financement direct d\'un projet identifié, avec rapport de suivi dédié.', 'drolung-branch-2' ) ) ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="pledge">
	<div class="container">
		<h2 class="pledge__title"><?php echo wp_kses_post( drolung_field( 'pledge_title', __( '<strong>100 %</strong> des dons vont au terrain.', 'drolung-branch-2' ) ) ); ?></h2>
		<p class="pledge__sub"><?php echo esc_html( drolung_field( 'pledge_sub_short', __( 'Équipe bénévole, aucun frais de structure, comptes publics.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<?php
drolung_branch2_donate_band(
	__( 'Prêt·e à contribuer ?', 'drolung-branch-2' ),
	__( 'Écrivez-nous — nous vous répondons sous 48 h.', 'drolung-branch-2' ),
	__( 'Nous contacter', 'drolung-branch-2' ),
	home_url( '/contact/' )
);

get_footer();
