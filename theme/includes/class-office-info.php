<?php
namespace CTCL\ElectionWebsite;

class Office_Info {
	public static function block_render() {
		$email = get_option( 'email_address' );
		$phone = get_option( 'phone' );
		$fax   = get_option( 'fax' );

		$address  = get_option( 'address' );
		$address2 = get_option( 'address2' );
		$city     = get_option( 'city' );
		$state    = get_option( 'state' );
		$zip      = get_option( 'zip' );

		$hours = get_option( 'hours' );

		ob_start();
		?>
		<div class="office-info">
		<?php if ( $email ) : ?>
		<h4 class="email">Email</h4>
		<p><a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
		<?php endif; ?>

		<?php if ( $phone || $fax ) : ?>
		<h4 class="phone">Phone numbers</h4>
		<p>
			<?php
			if ( $phone ) {
				echo 'Phone: ' . esc_html( $phone );
			}
			if ( $phone && $fax ) {
				echo '<br />';
			}
			if ( $fax ) {
				echo 'Fax: ' . esc_html( $fax );
			}
			?>
		</p>
		<?php endif; ?>

		<?php if ( $address ) : ?>
		<h4 class="location">Office address</h4>
		<p>
			<?php
			echo esc_html( $address );
			if ( $address2 ) {
				echo '<br />' . esc_html( $address2 );
			}
			echo '<br />' . esc_html( sprintf( '%s %s, %s', $city, $state, $zip ) );
			?>
		</p>
		<?php endif; ?>

		<?php if ( $hours ) : ?>
		<h4 class="hours">Hours</h4>
		<p><?php echo wp_kses( nl2br( $hours ), [ 'br' => true ] ); ?></p>
		<?php endif; ?>
		</div>

		<?php
		return ob_get_clean();
	}
}
