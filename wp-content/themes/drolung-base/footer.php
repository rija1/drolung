<?php
/**
 * Footer — closes <main>, prints a minimal site footer and finalizes <body>.
 * Child themes can override entirely (footer.php) or hook into the action
 * 'drolung_footer_content' to inject branded content without redefining the
 * surrounding structure.
 *
 * @package drolung-base
 */
?>
</main><!-- /#site-content -->

<footer class="site-footer" role="contentinfo">
	<div class="footer-inner">
		<?php
		/**
		 * Action: drolung_footer_content
		 * Use in child themes to add columns, social, etc. without redoing the wrapper.
		 */
		do_action( 'drolung_footer_content' );
		?>

		<div class="footer-bottom">
			<span class="footer-copyright">
				&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
			</span>
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu( [
					'theme_location' => 'footer',
					'container'      => false,
					'items_wrap'     => '<nav class="footer-nav" aria-label="' . esc_attr__( 'Liens de pied de page', 'drolung-base' ) . '">%3$s</nav>',
					'walker'         => new Drolung_Flat_Nav_Walker(),
				] );
			}
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
