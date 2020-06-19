<?php
/**
 * The template for displaying the footer.
 *
 * @package CTCL\ElectionWebsite
 * @since 1.0.0
 */
?>
<footer>
	<?php if ( is_front_page() ) { ?>
		<div class="footer-content-wrapper">
			<div>
				<h3 class="section-title"><?php bloginfo( 'title' ); ?></h3>
				<p class="info-item"><b>Gregory J. Diaz,</b> Registrar of Voters</p>
				<p class="info-item"><b>Email:</b> office@nevadacountyvotes.gov</p>
				<p class="info-item"><b>Phone:</b> 415.555.5555</p>	
			</div>
			<div>
				<p class="section-title"><b>Support</b></p>
				<?php
					wp_nav_menu(
						[
							'theme_location' => 'footer-section-1-menu',
						]
					);
				?>
			</div>
			<div>
				<p class="section-title"><b>Campaign Resources</b></p>
				<?php
					wp_nav_menu(
						[
							'theme_location' => 'footer-section-2-menu',
						]
					);
				?>
			</div>
		</div>
	<?php } else { ?>
		<div class="abbreviated-footer">
			<h3><?php bloginfo( 'title' ); ?></h3>
			<p><b>Email:</b> office@nevadacountyvotes.gov&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<b>Phone:</b> 415.555.5555</p>
		</div>	
	<?php } ?>
</footer>

	<?php wp_footer(); ?>

	</body>
</html>
