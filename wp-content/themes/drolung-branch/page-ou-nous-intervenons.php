<?php
/**
 * Template for the "Où nous intervenons" page.
 * Auto-generated from where-we-work.html of the mockup.
 * Static text will be moved into ACF in a follow-up.
 *
 * @package drolung-branch
 */

get_header();
?>

<div class="page-breadcrumb">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
    <span>›</span>
    <span>Where We Work</span>
  </div>
</div>

<section class="page-hero" style="--hero-bg: url('https://images.unsplash.com/photo-1624272909636?auto=format&fit=crop&q=80&w=1600&h=700');">
  <style>.page-hero::before { background-image: var(--hero-bg); }</style>
  <div class="page-hero__line"></div>
  <div class="container">
    <div class="page-hero__eyebrow">Where We Work</div>
    <h1 class="page-hero__title">Working across <em>24 countries</em></h1>
    <p class="page-hero__sub">From the Himalayas to Madagascar — our monastery network spans every major Buddhist cultural region on earth.</p>
  </div>
</section>

<section class="inner-section">
  <div class="container">
    <div class="two-col fade-up">
      <div>
        <div class="section-eyebrow">Global Presence</div>
        <h2 class="section-title">Reaching the <em>unreachable</em></h2>
        <p class="section-body">Our monastery-centred approach means we reach communities that formal aid channels simply cannot. Monasteries are trusted, permanent institutions embedded in the social fabric of local life — making them uniquely effective humanitarian hubs.</p>
        <a href="<?php echo esc_url( home_url( '/s-engager/' ) ); ?>" class="btn-page btn-page--primary" style="margin-top:28px">Support our reach</a>
      </div>
      <img src="https://images.unsplash.com/photo-1546859070-7ac5e2e606c7?auto=format&fit=crop&q=80&w=700&h=500" alt="Nepal monastery" class="img-full" loading="lazy">
    </div>
  </div>
</section>
<section class="inner-section inner-section--tint">
  <div class="container">
    <div class="section-header fade-up">
      <div class="section-eyebrow">Our Regions</div>
      <h2 class="section-title">Where we <em>operate</em></h2>
    </div>
    <div class="three-col">
      <div class="card fade-up"><div class="card-img"><img src="https://images.unsplash.com/photo-1592334934411-8c2b49989d29?auto=format&fit=crop&q=80&w=700&h=420" alt="South Asia" loading="lazy"></div><div class="card-body"><span class="card-tag">South Asia</span><div class="card-title">Nepal · Bhutan · Sri Lanka</div><p class="card-desc">Our largest region with 68 partner monasteries spanning Theravada and Vajrayana traditions. Focus on education and manuscript preservation.</p></div></div>
      <div class="card fade-up" style="transition-delay:0.08s"><div class="card-img"><img src="https://images.unsplash.com/photo-1666281269793-da06484657e8?auto=format&fit=crop&q=80&w=700&h=420" alt="South East Asia" loading="lazy"></div><div class="card-body"><span class="card-tag">South East Asia</span><div class="card-title">Myanmar · Thailand · Cambodia</div><p class="card-desc">32 partner monasteries in one of the world's most culturally rich Buddhist regions. Emergency relief and community resilience programmes.</p></div></div>
      <div class="card fade-up" style="transition-delay:0.16s"><div class="card-img"><img src="https://images.unsplash.com/photo-1624272909636-4995421e37e7?auto=format&fit=crop&q=80&w=700&h=420" alt="Africa" loading="lazy"></div><div class="card-body"><span class="card-tag">Africa</span><div class="card-title">Madagascar · Kenya · Tanzania</div><p class="card-desc">Our newest and fastest-growing region. 14 active sites supporting emerging Buddhist communities and interfaith humanitarian networks.</p></div></div>
    </div>
  </div>
</section>
<section class="inner-section inner-section--dark">
  <div class="container">
    <div class="stat-band">
      <div class="stat-item"><div class="stat-item__num">24</div><div class="stat-item__label">Countries active</div></div>
      <div class="stat-item"><div class="stat-item__num">148</div><div class="stat-item__label">Partner monasteries</div></div>
      <div class="stat-item"><div class="stat-item__num">3</div><div class="stat-item__label">Continents</div></div>
      <div class="stat-item"><div class="stat-item__num">18</div><div class="stat-item__label">Years of operation</div></div>
    </div>
  </div>
</section>

<?php get_footer();
