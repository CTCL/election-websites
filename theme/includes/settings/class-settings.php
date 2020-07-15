<?php
/**
 * Base class for settings pages.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Settings base class.
 *
 * Implements common functions for settings (field setup and callbacks, notices, page rendering).
 */
class Settings {

	const MENU_SLUG = 'elections';

	/**
	 * Set up actions and filters.
	 */
	public static function hooks() {
		add_action( 'admin_menu', [ get_called_class(), 'register_menu' ] );
		add_action( 'admin_init', [ get_called_class(), 'register_settings' ] );
	}

	/**
	 * Display a notice indicating the settings were updated.
	 */
	public static function admin_notice() {
		echo '<div class="notice notice-success is-dismissible"><p>Your settings have been updated.</p></div>';
	}

	/**
	 * Add and register settings fields.
	 *
	 * @param array $fields Settings fields.
	 * @param array $group Settings group name.
	 */
	public static function configure_fields( $fields, $group ) {
		if ( ! is_array( $fields ) ) {
			return;
		}

		foreach ( $fields as $field_data ) {
			$label = $field_data['label'] ?? '';
			$args  = $field_data['args'] ?? [];
			add_settings_field( $field_data['uid'], $label, [ 'CTCL\Elections\Settings', 'field_callback' ], $group, $field_data['section'], $field_data );
			register_setting( $group, $field_data['uid'], $args );
		}
	}

	/**
	 * Print a settings field.
	 *
	 * @param array $args Field properties (uid, label, placeholder, etc.).
	 */
	public static function field_callback( $args ) {
		$placeholder = $args['placeholder'] ?? '';

		switch ( $args['type'] ) {
			case 'checkbox':
				echo '<input name="' . esc_attr( $args['uid'] ) . '" id="' . esc_attr( $args['uid'] ) . '" type="' . esc_attr( $args['type'] ) . '" value="' . esc_attr( $args['value'] ) . '" ' . checked( $args['value'], get_option( $args['uid'] ), false ) . '/>';
				if ( isset( $args['title'] ) && $args['title'] ) {
					echo '<label for="' . esc_attr( $args['uid'] ) . '">' . esc_html( $args['title'] ) . '</label>';
				}
				break;
			case 'number':
			case 'password':
			case 'email':
			case 'text':
				$prefix = $args['prefix'] ?? '';
				if ( $prefix ) {
					echo '<span class="prefix">' . esc_html( $prefix ) . '</span>';
				}
				echo '<input size="50" name="' . esc_attr( $args['uid'] ) . '" id="' . esc_attr( $args['uid'] ) . '" type="' . esc_attr( $args['type'] ) . '" placeholder="' . esc_attr( $placeholder ) . '" value="' . esc_attr( get_option( $args['uid'] ) ) . '" />';
				break;
			case 'upload':
				$image_id = get_option( $args['uid'] );
				echo '<div id="' . esc_attr( $args['uid'] . '_wrapper' ) . '">';
				echo '<input name="' . esc_attr( $args['uid'] ) . '" id="' . esc_attr( $args['uid'] ) . '" type="hidden" value="' . esc_attr( $image_id ) . '" />';
				echo '<input type="button" class="button upload" id="' . esc_attr( 'upload_' . $args['uid'] ) . '" value="Select Image" />';
				echo '<input type="button" class="button remove" id="remove_banner_image" value="Remove Image"';
				if ( ! $image_id ) {
					echo ' disabled="disabled"';
				}
				echo '" />';

				if ( $image_id ) {
					echo wp_kses_post( wp_get_attachment_image( $image_id, 'thumbnail', false, [ 'id' => 'banner_image_thumbnail' ] ) );
				}

				echo '</div>';
				break;
			case 'multitext':
				$value_list = get_option( $args['uid'] );
				if ( ! is_array( $value_list ) ) {
					$value_list = [ '' ];
				}
				$value_list_length = count( $value_list );
				$index             = 1;

				echo '<div id="' . esc_attr( $args['uid'] . '_wrapper' ) . '">';
				foreach ( $value_list as $value ) {
					echo '<div><input size="50" class="multitext" name="' . esc_attr( $args['uid'] ) . '[]" type="text" placeholder="' . esc_attr( $placeholder ) . '" value="' . esc_attr( $value ) . '" />';
					if ( $index++ < $value_list_length ) {
						echo '<input type="button" class="button remove" value="Remove" />';
					} else {
						echo '<input type="button" class="button add" id="' . esc_attr( 'add_' . $args['uid'] ) . '" value="Add" />';
					}
					echo '</div>';
				}
				echo '</div>';
				break;
			case 'textarea':
				echo '<textarea cols="50" rows="5" name="' . esc_attr( $args['uid'] ) . '" id="' . esc_attr( $args['uid'] ) . '" type="' . esc_attr( $args['type'] ) . '" placeholder="' . esc_attr( $placeholder ) . '">' . esc_textarea( get_option( $args['uid'] ) ) . '</textarea>';
				break;
			case 'select':
				echo '<select id="' . esc_attr( $args['uid'] ) . '" name="' . esc_attr( $args['uid'] ) . '">';
				foreach ( $args['options'] as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $value, get_option( $args['uid'] ), false ) . '>' . esc_html( $label ) . '</option>';
				}
					echo '</select>';
				break;
		}
	}

	/**
	 * Render the settings page.
	 */
	public static function page() {
		?>
		<form method="post" action="options.php">
			<h1><?php echo esc_html( static::PAGE_TITLE ); ?></h1>
			<?php
				settings_fields( static::FIELD_GROUP );
			if ( filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING ) ) {
				self::admin_notice();
			}
				do_settings_sections( static::FIELD_GROUP );
				submit_button();
			?>
		</form>
		<?php
	}
}
