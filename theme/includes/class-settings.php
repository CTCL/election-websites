<?php
namespace CTCL\ElectionWebsite;

class Settings {

	public static function hooks() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function register_menu() {
		add_menu_page( 'Elections', 'Elections', 'manage_options', 'elections', [ __CLASS__, 'office_info' ], 'dashicons-star-filled', 2 );
		add_submenu_page( 'elections', 'Office Details', 'Office Details', 'manage_options', 'elections', [ __CLASS__, 'office_info' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'contact_section',
			'Contact details',
			false,
			'contact_fields_all'
		);

		add_settings_section(
			'social_section',
			'Social media',
			false,
			'contact_fields_all'
		);

		$fields = [
			'contact_fields' =>
			[
				[
					'uid'         => 'email_address',
					'label'       => 'Email Address',
					'section'     => 'contact_section',
					'type'        => 'text',
					'placeholder' => 'jane.doe@mycounty.gov',
					'label_for'   => 'email_address',
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_email_address' ] ],
				],
				[
					'uid'         => 'phone',
					'label'       => 'Phone number',
					'section'     => 'contact_section',
					'type'        => 'text',
					'placeholder' => '(415) 867-5309',
					'label_for'   => 'phone',
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_phone_number' ] ],
				],
				[
					'uid'         => 'fax',
					'label'       => 'Fax number',
					'section'     => 'contact_section',
					'type'        => 'text',
					'placeholder' => '(415) 867-5309',
					'label_for'   => 'fax',
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_phone_number' ] ],
				],
				[
					'uid'         => 'address',
					'label'       => 'Address',
					'section'     => 'contact_section',
					'type'        => 'text',
					'placeholder' => '47 Oak Drive',
					'label_for'   => 'address',
					'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
				],
				[
					'uid'         => 'address2',
					'label'       => 'Address line 2',
					'section'     => 'contact_section',
					'type'        => 'text',
					'placeholder' => 'Floor 3',
					'label_for'   => 'address2',
					'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
				],
				[
					'uid'         => 'city',
					'label'       => 'City',
					'section'     => 'contact_section',
					'type'        => 'text',
					'placeholder' => 'Riverdale',
					'label_for'   => 'city',
					'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
				],
				[
					'uid'         => 'state',
					'label'       => 'State',
					'section'     => 'contact_section',
					'type'        => 'select',
					'options'     => Helpers::state_list(),
					'placeholder' => '47 Oak Drive',
					'label_for'   => 'state',
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'validate_state' ] ],
				],
				[
					'uid'         => 'zip',
					'label'       => 'Zip',
					'section'     => 'contact_section',
					'type'        => 'text',
					'placeholder' => '90210',
					'label_for'   => 'zip',
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_zip' ] ],
				],
				[
					'uid'         => 'hours',
					'label'       => 'Hours',
					'section'     => 'contact_section',
					'type'        => 'textarea',
					'placeholder' => "Monday Friday\n9am - 5pm",
					'label_for'   => 'hours',
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_hours' ] ],
				],
			],
			'social_fields'  =>
			[
				[
					'uid'         => 'twitter',
					'label'       => 'Twitter',
					'section'     => 'social_section',
					'type'        => 'text',
					'placeholder' => 'MyCountyVotes',
					'label_for'   => 'twitter',
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_twitter' ] ],
				],
				[
					'uid'         => 'facebook',
					'label'       => 'Facebook',
					'section'     => 'social_section',
					'type'        => 'text',
					'placeholder' => 'MyCountyVotes',
					'label_for'   => 'facebook',
					'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_facebook' ] ],
				],
				[
					'uid'         => 'instagram',
					'label'       => 'Instagram',
					'section'     => 'social_section',
					'type'        => 'text',
					'placeholder' => 'MyCountyVotes',
					'label_for'   => 'instagram',
					'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
					'args'        => [ 'sanitize_callback' => [ '\CTCL\ElectionWebsite\Helpers', 'format_instagram' ] ],
				],
			],
		];

		self::configure_fields( $fields, 'contact_fields_all' );
	}

	public static function configure_fields( $fields, $group ) {
		foreach ( $fields as $field_slug => $field_list ) {
			foreach ( $field_list as $field_data ) {
				add_settings_field( $field_data['uid'], $field_data['label'], [ 'CTCL\ElectionWebsite\Settings', 'field_callback' ], $group, $field_data['section'], $field_data );
				$args = $field_data['args'] ?? [];
				register_setting( $group, $field_data['uid'], $args );
			}
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

	public static function admin_notice() {
		echo '<div class="notice notice-success is-dismissible"><p>Your settings have been updated.</p></div>';
	}

	public static function inquiries() {
		echo "subject lines";
	}

	public static function office_info() {
		?>
		<form method="post" action="options.php">
			<h1>Office Information</h1>
			<?php
				settings_fields( 'contact_fields_all' );
			if ( filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING ) ) {
				self::admin_notice();
			}
				do_settings_sections( 'contact_fields_all' );
				submit_button();
			?>
		</form>
		<?php
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Settings', 'hooks' ] );
