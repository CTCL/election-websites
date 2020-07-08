<?php
/**
 * Configure the Google settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

class Google_Settings extends Settings {

	const PAGE_TITLE  = 'Google';
	const FIELD_GROUP = 'google_fields';

	public static function register_menu() {
		add_submenu_page( 'elections', 'Google', 'Google', 'manage_options', 'google', [ get_called_class(), 'page' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'analytics_section',
			'Google Analytics',
			false,
			static::FIELD_GROUP
		);

		add_settings_section(
			'recaptcha_section',
			'ReCAPTCHA',
			false,
			static::FIELD_GROUP
		);

		$fields = [
			[
				'uid'         => Google_Analytics::TRACKING_ID,
				'label'       => 'Tracking ID',
				'section'     => 'analytics_section',
				'type'        => 'text',
				'placeholder' => 'UA-123456789-0',
				'label_for'   => Google_Analytics::TRACKING_ID,
				'args'        => [ 'sanitize_callback' => [ get_called_class(), 'validate_tracking_id' ] ],
			],
			[
				'uid'         => 'recaptcha_site_key',
				'label'       => 'Site Key',
				'section'     => 'recaptcha_section',
				'type'        => 'text',
				'placeholder' => 'Site Key',
				'label_for'   => 'recaptcha_site_key',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'recaptcha_secret_key',
				'label'       => 'Secret Key',
				'section'     => 'recaptcha_section',
				'type'        => 'text',
				'placeholder' => 'Secret Key',
				'label_for'   => 'recaptcha_secret_key',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}

	public static function validate_tracking_id( $s ) {
		if ( preg_match( '/^UA-\d{4,9}-\d{1,4}$/', $s ) ) {
			return $s;
		}

		return '';
	}

}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Google_Settings', 'hooks' ] );
