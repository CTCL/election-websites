<?php
/**
 * Configure the Office Details settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

class Office_Details_Settings extends Settings {

	const PAGE_TITLE  = 'Office Information';
	const FIELD_GROUP = 'contact_fields_all';

	public static function register_menu() {
		add_menu_page( 'Elections', 'Elections', 'manage_options', 'elections', [ get_called_class(), 'page' ], 'dashicons-star-filled', 2 );
		add_submenu_page( 'elections', 'Office Details', 'Office Details', 'manage_options', 'elections', [ get_called_class(), 'page' ] );
	}

	public static function get_fields() {
		return [
			[
				'uid'         => 'email_address',
				'label'       => 'Email Address',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'jane.doe@mycounty.gov',
				'label_for'   => 'email_address',
				'args'        => [ 'sanitize_callback' => 'sanitize_email' ],
			],
			[
				'uid'         => 'phone',
				'label'       => 'Phone number',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '(415) 867-5309',
				'label_for'   => 'phone',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_number' ] ],
			],
			[
				'uid'         => 'fax',
				'label'       => 'Fax number',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '(415) 867-5309',
				'label_for'   => 'fax',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_number' ] ],
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
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'validate_state' ] ],
			],
			[
				'uid'         => 'zip',
				'label'       => 'Zip',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '90210',
				'label_for'   => 'zip',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_zip' ] ],
			],
			[
				'uid'         => 'hours',
				'label'       => 'Hours',
				'section'     => 'contact_section',
				'type'        => 'textarea',
				'placeholder' => "Monday Friday\n9am - 5pm",
				'label_for'   => 'hours',
				'args'        => [ 'sanitize_callback' => 'sanitize_textarea_field' ],
			],
			[
				'uid'         => 'twitter',
				'label'       => 'Twitter',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'label_for'   => 'twitter',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_twitter' ] ],
			],
			[
				'uid'         => 'facebook',
				'label'       => 'Facebook',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'label_for'   => 'facebook',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_facebook' ] ],
			],
			[
				'uid'         => 'instagram',
				'label'       => 'Instagram',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'label_for'   => 'instagram',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_instagram' ] ],
			],
		];
	}

	public static function register_settings() {
		add_settings_section(
			'contact_section',
			'Contact details',
			false,
			static::FIELD_GROUP
		);

		add_settings_section(
			'social_section',
			'Social media',
			false,
			static::FIELD_GROUP
		);

		$fields = self::get_fields();

		self::configure_fields( $fields, static::FIELD_GROUP );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Office_Details_Settings', 'hooks' ] );
