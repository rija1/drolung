<?php
/**
 * Template for the "Témoignages" page.
 * Auto-generated from stories.html of the mockup.
 * Static text will be moved into ACF in a follow-up.
 *
 * @package drolung-org
 */

get_header();
?>

<main>
  <section class="page-hero">
    <div class="page-hero__inner">
      <div class="page-hero__eyebrow">Stories &amp; News</div>
      <h1 class="page-hero__title">From across<br>the <em>network</em></h1>
      <div class="page-hero__bar"></div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="filter-bar">
        <button class="filter-btn active">All</button>
        <button class="filter-btn">Heritage</button>
        <button class="filter-btn">Education</button>
        <button class="filter-btn">Humanitarian</button>
        <button class="filter-btn">Community</button>
        <button class="filter-btn">UK</button>
        <button class="filter-btn">Nepal</button>
        <button class="filter-btn">Madagascar</button>
      </div>

      <!-- Featured -->
      <div class="story-featured fade-up">
        <div class="story-featured__img">
          <img src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&q=80&w=900&h=560" alt="Nepal monastery manuscripts">
        </div>
        <div class="story-featured__body">
          <span class="story-featured__label">Featured · Heritage</span>
          <div class="story-featured__title">Inside the Vault: How Drolung Nepal Saved 2,000 Manuscripts from Disappearing Forever</div>
          <p class="story-featured__excerpt">Deep in the storerooms of a Kathmandu Valley monastery, a project twenty years in the making reaches completion. Lodro Dadron reflects on what was found, what was almost lost, and what it means to be a custodian of civilisation.</p>
          <div class="story-featured__meta">18 January 2026 · Drolung Nepal · 8 min read</div>
          <a href="#" class="story-featured__link">Read the story →</a>
        </div>
      </div>

      <!-- Grid -->
      <div class="story-grid">
        <div class="sc fade-up">
          <div class="sc__img"><img src="https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=600&h=320" alt="Madagascar relief"></div>
          <div class="sc__body">
            <div class="sc__tag">Emergency · Madagascar</div>
            <div class="sc__title">4,000 Families Reached After Cyclone Batsirai</div>
            <div class="sc__meta">3 March 2026 · Drolung Solidarités Madagascar</div>
          </div>
        </div>
        <div class="sc fade-up" style="transition-delay:0.08s">
          <div class="sc__img"><img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&q=80&w=600&h=320" alt="Education UK"></div>
          <div class="sc__body">
            <div class="sc__tag">Education · UK</div>
            <div class="sc__title">320 New Scholarships Awarded Through the Drolung UK Fund</div>
            <div class="sc__meta">12 February 2026 · Drolung UK</div>
          </div>
        </div>
        <div class="sc fade-up" style="transition-delay:0.16s">
          <div class="sc__img"><img src="https://images.unsplash.com/photo-1536599018102-9f803c140fc1?auto=format&fit=crop&q=80&w=600&h=320" alt="HK dialogue"></div>
          <div class="sc__body">
            <div class="sc__tag">Teachings · HK</div>
            <div class="sc__title">Drolung HK Hosts First Inter-Tradition Buddhist Dialogue in Five Years</div>
            <div class="sc__meta">28 January 2026 · Drolung HK</div>
          </div>
        </div>
        <div class="sc fade-up">
          <div class="sc__img"><img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=600&h=320" alt="DRC community"></div>
          <div class="sc__body">
            <div class="sc__tag">Community · RDCongo</div>
            <div class="sc__title">The Women's Cooperative That Is Changing the Economy of a Village</div>
            <div class="sc__meta">15 January 2026 · Drolung RDCongo</div>
          </div>
        </div>
        <div class="sc fade-up" style="transition-delay:0.08s">
          <div class="sc__img"><img src="https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=600&h=320" alt="Madagascar hub"></div>
          <div class="sc__body">
            <div class="sc__tag">Development · Madagascar</div>
            <div class="sc__title">A New Monastery Hub Opens in the Menabe Region</div>
            <div class="sc__meta">5 December 2025 · Drolung Solidarités Madagascar</div>
          </div>
        </div>
        <div class="sc fade-up" style="transition-delay:0.16s">
          <div class="sc__img"><img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=600&h=320" alt="Nepal school"></div>
          <div class="sc__body">
            <div class="sc__tag">Education · Nepal</div>
            <div class="sc__title">New Monastic School Opens in Remote Dolpo District</div>
            <div class="sc__meta">20 November 2025 · Drolung Nepal</div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer();
