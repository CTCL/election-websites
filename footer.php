<?php
/**
 * The template for displaying the footer.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

use \CTCL\Elections\Office_Details;

$official_name  = Office_Details::official();
$official_title = Office_Details::official_title();
$twitter        = Office_Details::twitter( true );
$facebook       = Office_Details::facebook( true );
$instagram      = Office_Details::instagram( true );
$phone          = Office_Details::phone( true );
?>
<footer>
	<div class="footer-content-wrapper">
		<div>
			<p class="office-title"><?php echo esc_html( Office_Details::title() ); ?></p>
			<?php if ( $official_name && $official_title ) : ?>
			<p class="info-item">
				<?php echo esc_html( $official_name ); ?>,
				<?php echo esc_html( $official_title ); ?>
			</p>
			<?php endif; ?>
		</div>
		<div>
			<p class="info-item"><?php Office_Details::email_address( true, true ); ?></p>
			<?php if ( $phone ) : ?>
			<p class="info-item"><?php echo wp_kses( $phone, Office_Details::$allowed_link_tags ); ?></p>
			<?php endif; ?>

			<?php if ( $twitter || $facebook || $instagram ) : ?>
			<ul class="info-item">
				<?php if ( $twitter ) : ?>
					<li><?php echo wp_kses( $twitter, Office_Details::$allowed_link_tags ); ?>
				<?php endif; ?>

				<?php if ( $facebook ) : ?>
					<li><?php echo wp_kses( $facebook, Office_Details::$allowed_link_tags ); ?>
				<?php endif; ?>

				<?php if ( $instagram ) : ?>
					<li><?php echo wp_kses( $instagram, Office_Details::$allowed_link_tags ); ?>
				<?php endif; ?>
			</ul>
			<?php endif; ?>

		</div>
	</div>
</footer>

	<?php wp_footer(); ?>

	</body>
</html>
