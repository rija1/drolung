<?php
/**
 * front-page.php — branch landing page, "Terrain" design.
 *
 * Mirrors mockup-dsf-2/index.html. Reuses the same ACF field keys as
 * drolung-branch (hero_*, chiffre_*, intro_*, donate_*, newsletter_*) so
 * seeded content carries over when switching themes; defaults hold the
 * shorter mockup-dsf-2 copy.
 *
 * Sections: hero · chiffres (3) · qui nous sommes · 4 domaines ·
 *           projets (3 cartes) · bandeau engagement · don · newsletter
 *
 * @package drolung-branch-2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$donate_url = apply_filters( 'drolung_donate_url', home_url( '/s-engager/' ) );
?>

<!-- HERO -->
<section class="hero">
	<div class="hero__grid">
		<div class="hero__text">
			<h1 class="hero__title"><?php echo wp_kses_post( drolung_field( 'hero_title', __( 'Agir avec <strong>Madagascar</strong>, depuis la France.', 'drolung-branch-2' ) ) ); ?></h1>
			<p class="hero__sub"><?php echo esc_html( drolung_field( 'hero_sub', __( 'Eau potable, éducation, agriculture : nous finançons des projets concrets, portés sur le terrain par Drolung Solidarité Madagascar.', 'drolung-branch-2' ) ) ); ?></p>
			<div class="hero__actions">
				<a href="<?php echo esc_url( $donate_url ); ?>" class="btn btn--accent"><?php echo esc_html( drolung_field( 'hero_cta1_label', __( 'Faire un don', 'drolung-branch-2' ) ) ); ?></a>
				<a href="<?php echo esc_url( drolung_field( 'hero_cta2_url', home_url( '/projets/' ) ) ); ?>" class="btn btn--outline"><?php echo esc_html( drolung_field( 'hero_cta2_label', __( 'Voir les projets', 'drolung-branch-2' ) ) ); ?></a>
			</div>
		</div>
		<div class="hero__visual">
			<img src="<?php echo esc_url( drolung_field( 'hero_image', 'https://images.unsplash.com/photo-1504598578017-40d9b776f1bc?auto=format&fit=crop&q=80&w=1000&h=1200' ) ); ?>" alt="">
		</div>
	</div>
</section>

<!-- CHIFFRES (3 seulement — design condensé) -->
<section class="stats">
	<div class="container">
		<div class="stats__grid">
			<?php
			$chiffres = [
				[ 'num' => drolung_field( 'chiffre_1_num', '80 %' ),  'label' => drolung_field( 'chiffre_1_label', __( 'de la population malgache vit sous le seuil de pauvreté', 'drolung-branch-2' ) ) ],
				[ 'num' => drolung_field( 'chiffre_2_num', '44 %' ),  'label' => drolung_field( 'chiffre_2_label', __( 'n\'a pas accès à une eau potable améliorée', 'drolung-branch-2' ) ) ],
				[ 'num' => drolung_field( 'chiffre_6_num', '1/16' ), 'label' => drolung_field( 'chiffre_6_label', __( 'enfants ne survit pas jusqu\'à ses 5 ans', 'drolung-branch-2' ) ) ],
			];
			foreach ( $chiffres as $chiffre ) : ?>
				<div class="stat">
					<div class="stat__num"><?php echo wp_kses_post( $chiffre['num'] ); ?></div>
					<div class="stat__label"><?php echo esc_html( $chiffre['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="stats__note"><?php echo esc_html( drolung_field( 'chiffres_cta', __( 'C\'est cette réalité que nos projets veulent changer, durablement.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<!-- QUI NOUS SOMMES -->
<section class="section">
	<div class="container">
		<div class="intro__grid">
			<img src="<?php echo esc_url( drolung_field( 'intro_image', 'https://images.unsplash.com/photo-1585335740523-85d0d6dfbf27?auto=format&fit=crop&q=80&w=900&h=680' ) ); ?>" alt="" loading="lazy">
			<div>
				<span class="section__eyebrow"><?php echo esc_html( drolung_field( 'intro_eyebrow', __( 'Qui nous sommes', 'drolung-branch-2' ) ) ); ?></span>
				<h2 class="section__title"><?php echo wp_kses_post( drolung_field( 'intro_title', __( 'Un pont entre la France et Madagascar', 'drolung-branch-2' ) ) ); ?></h2>
				<div class="intro__body"><?php echo wp_kses_post( drolung_field( 'intro_body', '<p>' . __( 'Association loi 1901, entièrement bénévole. Nous collectons en France les moyens qui permettent à notre association sœur d\'agir à Madagascar. 100 % des dons vont aux projets.', 'drolung-branch-2' ) . '</p>' ) ); ?></div>
				<a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>" class="link-more"><?php echo esc_html( drolung_field( 'intro_cta_label', __( 'Notre histoire →', 'drolung-branch-2' ) ) ); ?></a>
			</div>
		</div>
	</div>
</section>

<!-- NOTRE ACTION — 4 domaines -->
<?php
$notre_action_page = get_page_by_path( 'notre-action' );
$notre_action_id   = $notre_action_page ? $notre_action_page->ID : 0;

$axes_defaults = [
	1 => [ 'title' => __( 'Eau', 'drolung-branch-2' ),         'desc' => __( 'Financer forages et infrastructures sanitaires là où ils manquent le plus.', 'drolung-branch-2' ) ],
	2 => [ 'title' => __( 'Éducation', 'drolung-branch-2' ),   'desc' => __( 'Permettre aux enfants d\'aller à l\'école et accompagner les jeunes.', 'drolung-branch-2' ) ],
	3 => [ 'title' => __( 'Santé', 'drolung-branch-2' ),       'desc' => __( 'Soutenir l\'accès aux soins de base et la santé maternelle et infantile.', 'drolung-branch-2' ) ],
	4 => [ 'title' => __( 'Agriculture', 'drolung-branch-2' ), 'desc' => __( 'Appuyer l\'agriculture vivrière, les coopératives et la permaculture.', 'drolung-branch-2' ) ],
];
?>
<section class="section section--warm">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php esc_html_e( 'Notre action', 'drolung-branch-2' ); ?></span>
				<h2 class="section__title"><?php esc_html_e( 'Quatre domaines d\'intervention', 'drolung-branch-2' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>" class="link-more"><?php esc_html_e( 'Voir tout →', 'drolung-branch-2' ); ?></a>
		</div>
		<div class="actions-grid">
			<?php foreach ( $axes_defaults as $i => $axe ) :
				$tag  = ( $notre_action_id && function_exists( 'get_field' ) ) ? get_field( "axe_{$i}_tag", $notre_action_id ) : '';
				$desc = ( $notre_action_id && function_exists( 'get_field' ) ) ? get_field( "axe_{$i}_body", $notre_action_id ) : '';
				$title = $tag ?: $axe['title'];
				$desc  = $desc ?: $axe['desc'];
				?>
				<div class="action-card">
					<div class="action-card__title"><?php echo esc_html( $title ); ?></div>
					<p class="action-card__desc"><?php echo esc_html( wp_strip_all_tags( wp_trim_words( $desc, 20, '…' ) ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- PROJETS — 3 cartes depuis le réseau -->
<section class="section">
	<div class="container">
		<div class="section__head">
			<div>
				<span class="section__eyebrow"><?php echo esc_html( drolung_field( 'map_eyebrow', __( 'Nos projets', 'drolung-branch-2' ) ) ); ?></span>
				<h2 class="section__title"><?php esc_html_e( 'Sur le terrain, en ce moment', 'drolung-branch-2' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/projets/' ) ); ?>" class="link-more"><?php esc_html_e( 'Tous les projets →', 'drolung-branch-2' ); ?></a>
		</div>
		<?php
		$items = function_exists( 'drolung_get_projets' ) ? array_slice( (array) drolung_get_projets(), 0, 3 ) : [];
		if ( $items ) : ?>
			<div class="projects-grid">
				<?php foreach ( $items as $item ) :
					$type_slugs = array_keys( $item['types'] );
					$type_name  = $type_slugs ? ( $item['types'][ $type_slugs[0] ] ?? '' ) : '';
					$thumb_url  = $item['thumbnail']['large'] ?? '';
					$permalink  = home_url( '/projets/' . $item['slug'] . '/' );
					?>
					<a href="<?php echo esc_url( $permalink ); ?>" class="project-card">
						<?php if ( $thumb_url ) : ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy">
						<?php endif; ?>
						<div class="project-card__body">
							<?php if ( $type_name ) : ?>
								<div class="project-card__tag"><?php echo esc_html( $type_name ); ?></div>
							<?php endif; ?>
							<div class="project-card__title"><?php echo esc_html( $item['title'] ); ?></div>
							<?php if ( ! empty( $item['excerpt'] ) ) : ?>
								<p class="project-card__desc"><?php echo esc_html( wp_trim_words( $item['excerpt'], 18, '…' ) ); ?></p>
							<?php endif; ?>
							<span class="link-more"><?php esc_html_e( 'Découvrir →', 'drolung-branch-2' ); ?></span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="prose"><?php esc_html_e( 'Les projets seront bientôt présentés ici.', 'drolung-branch-2' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<!-- ENGAGEMENT -->
<section class="pledge">
	<div class="container">
		<h2 class="pledge__title"><?php echo wp_kses_post( drolung_field( 'pledge_title', __( '<strong>100 %</strong> des dons vont au terrain.', 'drolung-branch-2' ) ) ); ?></h2>
		<p class="pledge__sub"><?php echo esc_html( drolung_field( 'pledge_sub', __( 'Équipe bénévole, aucun frais de structure, comptes publics. Un lien direct entre votre don et l\'action à Madagascar.', 'drolung-branch-2' ) ) ); ?></p>
	</div>
</section>

<!-- DON -->
<?php
drolung_branch2_donate_band(
	drolung_field( 'donate_title_plain', __( 'Votre don agit directement.', 'drolung-branch-2' ) ),
	drolung_field( 'donate_body_short', __( '140 € financent une session de l\'École des Femmes. 11 000 €, un forage pour 1 300 personnes. Chaque euro compte.', 'drolung-branch-2' ) )
);
?>

<!-- NEWSLETTER -->
<section class="newsletter">
	<div class="container">
		<div class="newsletter__inner">
			<div>
				<div class="newsletter__title"><?php echo esc_html( drolung_field( 'newsletter_title', __( 'Suivez nos avancées', 'drolung-branch-2' ) ) ); ?></div>
				<p class="newsletter__sub"><?php echo esc_html( drolung_field( 'newsletter_body', __( 'Une lettre courte, quand il y a du nouveau.', 'drolung-branch-2' ) ) ); ?></p>
			</div>
			<form class="newsletter__form" action="#" method="post" novalidate>
				<label class="screen-reader-text" for="nl-email-branch2"><?php esc_html_e( 'Adresse e-mail', 'drolung-branch-2' ); ?></label>
				<input id="nl-email-branch2" type="email" name="nl_email" placeholder="<?php esc_attr_e( 'Votre adresse e-mail', 'drolung-branch-2' ); ?>" required>
				<button type="submit" class="btn btn--ink"><?php esc_html_e( 'Je m\'inscris', 'drolung-branch-2' ); ?></button>
			</form>
		</div>
	</div>
</section>

<?php
get_footer();
