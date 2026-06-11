<?php
/**
 * Front page for Drolung UK.
 * Mirrors mockup-duk/index.html. Static text will be moved into ACF in a
 * follow-up so editors can change copy without touching this file.
 *
 * @package drolung-duk
 */

get_header();
?>

<!-- HERO -->
<section class="hero">
	<div class="hero-bg"></div>
	<div class="hero-overlay"></div>
	<div class="hero-saffron-bar"></div>
	<div class="hero-content">
		<div class="hero-eyebrow"><?php esc_html_e( 'Scottish Charitable Incorporated Organisation · SC054814', 'drolung-duk' ); ?></div>
		<h1 class="hero-title"><?php
			/* translators: %s wraps the emphasised word "Buddhism" in <em>. */
			printf( esc_html__( 'Funding the future%sof %s.', 'drolung-duk' ), '<br>', '<em>' . esc_html__( 'Buddhism', 'drolung-duk' ) . '</em>' );
		?></h1>
		<p class="hero-sub"><?php esc_html_e( 'Drolung UK supports the practice and preservation of authentic lineages of Buddhist teaching, alongside education, health and the relief of poverty in the communities we serve.', 'drolung-duk' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/our-work/' ) ); ?>" class="hero-cta"><?php esc_html_e( 'Find out more about us →', 'drolung-duk' ); ?></a>
	</div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
	<div class="stats-bar__inner">
		<div class="stat-item fade-up">
			<div class="stat-num">40</div>
			<div class="stat-label"><?php esc_html_e( 'Young monastics ordained in DRC', 'drolung-duk' ); ?></div>
		</div>
		<div class="stat-item fade-up" style="transition-delay:0.08s">
			<div class="stat-num">34</div>
			<div class="stat-label"><?php esc_html_e( 'Students sponsored in Nepal', 'drolung-duk' ); ?></div>
		</div>
		<div class="stat-item fade-up" style="transition-delay:0.16s">
			<div class="stat-num">97%</div>
			<div class="stat-label"><?php esc_html_e( 'Of donations go to projects', 'drolung-duk' ); ?></div>
		</div>
		<div class="stat-item fade-up" style="transition-delay:0.24s">
			<div class="stat-num">2022</div>
			<div class="stat-label"><?php esc_html_e( 'Founded as BSF, now Drolung UK', 'drolung-duk' ); ?></div>
		</div>
	</div>
</div>

<!-- VISION INTRO -->
<section class="section">
	<div class="container">
		<div class="vision-split">
			<div class="fade-up">
				<div class="section-eyebrow"><?php esc_html_e( 'Who We Are', 'drolung-duk' ); ?></div>
				<h2 class="section-title"><?php
					/* translators: %s wraps "big vision" in <em>. */
					printf( esc_html__( 'A small charity with%sa %s', 'drolung-duk' ), '<br>', '<em>' . esc_html__( 'big vision', 'drolung-duk' ) . '</em>' );
				?></h2>
				<div class="divider"></div>
				<p class="section-body"><?php esc_html_e( 'Based on the west coast of Scotland, we are a group of long-term Buddhist practitioners — originally students of the late Akong Rinpoche — with more than sixty years of retreat between us under the personal guidance of our Lama, Drupon Khen Rinpoche Karma Lhabu.', 'drolung-duk' ); ?></p>
				<p class="section-body" style="margin-top:18px;"><?php esc_html_e( 'Our mission is to advance the understanding and practice of Buddhism while promoting education, health, and the relief of poverty. Our outlook is nonsectarian: we equally respect all genuine Buddhist lineages, whether Theravada, Mahayana or Vajrayana.', 'drolung-duk' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" style="display:inline-block;margin-top:28px;font-size:14px;font-weight:600;color:var(--saffron);letter-spacing:0.04em;"><?php esc_html_e( 'Read our story →', 'drolung-duk' ); ?></a>
			</div>
			<div class="vision-img fade-up" style="transition-delay:0.15s">
				<img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&q=80&w=800&h=880" alt="<?php esc_attr_e( 'Himalayan monastery at dawn', 'drolung-duk' ); ?>">
			</div>
		</div>
	</div>
</section>

<!-- WHAT WE DO — Projects -->
<section class="section section--alt">
	<div class="container">
		<div class="section-eyebrow"><?php esc_html_e( 'What We Do', 'drolung-duk' ); ?></div>
		<h2 class="section-title"><?php
			printf( esc_html__( 'Two projects,%sone %s', 'drolung-duk' ), '<br>', '<em>' . esc_html__( 'shared purpose', 'drolung-duk' ) . '</em>' );
		?></h2>
		<div class="divider"></div>
		<p class="section-body"><?php esc_html_e( 'Drolung UK does not run projects directly. We fund initiatives that share our objectives and ethos, supervised by — or aligned with — the vision of our Lama, Drupon Khen Rinpoche Karma Lhabu.', 'drolung-duk' ); ?></p>
		<div class="project-grid">
			<a href="<?php echo esc_url( home_url( '/our-work/#drc' ) ); ?>" class="project-card fade-up" style="text-decoration:none;color:inherit;">
				<img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=1000&h=560" alt="<?php esc_attr_e( 'Drolung Monastery construction site, DRC', 'drolung-duk' ); ?>" class="project-card__img">
				<div class="project-card__body">
					<span class="project-card__tag"><?php esc_html_e( 'Africa · DR Congo', 'drolung-duk' ); ?></span>
					<div class="project-card__title"><?php esc_html_e( 'Drolung Monastery — DR Congo', 'drolung-duk' ); ?></div>
					<p class="project-card__desc"><?php esc_html_e( 'Supporting the construction of the first Tibetan Buddhist monastery on the African continent, in Lubumbashi. Since 2022 we have funded the perimeter wall, accommodation for the first ordained monks, and study texts in French and Swahili.', 'drolung-duk' ); ?></p>
					<span class="project-card__link"><?php esc_html_e( 'Read more →', 'drolung-duk' ); ?></span>
				</div>
			</a>
			<a href="<?php echo esc_url( home_url( '/our-work/#nepal' ) ); ?>" class="project-card fade-up" style="text-decoration:none;color:inherit;transition-delay:0.08s;">
				<img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&q=80&w=1000&h=560" alt="<?php esc_attr_e( 'Students at Shree Saraswati Secondary School, Nepal', 'drolung-duk' ); ?>" class="project-card__img">
				<div class="project-card__body">
					<span class="project-card__tag"><?php esc_html_e( 'Asia · Nepal', 'drolung-duk' ); ?></span>
					<div class="project-card__title"><?php esc_html_e( 'Shree Saraswati Secondary School — Nepal', 'drolung-duk' ); ?></div>
					<p class="project-card__desc"><?php esc_html_e( 'Sponsoring school fees for 34 children from low-income families in Sudal, on the rim of the Kathmandu valley. The school welcomes some 400 students across primary and secondary levels.', 'drolung-duk' ); ?></p>
					<span class="project-card__link"><?php esc_html_e( 'Read more →', 'drolung-duk' ); ?></span>
				</div>
			</a>
		</div>
	</div>
</section>

<!-- OBJECTIVES -->
<section class="section">
	<div class="container">
		<div class="section-eyebrow"><?php esc_html_e( 'Our Constitutional Objectives', 'drolung-duk' ); ?></div>
		<h2 class="section-title"><?php
			printf( esc_html__( 'Three commitments,%sset in our %s', 'drolung-duk' ), '<br>', '<em>' . esc_html__( 'foundation', 'drolung-duk' ) . '</em>' );
		?></h2>
		<div class="divider"></div>
		<p class="section-body"><?php esc_html_e( 'The objectives stated in our constitution guide every funding decision we make.', 'drolung-duk' ); ?></p>
		<div class="theme-strip" style="grid-template-columns:repeat(3,1fr);">
			<div class="theme-item fade-up">
				<div class="theme-icon">☸</div>
				<div class="theme-title"><?php esc_html_e( 'Advancement of Buddhism', 'drolung-duk' ); ?></div>
				<p class="theme-desc"><?php esc_html_e( 'Aiding the preservation and propagation of genuine transmission lineages of Buddhist teaching and practice.', 'drolung-duk' ); ?></p>
			</div>
			<div class="theme-item fade-up" style="transition-delay:0.08s">
				<div class="theme-icon">📚</div>
				<div class="theme-title"><?php esc_html_e( 'Advancement of education', 'drolung-duk' ); ?></div>
				<p class="theme-desc"><?php esc_html_e( 'Supporting access to mainstream and monastic education for children and young practitioners in the communities we serve.', 'drolung-duk' ); ?></p>
			</div>
			<div class="theme-item fade-up" style="transition-delay:0.16s">
				<div class="theme-icon">🤲</div>
				<div class="theme-title"><?php esc_html_e( 'Relief of poverty', 'drolung-duk' ); ?></div>
				<p class="theme-desc"><?php esc_html_e( 'Funding practical, locally-led work that helps lift families and communities out of hardship — from school fees to basic infrastructure.', 'drolung-duk' ); ?></p>
			</div>
		</div>
		<div style="margin-top:36px;text-align:center;">
			<a href="<?php echo esc_url( home_url( '/our-work/' ) ); ?>" style="font-size:14px;font-weight:600;color:var(--saffron);letter-spacing:0.04em;"><?php esc_html_e( 'See our work in depth →', 'drolung-duk' ); ?></a>
		</div>
	</div>
</section>

<!-- ASPIRATION PRAYER (Dölpopa) -->
<section class="section section--dark">
	<div class="container">
		<div class="prayer-block fade-up" style="text-align:center;">
			<div class="section-eyebrow"><?php esc_html_e( 'Aspiration Prayer', 'drolung-duk' ); ?></div>
			<h2 class="section-title" style="margin-bottom:8px;"><?php
				printf( esc_html__( 'Our %s', 'drolung-duk' ), '<em>' . esc_html__( 'guiding wish', 'drolung-duk' ) . '</em>' );
			?></h2>
			<div class="divider" style="margin-left:auto;margin-right:auto;"></div>
			<blockquote>
				<span>May I, throughout my each and every life,</span>
				<span>Illumine Buddha's teachings, make them bright.</span>
				<span>Yet, if I cannot so elucidate,</span>
				<span>Then may I shoulder Dharma's mighty weight.</span>
				<span>Yet, if the load is more than I can bear,</span>
				<span>May I at least feel deep concern and care.</span>
				<span>And, worrying about Dharma's decline,</span>
				<span>Stand guard and keep an ever watchful eye.</span>
			</blockquote>
			<cite>&mdash; <?php esc_html_e( 'Aspiration prayer by Dölpopa Sherab Gyaltsen', 'drolung-duk' ); ?></cite>
		</div>
	</div>
</section>

<!-- NEWS -->
<section class="section section--alt">
	<div class="container">
		<div class="section-eyebrow"><?php esc_html_e( 'News & Updates', 'drolung-duk' ); ?></div>
		<h2 class="section-title"><?php
			printf( esc_html__( 'From the %s', 'drolung-duk' ), '<em>' . esc_html__( 'field', 'drolung-duk' ) . '</em>' );
		?></h2>
		<div class="divider"></div>
		<div class="news-grid">
			<div class="news-card fade-up">
				<img src="https://images.unsplash.com/photo-1561414927-6d86591d0c4f?auto=format&fit=crop&q=80&w=800&h=520" alt="<?php esc_attr_e( 'Young monastics studying in Nepal', 'drolung-duk' ); ?>" class="news-card__img">
				<div class="news-card__body">
					<div class="news-card__tag"><?php esc_html_e( 'Africa · DRC Monastery', 'drolung-duk' ); ?></div>
					<div class="news-card__title"><?php esc_html_e( 'The Future is Here — Q&A with three young monastics from the DRC', 'drolung-duk' ); ?></div>
					<div class="news-card__meta">19 January 2025 · Lodro Yeshe</div>
				</div>
			</div>
			<div class="news-card fade-up" style="transition-delay:0.08s">
				<img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&q=80&w=600&h=360" alt="<?php esc_attr_e( 'Monastery construction site', 'drolung-duk' ); ?>" class="news-card__img">
				<div class="news-card__body">
					<div class="news-card__tag"><?php esc_html_e( 'Africa · DRC Monastery', 'drolung-duk' ); ?></div>
					<div class="news-card__title"><?php esc_html_e( 'Latest progress — Drolung Monastery, DRC', 'drolung-duk' ); ?></div>
					<div class="news-card__meta">29 December 2024 · Lodro Yeshe</div>
				</div>
			</div>
			<div class="news-card fade-up" style="transition-delay:0.16s">
				<img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&q=80&w=600&h=360" alt="<?php esc_attr_e( 'Foundations under construction', 'drolung-duk' ); ?>" class="news-card__img">
				<div class="news-card__body">
					<div class="news-card__tag"><?php esc_html_e( 'Africa · DRC Monastery', 'drolung-duk' ); ?></div>
					<div class="news-card__title"><?php esc_html_e( 'Laying the Foundations of Buddhism in Lubumbashi', 'drolung-duk' ); ?></div>
					<div class="news-card__meta">1 December 2024 · Inge Derijck</div>
				</div>
			</div>
		</div>
		<div style="margin-top:36px;text-align:center;">
			<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" style="font-size:14px;font-weight:600;color:var(--saffron);letter-spacing:0.04em;"><?php esc_html_e( 'All news →', 'drolung-duk' ); ?></a>
		</div>
	</div>
</section>

<?php get_footer();
