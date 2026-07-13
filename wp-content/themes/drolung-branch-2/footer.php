<?php
/**
 * drolung-branch-2 footer — dark compact footer, "Terrain" design.
 * Overrides the parent (drolung-base) footer entirely.
 *
 * @package drolung-branch-2
 */
?>
</main><!-- /#site-content -->

<footer class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-brand">
				<img src="<?php echo esc_url( drolung_get_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<p><?php echo esc_html( drolung_field( 'footer_baseline', __( 'Association loi 1901, entièrement bénévole. Soutien aux projets de Drolung Solidarité Madagascar.', 'drolung-branch-2' ) ) ); ?></p>
			</div>
			<?php drolung_branch2_footer_columns(); ?>
		</div>
		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu( [
					'theme_location' => 'footer',
					'container'      => false,
					'items_wrap'     => '<nav class="footer-nav" aria-label="' . esc_attr__( 'Liens de pied de page', 'drolung-branch-2' ) . '" style="display:flex;gap:20px">%3$s</nav>',
					'walker'         => class_exists( 'Drolung_Flat_Nav_Walker' ) ? new Drolung_Flat_Nav_Walker() : null,
				] );
			}
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
