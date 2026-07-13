<?php
/**
 * Page « Notre action » — "Terrain" design.
 * Mirrors mockup-dsf-2/what-we-do.html. Reuses drolung-branch axe_* ACF keys.
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
		<span class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'na_hero_eyebrow', __( 'Notre action', 'drolung-branch-2' ) ) ); ?></span>
		<h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'na_hero_title', __( 'Soutenir, depuis la France, l\'action <strong>sur le terrain</strong>.', 'drolung-branch-2' ) ) ); ?></h1>
		<p class="page-hero__sub"><?php echo esc_html( drolung_field( 'na_hero_sub', __( 'Nous ne menons pas les projets nous-mêmes : nous donnons à Drolung Solidarité Madagascar les moyens de les conduire, au plus près des communautés.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="intro__grid">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Notre rôle', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php echo wp_kses_post( drolung_field( 'na_role_title', __( 'Collecter ici, agir là-bas', 'drolung-branch-2' ) ) ); ?></h2>
				<div class="intro__body"><?php echo wp_kses_post( drolung_field( 'na_role_body', '<p>' . __( 'L\'intégralité des fonds collectés est destinée aux projets portés par notre association sœur. À chaque don correspond une action concrète et identifiée.', 'drolung-branch-2' ) . '</p>' ) ); ?></div>
				<a href="<?php echo esc_url( home_url( '/projets/' ) ); ?>" class="link-more"><?php esc_html_e( 'Voir les projets financés →', 'drolung-branch-2' ); ?></a>
			</div>
			<img src="<?php echo esc_url( drolung_field( 'na_role_image', 'https://images.unsplash.com/photo-1504598578017-40d9b776f1bc?auto=format&fit=crop&q=80&w=900&h=680' ) ); ?>" alt="" loading="lazy">
		</div>
	</div>
</section>

<section class="section section--warm">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Quatre domaines', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php esc_html_e( 'Inséparables, et qui se renforcent', 'drolung-branch-2' ); ?></h2>
			</div>
		</div>
		<div class="actions-grid actions-grid--two">
			<?php
			$axes_defaults = [
				1 => [ 'tag' => __( 'Eau & assainissement', 'drolung-branch-2' ), 'body' => __( 'Financer l\'accès à l\'eau potable et aux infrastructures sanitaires là où ils manquent le plus. Sans eau, rien d\'autre n\'est possible.', 'drolung-branch-2' ) ],
				2 => [ 'tag' => __( 'Éducation', 'drolung-branch-2' ), 'body' => __( 'Donner aux enfants les moyens d\'aller à l\'école, accompagner les jeunes, soutenir les passeurs de savoirs locaux.', 'drolung-branch-2' ) ],
				3 => [ 'tag' => __( 'Santé', 'drolung-branch-2' ), 'body' => __( 'Soutenir l\'accès aux soins de base, les structures de santé locales et la santé maternelle et infantile.', 'drolung-branch-2' ) ],
				4 => [ 'tag' => __( 'Environnement & agriculture', 'drolung-branch-2' ), 'body' => __( 'Appuyer l\'agriculture vivrière, les coopératives et artisans, préserver les écosystèmes. Prospérer chez soi vaut mieux que devoir partir.', 'drolung-branch-2' ) ],
			];
			foreach ( $axes_defaults as $i => $axe ) :
				$tag  = drolung_field( "axe_{$i}_tag", $axe['tag'] );
				$body = drolung_field( "axe_{$i}_body", $axe['body'] );
				?>
				<div class="action-card">
					<div class="action-card__title"><?php echo esc_html( $tag ); ?></div>
					<p class="action-card__desc"><?php echo esc_html( wp_strip_all_tags( $body ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Nos engagements', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php esc_html_e( 'Ce que nous garantissons', 'drolung-branch-2' ); ?></h2>
			</div>
		</div>
		<div class="values-grid">
			<?php
			$engagements = [
				1 => [ 'label' => __( '100 % vers le terrain', 'drolung-branch-2' ), 'body' => __( 'L\'intégralité des fonds collectés est destinée aux projets. Aucun frais de structure prélevé sur les dons.', 'drolung-branch-2' ) ],
				2 => [ 'label' => __( 'Équipe bénévole', 'drolung-branch-2' ), 'body' => __( 'Le bureau et tous les contributeurs réguliers travaillent sans rémunération.', 'drolung-branch-2' ) ],
				3 => [ 'label' => __( 'Transparence intégrale', 'drolung-branch-2' ), 'body' => __( 'Chaque euro engagé est suivi, documenté et rendu public dans nos comptes annuels.', 'drolung-branch-2' ) ],
				4 => [ 'label' => __( 'Un lien direct', 'drolung-branch-2' ), 'body' => __( 'Pas d\'intermédiaire entre le don en France et l\'action à Madagascar.', 'drolung-branch-2' ) ],
			];
			foreach ( $engagements as $i => $eng ) : ?>
				<div class="value-item">
					<div class="value-item__title"><?php echo esc_html( drolung_field( "engagement_{$i}_label", $eng['label'] ) ); ?></div>
					<p class="value-item__desc"><?php echo esc_html( drolung_field( "engagement_{$i}_body", $eng['body'] ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
drolung_branch2_donate_band(
	__( 'Chaque euro compte.', 'drolung-branch-2' ),
	__( 'Votre don finance directement l\'un de ces quatre domaines, sans intermédiaire.', 'drolung-branch-2' )
);

get_footer();
