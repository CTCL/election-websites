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
				'uid'     => 'ctcl_banner_enabled',
				'title'   => 'Enabled',
				'section' => 'banner_section',
				'type'    => 'checkbox',
				'value'   => 1,
				'args'    => [ 'sanitize_callback' => 'absint' ],
			],
			[
				'uid'         => 'ctcl_banner_title',
				'label'       => 'Title',
				'section'     => 'banner_section',
				'type'        => 'text',
				'placeholder' => 'Important News',
				'label_for'   => 'ctcl_banner_title',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_banner_description',
				'label'       => 'Description',
				'section'     => 'banner_section',
				'type'        => 'textarea',
				'placeholder' => 'Lorem ipsum…',
				'label_for'   => 'ctcl_banner_description',
				'args'        => [ 'sanitize_callback' => 'sanitize_textarea_field' ],
			],
			[
				'uid'         => 'ctcl_banner_link',
				'label'       => 'Link',
				'section'     => 'banner_section',
				'type'        => 'url',
				'placeholder' => 'https://www.example.com/',
				'label_for'   => 'ctcl_banner_link',
				'args'        => [ 'sanitize_callback' => 'esc_url_raw' ],
			],
			[
				'uid'         => 'ctcl_banner_button',
				'label'       => 'Button',
				'section'     => 'banner_section',
				'type'        => 'text',
				'placeholder' => 'Read Me',
				'label_for'   => 'ctcl_banner_button',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'       => 'ctcl_banner_image',
				'label'     => 'Image',
				'section'   => 'banner_section',
				'type'      => 'upload',
				'label_for' => 'ctcl_banner_image',
				'args'      => [ 'sanitize_callback' => 'absint' ],
			],
			[
				'uid'     => 'ctcl_alert_banner_enabled',
				'title'   => 'Enabled',
				'section' => 'alert_banner_section',
				'type'    => 'checkbox',
				'value'   => 1,
				'args'    => [ 'sanitize_callback' => 'absint' ],
			],
			[
				'uid'         => 'ctcl_alert_banner_title',
				'label'       => 'Title',
				'section'     => 'alert_banner_section',
				'type'        => 'text',
				'placeholder' => 'Don’t Forget',
				'label_for'   => 'ctcl_alert_banner_title',
			],
			[
				'uid'         => 'ctcl_alert_banner_description',
				'label'       => 'Description',
				'section'     => 'alert_banner_section',
				'type'        => 'text',
				'placeholder' => 'Lorem ipsum…',
				'label_for'   => 'ctcl_alert_banner_description',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'         => 'ctcl_alert_banner_link',
				'label'       => 'Link',
				'section'     => 'alert_banner_section',
				'type'        => 'url',
				'placeholder' => 'https://www.example.com/',
				'label_for'   => 'ctcl_alert_banner_link',
				'args'        => [ 'sanitize_callback' => 'esc_url_raw' ],
			],
			[
				'uid'         => 'ctcl_alert_banner_link_text',
				'label'       => 'Link Text',
				'section'     => 'alert_banner_section',
				'type'        => 'text',
				'placeholder' => 'Read Me',
				'label_for'   => 'ctcl_alert_banner_link_text',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Banner_Settings', 'hooks' ] );
