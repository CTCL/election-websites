<?php
namespace CTCL\ElectionWebsite;

class Settings {

	public static function hooks() {
		add_action( 'admin_menu', [ get_called_class(), 'register_menu' ] );
		add_action( 'admin_init', [ get_called_class(), 'register_settings' ] );
	}

	public static function admin_notice() {
		echo '<div class="notice notice-success is-dismissible"><p>Your settings have been updated.</p></div>';
	}

	public static function configure_fields( $fields, $group ) {
		foreach ( $fields as $field_data ) {
			// var_dump($field_data);exit;
			add_settings_field( $field_data['uid'], $field_data['label'], [ 'CTCL\ElectionWebsite\Settings', 'field_callback' ], $group, $field_data['section'], $field_data );
			$args = $field_data['args'] ?? [];
			register_setting( $group, $field_data['uid'], $args );
		}
	}

	public static function field_callback( $args ) {
		switch ( $args['type'] ) {
			case 'text':
				echo '<input size="50" name="' . esc_attr( $args['uid'] ) . '" id="' . esc_attr( $args['uid'] ) . '" type="' . esc_attr( $args['type'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" value="' . esc_attr( get_option( $args['uid'] ) ) . '" />';
				break;
			case 'textarea':
				echo '<textarea cols="50" rows="5" name="' . esc_attr( $args['uid'] ) . '" id="' . esc_attr( $args['uid'] ) . '" type="' . esc_attr( $args['type'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '">' . esc_textarea( get_option( $args['uid'] ) ) . '</textarea>';
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
