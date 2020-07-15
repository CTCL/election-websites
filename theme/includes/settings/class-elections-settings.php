<?php
/**
 * Configure the website settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Elections Settings class.
 *
 * Implements the Office Details settings page.
 */
class Elections_Settings extends Settings {

	const PAGE_TITLE  = 'Appearance';
	const FIELD_GROUP = 'appearance_all';

	/**
	 * Add Elections menu. Set first item to Office Details.
	 */
	public static function register_menu() {
		add_menu_page( 'Elections', 'Elections', 'edit_pages', Settings::MENU_SLUG, [ get_called_class(), 'page' ], 'dashicons-star-filled', 2 );
		add_submenu_page( Settings::MENU_SLUG, 'Appearance', 'Appearance', 'edit_pages', Settings::MENU_SLUG, [ get_called_class(), 'page' ] );
	}

	/**
	 * Configure Elections settings.
	 */
	public static function register_settings() {
		add_settings_section(
			'appearance',
			false,
			false,
			static::FIELD_GROUP
		);

		$fields = [
			[
				'uid'       => 'color_scheme',
				'label'     => 'Color Scheme',
				'section'   => 'appearance',
				'type'      => 'radio',
				'options'   => self::color_scheme_list(),
				'label_for' => 'color_scheme',
				'args'      => [ 'sanitize_callback' => [ __CLASS__, 'validate_color_scheme' ] ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}

	/**
	 * List of color schemes to choose from.
	 *
	 * @return array
	 */
	public static function color_scheme_list() {
		return [
			'A' => 'Scheme A',
			'B' => 'Scheme B',
		];
	}

	/**
	 * Ensure color scheme is from list of valid schemes.
	 *
	 * @param string $item  Color scheme ID.
	 *
	 * @return string
	 */
	public static function validate_color_scheme( $item ) {
		$list = self::color_scheme_list();

		if ( in_array( $item, array_keys( $list ), true ) ) {
			return $item;
		}

		return '';
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Elections_Settings', 'hooks' ] );
