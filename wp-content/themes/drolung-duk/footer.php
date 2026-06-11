<?php
/**
 * Drolung UK footer — closes <main>, prints the multi-column footer.
 *
 * @package drolung-duk
 */
?>
</main><!-- /#site-content -->

<footer>
	<div class="footer-inner">
		<div class="footer-brand">
			<div class="footer-brand__logo">
				<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="height:36px;filter:brightness(0) invert(0.85);">
				<span class="footer-brand__name">DROLUNG UK</span>
			</div>
			<p><?php esc_html_e( 'Drolung UK is a Scottish Charitable Incorporated Organisation (SCIO) registered with OSCR, no. SC054814. Continuing the work of the Buddhist Support Fund under a new legal structure.', 'drolung-duk' ); ?></p>
		</div>
		<div class="footer-col">
			<h4><?php esc_html_e( 'Who We Are', 'drolung-duk' ); ?></h4>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'Our Story', 'drolung-duk' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about/#lama' ) ); ?>"><?php esc_html_e( 'Our Lama', 'drolung-duk' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about/#team' ) ); ?>"><?php esc_html_e( 'Our Team', 'drolung-duk' ); ?></a></li>
			</ul>
		</div>
		<div class="footer-col">
			<h4><?php esc_html_e( 'What We Do', 'drolung-duk' ); ?></h4>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/our-work/#drc' ) ); ?>"><?php esc_html_e( 'Drolung Monastery, DRC', 'drolung-duk' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/our-work/#nepal' ) ); ?>"><?php esc_html_e( 'Shree Saraswati School', 'drolung-duk' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/how-we-spend/' ) ); ?>"><?php esc_html_e( 'How We Spend Donations', 'drolung-duk' ); ?></a></li>
			</ul>
		</div>
		<div class="footer-col">
			<h4><?php esc_html_e( 'Get Involved', 'drolung-duk' ); ?></h4>
			<ul>
				<li><a href="<?php echo esc_url( apply_filters( 'drolung_donate_url', '#' ) ); ?>"><?php esc_html_e( 'Donate', 'drolung-duk' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>"><?php esc_html_e( 'Articles', 'drolung-duk' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>"><?php esc_html_e( 'News & Updates', 'drolung-duk' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'drolung-duk' ); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="footer-bottom">
		<span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?> &middot; drolung.org.uk &middot; <?php esc_html_e( 'Scottish Charity SC054814', 'drolung-duk' ); ?></span>
		<span><?php esc_html_e( 'Privacy · Cookies · Safeguarding', 'drolung-duk' ); ?></span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
