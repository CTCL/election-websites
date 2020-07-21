<?php
/**
 * Configure the Banner settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Banner class.
 *
 * Implements the Banner settings page.
 */
class Banner_Settings extends Settings {

	const PAGE_TITLE  = 'Banner';
	const FIELD_GROUP = 'banner_fields';

	/**
	 * Add Banner submenu page.
	 */
	public static function register_menu() {
		add_submenu_page( Settings::MENU_SLUG, 'Banner', 'Banner', 'edit_pages', 'banner', [ get_called_class(), 'page' ] );
	}

	/**
	 * Configure Banner settings.
	 */
	public static function register_settings() {
		add_settings_section(
			'banner_section',
			'Home Page Banner',
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
				'uid'     => 'banner_enabled',
				'title'   => 'Enabled',
				'section' => 'banner_section',
				'type'    => 'checkbox',
				'value'   => 1,
				'args'    => [ 'sanitize_callback' => 'absint' ],
			],
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
				'label'       => 'Link',
				'section'     => 'banner_section',
				'type'        => 'url',
				'placeholder' => 'https://www.example.com/',
				'label_for'   => 'banner_link',
				'args'        => [ 'sanitize_callback' => 'esc_url_raw' ],
			],
			[
				'uid'       => 'banner_image',
				'label'     => 'Image',
				'section'   => 'banner_section',
				'type'      => 'upload',
				'label_for' => 'banner_image',
				'args'      => [ 'sanitize_callback' => 'absint' ],
			],
			[
				'uid'     => 'alert_banner_enabled',
				'title'   => 'Enabled',
				'section' => 'alert_banner_section',
				'type'    => 'checkbox',
				'value'   => 1,
				'args'    => [ 'sanitize_callback' => 'absint' ],
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
				'label'       => 'Link',
				'section'     => 'alert_banner_section',
				'type'        => 'url',
				'placeholder' => 'https://www.example.com/',
				'label_for'   => 'alert_banner_link',
				'args'        => [ 'sanitize_callback' => 'esc_url_raw' ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Banner_Settings', 'hooks' ] );
