<?php
/**
 * front-page.php — drolung.org landing page.
 *
 * Content extracted from the Mock/drolung-org/ mockup. Wrapped with the
 * parent theme's get_header() and get_footer(). The header and footer are
 * thus shared with the branches; only the body content lives here.
 *
 * The text and images below are currently static. They will be moved into
 * ACF (or Pods) field groups in a follow-up step so non-developers can edit
 * them from the admin without touching code.
 *
 * @package drolung-org
 */

get_header();
?>

<!-- HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-saffron-bar"></div>
    <div class="hero-content">
      <div class="hero-eyebrow">Buddhist Heritage · Humanitarian Aid · Global Network</div>
      <h1 class="hero-title">Preserving wisdom.<br><em>Serving humanity.</em></h1>
      <p class="hero-sub">Six independent organisations, one shared vision — spanning Nepal, the UK, Hong Kong, France, Madagascar and the DR Congo.</p>
      <a href="<?php echo esc_url( home_url( '/reseau/' ) ); ?>" class="hero-cta">Explore the Network →</a>
    </div>
  </section>

  <!-- STATS BAR -->
  <div class="stats-bar">
    <div class="stats-bar__inner">
      <div class="stat-item fade-up">
        <div class="stat-num">6</div>
        <div class="stat-label">Independent branches</div>
      </div>
      <div class="stat-item fade-up" style="transition-delay:0.08s">
        <div class="stat-num">24</div>
        <div class="stat-label">Countries of operation</div>
      </div>
      <div class="stat-item fade-up" style="transition-delay:0.16s">
        <div class="stat-num">20+</div>
        <div class="stat-label">Years of activity</div>
      </div>
      <div class="stat-item fade-up" style="transition-delay:0.24s">
        <div class="stat-num">2,000+</div>
        <div class="stat-label">Manuscripts preserved</div>
      </div>
    </div>
  </div>

  <!-- VISION INTRO -->
  <section class="section">
    <div class="container">
      <div class="vision-split">
        <div class="fade-up">
          <div class="section-eyebrow">Our Purpose</div>
          <h2 class="section-title">Rooted in <em>Dharma</em>,<br>engaged with the world</h2>
          <div class="divider"></div>
          <p class="section-body">Drolung — meaning "the place where the Dharma abides" — is a network of organisations founded on the Buddhist principles of compassion, interdependence and service. Each branch is anchored in its own country, culture and legal framework, while sharing the same lineage, the same values and the same long-term vision.</p>
          <blockquote class="blockquote">"To preserve what has been entrusted to us, and to share it generously with all who need it."</blockquote>
          <p class="attribution">— Drupon Khen Rinpoche, Spiritual Director</p>
          <a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>" style="display:inline-block;margin-top:28px;font-size:14px;font-weight:600;color:var(--saffron);letter-spacing:0.04em;">About Drolung →</a>
        </div>
        <div class="vision-img fade-up" style="transition-delay:0.15s">
          <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&q=80&w=800&h=880" alt="Himalayan monastery at dawn">
        </div>
      </div>
    </div>
  </section>

  <!-- NETWORK -->
  <section class="section section--alt">
    <div class="container">
      <div class="section-eyebrow">The Drolung Network</div>
      <h2 class="section-title">Six branches,<br>one <em>family</em></h2>
      <div class="divider"></div>
      <p class="section-body">Each organisation is independent and locally accountable. Together they form the Drolung global family, coordinated by a shared Committee.</p>
      <div class="branch-grid">
        <div class="branch-card fade-up">
          <div class="branch-flag">🇬🇧</div>
          <div class="branch-name">DROLUNG UK</div>
          <div class="branch-country">United Kingdom · SC054814</div>
          <p class="branch-desc">Scottish Charitable Incorporated Organisation supporting Buddhist heritage and humanitarian programmes across Asia and Africa.</p>
          <a href="https://drolung.org.uk" target="_blank" class="branch-link">Visit Drolung UK →</a>
        </div>
        <div class="branch-card fade-up" style="transition-delay:0.06s">
          <div class="branch-flag">🇳🇵</div>
          <div class="branch-name">DROLUNG Nepal</div>
          <div class="branch-country">Nepal</div>
          <p class="branch-desc">The founding branch, based in Kathmandu, running monastic schools and heritage preservation programmes in Nepal and Bhutan.</p>
          <a href="#" class="branch-link">Visit →</a>
        </div>
        <div class="branch-card fade-up" style="transition-delay:0.12s">
          <div class="branch-flag">🇭🇰</div>
          <div class="branch-name">DROLUNG HK</div>
          <div class="branch-country">Hong Kong</div>
          <p class="branch-desc">Supporting the teaching lineage and Dharma resources across East and South-East Asia, with a focus on education and dialogue.</p>
          <a href="#" class="branch-link">Visit →</a>
        </div>
        <div class="branch-card fade-up" style="transition-delay:0.18s">
          <div class="branch-flag">🇫🇷</div>
          <div class="branch-name">DROLUNG Solidarités France</div>
          <div class="branch-country">France</div>
          <p class="branch-desc">Association loi 1901 coordinating humanitarian response and community development programmes in francophone regions.</p>
          <a href="#" class="branch-link">Visit →</a>
        </div>
        <div class="branch-card fade-up" style="transition-delay:0.24s">
          <div class="branch-flag">🇲🇬</div>
          <div class="branch-name">DROLUNG Solidarités Madagascar</div>
          <div class="branch-country">Madagascar</div>
          <p class="branch-desc">Building social enterprise monasteries as hubs for education, health and sustainable livelihoods across Madagascar.</p>
          <a href="#" class="branch-link">Visit →</a>
        </div>
        <div class="branch-card fade-up" style="transition-delay:0.30s">
          <div class="branch-flag">🇨🇩</div>
          <div class="branch-name">DROLUNG RDCongo</div>
          <div class="branch-country">DR Congo</div>
          <p class="branch-desc">Pioneering Buddhist-inspired humanitarian response in the DRC, delivering food security and community resilience programmes.</p>
          <a href="#" class="branch-link">Visit →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- OUR WORK -->
  <section class="section">
    <div class="container">
      <div class="section-eyebrow">What We Do</div>
      <h2 class="section-title">Four pillars of <em>shared work</em></h2>
      <div class="divider"></div>
      <div class="theme-strip">
        <div class="theme-item fade-up">
          <div class="theme-icon">📚</div>
          <div class="theme-title">Buddhist Education</div>
          <p class="theme-desc">Supporting monastic schools, scholarships and Dharma teaching across the network's partner countries.</p>
        </div>
        <div class="theme-item fade-up" style="transition-delay:0.08s">
          <div class="theme-icon">🏛</div>
          <div class="theme-title">Heritage Preservation</div>
          <p class="theme-desc">Safeguarding ancient manuscripts, temple archives and living ritual traditions threatened by time and conflict.</p>
        </div>
        <div class="theme-item fade-up" style="transition-delay:0.16s">
          <div class="theme-icon">🤝</div>
          <div class="theme-title">Humanitarian Aid</div>
          <p class="theme-desc">Emergency response and post-crisis recovery delivered through monastery networks in disaster-prone regions.</p>
        </div>
        <div class="theme-item fade-up" style="transition-delay:0.24s">
          <div class="theme-icon">🌱</div>
          <div class="theme-title">Community Resilience</div>
          <p class="theme-desc">Long-term development programmes building food security, health access and sustainable livelihoods.</p>
        </div>
      </div>
      <div style="margin-top:36px;text-align:center;">
        <a href="<?php echo esc_url( home_url( '/notre-action/' ) ); ?>" style="font-size:14px;font-weight:600;color:var(--saffron);letter-spacing:0.04em;">See our work in depth →</a>
      </div>
    </div>
  </section>

  <!-- COMMITTEE -->
  <section class="section section--dark">
    <div class="container">
      <div class="section-eyebrow">The Committee</div>
      <h2 class="section-title">The people who hold <em>the vision</em></h2>
      <div class="divider"></div>
      <p class="section-body">The Drolung Committee provides spiritual and strategic guidance to the entire network. Its members bring together Buddhist scholarship, humanitarian expertise and decades of field experience.</p>
      <div class="committee-grid">
        <div class="member-card fade-up">
          <div class="member-avatar">
            <img src="https://images.unsplash.com/photo-1590086782957-93c06ef21604?auto=format&fit=crop&q=80&w=240&h=240" alt="Drupon Khen Rinpoche">
          </div>
          <div class="member-name">Drupon Khen Rinpoche</div>
          <div class="member-role">Spiritual Director &amp; Founder</div>
        </div>
        <div class="member-card fade-up" style="transition-delay:0.08s">
          <div class="member-avatar">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=240&h=240" alt="Lama Samten">
          </div>
          <div class="member-name">Lama Samten</div>
          <div class="member-role">Deputy Director, Teachings</div>
        </div>
        <div class="member-card fade-up" style="transition-delay:0.16s">
          <div class="member-avatar">
            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=240&h=240" alt="Lodro Dadron">
          </div>
          <div class="member-name">Lodro Dadron</div>
          <div class="member-role">Committee Member, Heritage</div>
        </div>
        <div class="member-card fade-up" style="transition-delay:0.24s">
          <div class="member-avatar">
            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=240&h=240" alt="Sherab Chodron">
          </div>
          <div class="member-name">Sherab Chodron</div>
          <div class="member-role">Committee Member, Education</div>
        </div>
        <div class="member-card fade-up" style="transition-delay:0.32s">
          <div class="member-avatar">
            <img src="https://images.unsplash.com/photo-1554151228-14d9def656e4?auto=format&fit=crop&q=80&w=240&h=240" alt="Drolkar">
          </div>
          <div class="member-name">Drolkar</div>
          <div class="member-role">Committee Member, Humanitarian</div>
        </div>
        <div class="member-card fade-up" style="transition-delay:0.40s">
          <div class="member-avatar">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=240&h=240" alt="Karma Kunga">
          </div>
          <div class="member-name">Karma Kunga</div>
          <div class="member-role">Committee Member, Development</div>
        </div>
      </div>
      <div style="margin-top:48px;text-align:center;">
        <a href="<?php echo esc_url( home_url( '/a-propos/#committee' ) ); ?>" style="font-size:14px;font-weight:600;color:var(--saffron);letter-spacing:0.04em;">Meet the Committee →</a>
      </div>
    </div>
  </section>

  <!-- STORIES -->
  <section class="section section--alt">
    <div class="container">
      <div class="section-eyebrow">Stories &amp; News</div>
      <h2 class="section-title">From across the <em>network</em></h2>
      <div class="divider"></div>
      <div class="news-grid">
        <div class="news-card fade-up">
          <img src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&q=80&w=800&h=520" alt="Nepal monastery" class="news-card__img">
          <div class="news-card__body">
            <div class="news-card__tag">Heritage · Nepal</div>
            <div class="news-card__title">2,000-Year-Old Manuscripts Digitised in Partnership with Kathmandu University</div>
            <div class="news-card__meta">18 January 2026 · Drolung Nepal</div>
          </div>
        </div>
        <div class="news-card fade-up" style="transition-delay:0.08s">
          <img src="https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=600&h=360" alt="Madagascar relief" class="news-card__img">
          <div class="news-card__body">
            <div class="news-card__tag">Emergency · Madagascar</div>
            <div class="news-card__title">Emergency Cyclone Response Reaches 4,000 Families in Southern Madagascar</div>
            <div class="news-card__meta">3 March 2026 · Drolung Solidarités Madagascar</div>
          </div>
        </div>
        <div class="news-card fade-up" style="transition-delay:0.16s">
          <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&q=80&w=600&h=360" alt="Education programme" class="news-card__img">
          <div class="news-card__body">
            <div class="news-card__tag">Education · UK</div>
            <div class="news-card__title">320 New Scholarships Awarded Through the Drolung UK Fund</div>
            <div class="news-card__meta">12 February 2026 · Drolung UK</div>
          </div>
        </div>
      </div>
      <div style="margin-top:36px;text-align:center;">
        <a href="<?php echo esc_url( home_url( '/temoignages/' ) ); ?>" style="font-size:14px;font-weight:600;color:var(--saffron);letter-spacing:0.04em;">All stories →</a>
      </div>
    </div>
  </section>

<?php get_footer();
