<?php
/**
 * The template for displaying the footer.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

use \CTCL\Elections\Office_Details;

$address   = Office_Details::mailing_address();
$hours     = Office_Details::hours();
$twitter   = Office_Details::twitter( true );
$facebook  = Office_Details::facebook( true );
$instagram = Office_Details::instagram( true );
$phone     = Office_Details::phone( true );
$fax       = Office_Details::fax();
?>
<footer>
	<div class="footer-content-wrapper">
		<div class="contact">
			<h3>Contact</h3>
			<p class="office-title"><?php echo esc_html( Office_Details::title() ); ?></p>
			<p class="info-item"><?php Office_Details::email_address( true, true ); ?></p>
			<?php if ( $phone ) : ?>
			<p class="info-item"><?php echo wp_kses( $phone, Office_Details::$allowed_link_tags ); ?></p>
			<?php endif; ?>
			<?php if ( $fax ) : ?>
			<p class="info-item"><?php echo wp_kses( $fax, Office_Details::$allowed_link_tags ); ?> fax</p>
			<?php endif; ?>
		</div>
		<?php if ( $address || $hours ) : ?>
			<div class="address">
				<?php if ( $address ) : ?>
				<h3>Visit</h3>
				<p class="info-item">
					<?php echo wp_kses( $address, Office_Details::$allowed_br_tag ); ?>
				</p>
				<?php endif; ?>
				<?php if ( $hours ) : ?>
				<p class="info-item hours">
					Open:<br>
					<?php echo wp_kses( nl2br( $hours ), Office_Details::$allowed_br_tag ); ?>
				</p>
				<?php endif; ?>
			</div>
		<?php endif ?>
		<div class="social">
			<?php if ( $twitter || $facebook || $instagram ) : ?>
			<h3>Social</h3>
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
	<?php edit_post_link( 'Edit Page' ); ?>
</footer>

	<?php wp_footer(); ?>

	</body>
</html>
