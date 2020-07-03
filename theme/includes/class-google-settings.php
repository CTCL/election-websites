<?php
namespace CTCL\ElectionWebsite;

class Google_Settings extends Settings {

	const PAGE_TITLE = 'Google';
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
				'uid'         => 'tracking_id',
				'label'       => 'Tracking ID',
				'section'     => 'analytics_section',
				'type'        => 'text',
				'placeholder' => 'UA-123456789-0',
				'label_for'   => 'tracking_id',
			],
			[
				'uid'         => 'recaptcha_site_key',
				'label'       => 'Site Key',
				'section'     => 'recaptcha_section',
				'type'        => 'text',
				'placeholder' => 'Site Key',
				'label_for'   => 'recaptcha_site_key',
			],
			[
				'uid'         => 'recaptcha_secret_key',
				'label'       => 'Secret Key',
				'section'     => 'recaptcha_section',
				'type'        => 'text',
				'placeholder' => 'Secret Key',
				'label_for'   => 'recaptcha_secret_key',
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Google_Settings', 'hooks' ] );
