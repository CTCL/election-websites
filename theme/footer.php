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
				<h4 class="section-title"><?php bloginfo( 'title' ); ?></h4>
				<p class="info-item"><b>Gregory J. Diaz,</b> Registrar of Voters</p>
				<p class="info-item"><b>Email:</b> office@nevadacountyvotes.gov</p>
				<p class="info-item"><b>Phone:</b> 415.555.5555</p>
			</div>
		</div>
	<?php } else { ?>
		<div class="abbreviated-footer">
			<h4><?php bloginfo( 'title' ); ?></h4>
			<p><b>Email:</b> office@nevadacountyvotes.gov | <b>Phone:</b> 415.555.5555</p>
		</div>
	<?php } ?>
</footer>

	<?php wp_footer(); ?>

	</body>
</html>
