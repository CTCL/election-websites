<?php
/**
 * The template for displaying the footer.
 *
 * @package CTCL\ElectionWebsite
 * @since 1.0.0
 */
?>
<footer>
	<div class="footer-content-wrapper">
		<div>
			<h3 class="section-title"><?php bloginfo( 'title' ); ?></h3>
			<p><b>Gregory J. Diaz,</b> Registrar of Voters</p>
			<p><b>Email:</b> office@nevadacountyvotes.gov</p>
			<p><b>Phone:</b> 415.555.5555</p>	
		</div>
		<div>
			<p class="section-title"><b>Support</b></p>
			<?php
				wp_nav_menu([
					'theme_location' => 'footer-section-1-menu'
				]);
			?>
		</div>
		<div>
			<p class="section-title"><b>Campaign Resources</b></p>
			<?php
				wp_nav_menu([
					'theme_location' => 'footer-section-2-menu'
				]);
			?>
		</div>
	</div>
</footer>

	<?php wp_footer(); ?>

	</body>
</html>
