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

		$voter_fields = [
			[
				'uid'         => 'ctcl_official_name',
				'label'       => 'Election Official Name',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Jane Smith',
				'label_for'   => 'ctcl_official_name',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_official_title',
				'label'       => 'Election Official Title',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Registrar of Voters',
				'label_for'   => 'ctcl_official_title',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_county_name',
				'label'       => 'Jurisdiction name',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Washington County',
				'label_for'   => 'ctcl_county_name',
				'args'        => [ 'sanitize_callback' => [ __CLASS__, 'validate_and_save_jurisdiction' ] ],
			],
			[
				'uid'         => 'ctcl_email_address',
				'label'       => 'Email Address',
				'section'     => 'contact_section',
				'type'        => 'email',
				'placeholder' => 'jane.doe@mycounty.gov',
				'label_for'   => 'ctcl_email_address',
				'args'        => [ 'sanitize_callback' => 'sanitize_email' ],
			],
			[
				'uid'         => 'ctcl_phone',
				'label'       => 'Phone number',
				'section'     => 'contact_section',
				'type'        => 'tel',
				'placeholder' => '(415) 867-5309',
				'label_for'   => 'ctcl_phone',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_number' ] ],
			],
			[
				'uid'         => 'ctcl_phone_extension',
				'label'       => 'Phone extension',
				'section'     => 'contact_section',
				'type'        => 'tel',
				'placeholder' => '54321',
				'label_for'   => 'ctcl_phone_extension',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_extension' ] ],
			],
			[
				'uid'         => 'ctcl_fax',
				'label'       => 'Fax number',
				'section'     => 'contact_section',
				'type'        => 'tel',
				'placeholder' => '(415) 867-5309',
				'label_for'   => 'ctcl_fax',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_number' ] ],
			],
			[
				'uid'         => 'ctcl_address',
				'label'       => 'Address',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '',
				'label_for'   => 'ctcl_address',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_address2',
				'label'       => 'Address line 2',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '',
				'label_for'   => 'ctcl_address2',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_city',
				'label'       => 'City',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '',
				'label_for'   => 'ctcl_city',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_state',
				'label'       => 'State',
				'section'     => 'contact_section',
				'type'        => 'select',
				'options'     => Helpers::state_list(),
				'placeholder' => '',
				'label_for'   => 'ctcl_state',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'validate_state' ] ],
			],
			[
				'uid'         => 'ctcl_zip',
				'label'       => 'Zip',
				'section'     => 'contact_section',
				'type'        => 'number',
				'placeholder' => '',
				'label_for'   => 'ctcl_zip',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_zip' ] ],
			],
			[
				'uid'         => 'ctcl_hours',
				'label'       => 'Hours',
				'section'     => 'contact_section',
				'type'        => 'textarea',
				'placeholder' => '',
				'label_for'   => 'ctcl_hours',
				'args'        => [ 'sanitize_callback' => 'sanitize_textarea_field' ],
			],
			[
				'uid'         => 'ctcl_facebook',
				'label'       => 'Facebook',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '/',
				'label_for'   => 'ctcl_facebook',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_facebook' ] ],
			],
			[
				'uid'         => 'ctcl_twitter',
				'label'       => 'Twitter / X',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '@',
				'label_for'   => 'ctcl_twitter',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_twitter' ] ],
			],
			[
				'uid'         => 'ctcl_instagram',
				'label'       => 'Instagram',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '@',
				'label_for'   => 'ctcl_instagram',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_instagram' ] ],
			],
		];

		$electon_fields = [
			[
				'uid'         => 'ctcl_official_name',
				'label'       => 'Association leader name',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Jane Smith',
				'label_for'   => 'ctcl_official_name',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_official_title',
				'label'       => 'Association leader title',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Registrar of Voters',
				'label_for'   => 'ctcl_official_title',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_county_name',
				'label'       => 'Association name',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => 'Washington County',
				'label_for'   => 'ctcl_county_name',
				'args'        => [ 'sanitize_callback' => [ __CLASS__, 'validate_and_save_jurisdiction' ] ],
			],
			[
				'uid'         => 'ctcl_email_address',
				'label'       => 'Email Address',
				'section'     => 'contact_section',
				'type'        => 'email',
				'placeholder' => 'jane.doe@mycounty.gov',
				'label_for'   => 'ctcl_email_address',
				'args'        => [ 'sanitize_callback' => 'sanitize_email' ],
			],
			[
				'uid'         => 'ctcl_phone',
				'label'       => 'Phone number',
				'section'     => 'contact_section',
				'type'        => 'tel',
				'placeholder' => '(415) 867-5309',
				'label_for'   => 'ctcl_phone',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_number' ] ],
			],
			[
				'uid'         => 'ctcl_phone_extension',
				'label'       => 'Phone extension',
				'section'     => 'contact_section',
				'type'        => 'tel',
				'placeholder' => '54321',
				'label_for'   => 'ctcl_phone_extension',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_extension' ] ],
			],
			[
				'uid'         => 'ctcl_fax',
				'label'       => 'Fax number',
				'section'     => 'contact_section',
				'type'        => 'tel',
				'placeholder' => '(415) 867-5309',
				'label_for'   => 'ctcl_fax',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_phone_number' ] ],
			],
			[
				'uid'         => 'ctcl_address',
				'label'       => 'Address',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '',
				'label_for'   => 'ctcl_address',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_address2',
				'label'       => 'Address line 2',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '',
				'label_for'   => 'ctcl_address2',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_city',
				'label'       => 'City',
				'section'     => 'contact_section',
				'type'        => 'text',
				'placeholder' => '',
				'label_for'   => 'ctcl_city',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_state',
				'label'       => 'State',
				'section'     => 'contact_section',
				'type'        => 'select',
				'options'     => Helpers::state_list(),
				'placeholder' => '',
				'label_for'   => 'ctcl_state',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'validate_state' ] ],
			],
			[
				'uid'         => 'ctcl_zip',
				'label'       => 'Zip',
				'section'     => 'contact_section',
				'type'        => 'number',
				'placeholder' => '',
				'label_for'   => 'ctcl_zip',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_zip' ] ],
			],
			[
				'uid'         => 'ctcl_facebook',
				'label'       => 'Facebook',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '/',
				'label_for'   => 'ctcl_facebook',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_facebook' ] ],
			],
			[
				'uid'         => 'ctcl_twitter',
				'label'       => 'Twitter',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '@',
				'label_for'   => 'ctcl_twitter',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_twitter' ] ],
			],
			[
				'uid'         => 'ctcl_instagram',
				'label'       => 'Instagram',
				'section'     => 'social_section',
				'type'        => 'text',
				'placeholder' => 'MyCountyVotes',
				'prefix'      => '@',
				'label_for'   => 'ctcl_instagram',
				'args'        => [ 'sanitize_callback' => [ '\CTCL\Elections\Helpers', 'format_instagram' ] ],
			],
		];

		$fields = get_option( 'audience' ) === 'officials' ? $electon_fields : $voter_fields;

		return $fields;
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

	/**
	 * Update site title when jurisdiction is changed.
	 *
	 * @param string $jurisdiction  County or town name.
	 *
	 * @return string
	 */
	public static function validate_and_save_jurisdiction( $jurisdiction ) {
		$jurisdiction = sanitize_text_field( $jurisdiction );
		if ( ! $jurisdiction ) {
			return false;
		}

		if ( get_option( 'audience' ) === 'officials' ) {
			update_option( 'blogname', $jurisdiction );
		} else {
			update_option( 'blogname', sprintf( '%s Elections', $jurisdiction ) );
		}


		return $jurisdiction;
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Office_Details_Settings', 'hooks' ] );
