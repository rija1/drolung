<?php
/**
 * Page « À propos » — "Terrain" design.
 * Mirrors mockup-dsf-2/about.html (histoire, valeurs, équipe, réseau).
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
		<span class="page-hero__eyebrow"><?php echo esc_html( drolung_field( 'ap_hero_eyebrow', __( 'L\'association', 'drolung-branch-2' ) ) ); ?></span>
		<h1 class="page-hero__title"><?php echo wp_kses_post( drolung_field( 'ap_hero_title', __( 'Un pont entre <strong>deux pays</strong>.', 'drolung-branch-2' ) ) ); ?></h1>
		<p class="page-hero__sub"><?php echo esc_html( drolung_field( 'ap_hero_sub', __( 'Drolung Solidarité France mobilise depuis la France les ressources nécessaires aux actions menées par notre association sœur à Madagascar.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="intro__grid">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Notre histoire', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php echo wp_kses_post( drolung_field( 'ap_histoire_title', __( 'Deux associations sœurs', 'drolung-branch-2' ) ) ); ?></h2>
				<div class="prose" style="margin-top:20px"><?php echo wp_kses_post( drolung_field( 'ap_histoire_body',
					'<p>' . __( 'En 2025, des membres du réseau Drolung — bouddhistes pratiquants franco-malgaches et leurs proches — créent deux associations sœurs : l\'une en France pour collecter, l\'autre à Madagascar pour agir.', 'drolung-branch-2' ) . '</p>' .
					'<p>' . __( 'Le constat est simple : le soutien offert depuis la France a besoin d\'un cadre clair et transparent ; l\'action à Madagascar a besoin d\'un ancrage local.', 'drolung-branch-2' ) . '</p>'
				) ); ?></div>
			</div>
			<img src="<?php echo esc_url( drolung_field( 'ap_histoire_image', 'https://images.unsplash.com/photo-1585335740523-85d0d6dfbf27?auto=format&fit=crop&q=80&w=900&h=680' ) ); ?>" alt="" loading="lazy">
		</div>
	</div>
</section>

<section class="section section--warm">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Nos valeurs', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php esc_html_e( 'Quatre repères', 'drolung-branch-2' ); ?></h2>
			</div>
		</div>
		<div class="values-grid">
			<?php
			$valeurs = [
				1 => [ 'label' => __( 'Compassion', 'drolung-branch-2' ),      'body' => __( 'Reconnaître la peine des autres comme sienne, et y répondre par l\'action.', 'drolung-branch-2' ) ],
				2 => [ 'label' => __( 'Humilité', 'drolung-branch-2' ),        'body' => __( 'Écouter avant de parler, apprendre avant de proposer.', 'drolung-branch-2' ) ],
				3 => [ 'label' => __( 'Transmission', 'drolung-branch-2' ),    'body' => __( 'Faire passer les savoirs et les responsabilités, sans rien retenir pour soi.', 'drolung-branch-2' ) ],
				4 => [ 'label' => __( 'Interdépendance', 'drolung-branch-2' ), 'body' => __( 'Aucun bien-être n\'est isolé : nos vies sont liées, nos actions le rappellent.', 'drolung-branch-2' ) ],
			];
			foreach ( $valeurs as $i => $val ) : ?>
				<div class="value-item">
					<div class="value-item__title"><?php echo esc_html( drolung_field( "valeur_{$i}_label", $val['label'] ) ); ?></div>
					<p class="value-item__desc"><?php echo esc_html( drolung_field( "valeur_{$i}_body", $val['body'] ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Le bureau', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php echo esc_html( drolung_field( 'ap_equipe_title', __( 'Trois membres bénévoles', 'drolung-branch-2' ) ) ); ?></h2>
			</div>
		</div>
		<div class="team-grid">
			<?php
			$membres = [
				1 => [
					'name'  => __( 'Petra Hoelscher', 'drolung-branch-2' ),
					'bio'   => __( 'Docteure et experte en développement international — vingt ans d\'expérience, notamment à l\'UNICEF. Nonne ordonnée dans la tradition tibétaine.', 'drolung-branch-2' ),
				],
				2 => [
					'name'  => __( 'Rija Ratinahirana', 'drolung-branch-2' ),
					'bio'   => __( 'Franco-malgache, master en informatique. Convaincu qu\'un développement juste vient de l\'intérieur des communautés.', 'drolung-branch-2' ),
				],
				3 => [
					'name'  => __( 'Barbara Stuetz', 'drolung-branch-2' ),
					'bio'   => __( 'Architecte paysagiste, post-master en systèmes alimentaires mondiaux. A travaillé en Belgique, en Écosse et en Autriche sur l\'agriculture durable.', 'drolung-branch-2' ),
				],
			];
			foreach ( $membres as $i => $membre ) :
				$photo = drolung_field( "membre_{$i}_photo", '' );
				$name  = drolung_field( "membre_{$i}_name", $membre['name'] );
				$bio   = drolung_field( "membre_{$i}_bio", $membre['bio'] );
				?>
				<div class="team-card">
					<?php if ( $photo ) : ?>
						<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
					<?php endif; ?>
					<div class="team-card__body">
						<div class="team-card__name"><?php echo esc_html( $name ); ?></div>
						<p class="team-card__bio"><?php echo esc_html( $bio ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="pledge">
	<div class="container">
		<h2 class="pledge__title"><?php echo wp_kses_post( drolung_field( 'ap_reseau_title', __( 'Une famille internationale : <strong>le réseau Drolung</strong>.', 'drolung-branch-2' ) ) ); ?></h2>
		<p class="pledge__sub"><?php echo esc_html( drolung_field( 'ap_reseau_body', __( 'Drolung UK, Nepal, Hong Kong… des organisations indépendantes reliées par un même héritage spirituel et un même engagement humanitaire.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<?php
drolung_branch2_donate_band(
	__( 'Rejoignez-nous.', 'drolung-branch-2' ),
	__( 'Un don, un coup de main, un partage — chaque geste fait avancer les projets.', 'drolung-branch-2' ),
	__( 'S\'engager', 'drolung-branch-2' )
);

get_footer();
