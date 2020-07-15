<?php
/**
 * Configure the Office Details settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Office Details class.
 *
 * Implements the Office Details settings page.
 */
class Office_Details_Settings extends Settings {

	const PAGE_TITLE  = 'Office Information';
	const FIELD_GROUP = 'contact_fields_all';

	/**
	 * Add Elections menu. Set first item to Office Details.
	 */
	public static function register_menu() {
		add_submenu_page( Settings::MENU_SLUG, 'Office Details', 'Office Details', 'edit_pages', 'office_details', [ get_called_class(), 'page' ] );
	}

	/**
	 * Get Office Details settings fields. Abstracted out so we can use placeholder text in the block backend.
	 *
	 * @return array
	 */
	public static function get_fields() {
		return [
			[
				'uid'         => 'official_name',
				'label'       => 'Election Official Name',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Jane Smith',
				'label_for'   => 'official_name',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'official_title',
				'label'       => 'Election Official Title',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Registrar of Voters',
				'label_for'   => 'official_title',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
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
				'uid'         => 'facebook',
				'label'       => 'Facebook',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '/',
				'label_for'   => 'facebook',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_facebook' ] ],
			],
			[
				'uid'         => 'twitter',
				'label'       => 'Twitter',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '@',
				'label_for'   => 'twitter',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_twitter' ] ],
			],
			[
				'uid'         => 'instagram',
				'label'       => 'Instagram',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '@',
				'label_for'   => 'instagram',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_instagram' ] ],
			],
		];
	}

	/**
	 * Configure Office Details settings.
	 */
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
