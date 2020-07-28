<?php
/**
 * Render the Office Info block.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Office Info class.
 *
 * Implements the office info block.
 */
class Office_Info {
	/**
	 * Render the Office Info block.
	 *
	 * @param array  $block_attributes  Array of block attributes.
	 * @param string $content           Post content.
	 *
	 * @return string
	 */
	public static function block_render( $block_attributes, $content ) {

		$contact_info = [
			'email_address' => \CTCL\Elections\Office_Details::email_address(),
			'phone'         => \CTCL\Elections\Office_Details::phone(),
			'fax'           => \CTCL\Elections\Office_Details::fax(),
			'address'       => \CTCL\Elections\Office_Details::address(),
			'address2'      => \CTCL\Elections\Office_Details::address2(),
			'city'          => \CTCL\Elections\Office_Details::city(),
			'state'         => \CTCL\Elections\Office_Details::state(),
			'zip'           => \CTCL\Elections\Office_Details::zip(),
			'hours'         => \CTCL\Elections\Office_Details::hours(),
		];

		// Fill in placeholder values on the backend.
		$is_backend = Helpers::is_block_backend();
		if ( $is_backend ) {
			$defaults = [];
			foreach ( Office_Details_Settings::get_fields() as $field_info ) {
				$defaults [ $field_info['uid'] ] = $field_info['placeholder'];
			}

			// Filter out the blank items. If something is '', it counts as present for wp_parse_args(), and is not replaced with a default value.
			$contact_info = wp_parse_args( array_filter( $contact_info ), $defaults );
		}

		ob_start();
		?>
		<div class="office-info">
		<?php if ( $contact_info['email_address'] ) : ?>
		<h5 class="email">Email</h5>
		<p><a href="<?php echo esc_url( 'mailto:' . $contact_info['email_address'] ); ?>"><?php echo esc_html( $contact_info['email_address'] ); ?></a></p>
		<?php endif; ?>

		<?php if ( $contact_info['phone'] || $contact_info['fax'] ) : ?>
		<h5 class="phone">Phone numbers</h5>
		<p>
			<?php
			if ( $contact_info['phone'] ) {
				echo 'Phone: ' . esc_html( $contact_info['phone'] );
			}
			if ( $contact_info['phone'] && $contact_info['fax'] ) {
				echo '<br />';
			}
			if ( $contact_info['fax'] ) {
				echo 'Fax: ' . esc_html( $contact_info['fax'] );
			}
			?>
		</p>
		<?php endif; ?>

		<?php if ( $contact_info['address'] ) : ?>
		<h5 class="location">Office address</h5>
		<p>
			<?php
			echo esc_html( $contact_info['address'] );
			if ( $contact_info['address2'] ) {
				echo '<br />' . esc_html( $contact_info['address2'] );
			}
			if ( $contact_info['city'] && $contact_info['state'] && $contact_info['zip'] ) {
				echo '<br />' . esc_html( sprintf( '%s %s, %s', $contact_info['city'], $contact_info['state'], $contact_info['zip'] ) );
			}
			?>
		</p>
		<?php endif; ?>

		<?php if ( $contact_info['hours'] ) : ?>
		<h5 class="hours">Hours</h5>
		<p><?php echo wp_kses( nl2br( $contact_info['hours'] ), [ 'br' => true ] ); ?></p>
		<?php endif; ?>
		</div>

		<?php
		return ob_get_clean();
	}
}
