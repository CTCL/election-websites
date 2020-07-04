<?php
namespace CTCL\ElectionWebsite;

class Banner_Settings extends Settings {

	const PAGE_TITLE = 'Banner';
	const FIELD_GROUP = 'banner_fields';

	public static function register_menu() {
		add_submenu_page( 'elections', 'Banner', 'Banner', 'manage_options', 'banner', [ get_called_class(), 'page' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'banner_section',
			'Banner',
			false,
			static::FIELD_GROUP
		);

		add_settings_section(
			'alert_banner_section',
			'Alert Banner',
			false,
			static::FIELD_GROUP
		);

		$fields = [
			[
				'uid'         => 'banner_title',
				'label'       => 'Title',
				'section'     => 'banner_section',
				'type'        => 'text',
				'placeholder' => 'Important News',
				'label_for'   => 'banner_title',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'banner_description',
				'label'       => 'Description',
				'section'     => 'banner_section',
				'type'        => 'textarea',
				'placeholder' => 'Lorem ipsum…',
				'label_for'   => 'banner_description',
				'args'        => [ 'sanitize_callback' => 'sanitize_textarea_field' ],
			],
			[
				'uid'         => 'banner_link',
				'label'       => 'Description',
				'section'     => 'banner_section',
				'type'        => 'text',
				'placeholder' => 'https://www.example.com/',
				'label_for'   => 'banner_link',
				'args'        => [ 'sanitize_callback' => 'sanitize_url' ],
			],
			[
				'uid'         => 'banner_image',
				'label'       => 'Image',
				'section'     => 'banner_section',
				'type'        => 'upload',
				'label_for'   => 'banner_image',
				'args'        => [ 'sanitize_callback' => 'absint' ],
			],
			[
				'uid'         => 'alert_banner_title',
				'label'       => 'Title',
				'section'     => 'alert_banner_section',
				'type'        => 'text',
				'placeholder' => 'Don’t Forget',
				'label_for'   => 'alert_banner_title',
			],
			[
				'uid'         => 'alert_banner_description',
				'label'       => 'Description',
				'section'     => 'alert_banner_section',
				'type'        => 'text',
				'placeholder' => 'Lorem ipsum…',
				'label_for'   => 'alert_banner_description',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'alert_banner_link',
				'label'       => 'Description',
				'section'     => 'alert_banner_section',
				'type'        => 'text',
				'placeholder' => 'https://www.example.com/',
				'label_for'   => 'alert_banner_link',
				'args'        => [ 'sanitize_callback' => 'sanitize_url' ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Banner_Settings', 'hooks' ] );
