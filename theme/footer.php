<?php
/**
 * The template for displaying the footer.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

$official_name  = \CTCL\Elections\Office_Details::official();
$official_title = \CTCL\Elections\Office_Details::official_title();
?>
<footer>
	<div class="footer-content-wrapper">
		<div>
			<h4 class="section-title"><?php echo esc_html( \CTCL\Elections\Office_Details::title() ); ?></h4>
			<?php if ( $official_name && $official_title ) : ?>
			<p class="info-item">
				<b><?php echo esc_html( $official_name ); ?>,</b>
				<?php echo esc_html( $official_title ); ?>
			</p>
			<?php endif; ?>
			<p class="info-item"><b>Email:</b> <?php \CTCL\Elections\Office_Details::email( true, true ); ?></p>
			<p class="info-item"><b>Phone:</b> <?php echo esc_html( \CTCL\Elections\Office_Details::phone() ); ?></p>
		</div>
	</div>
</footer>

	<?php wp_footer(); ?>

	</body>
</html>
