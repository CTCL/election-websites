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

	const DEFAULT_THEME = 'blue';

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
				'uid'         => 'blogname',
				'label'       => 'Site Title',
				'section'     => 'appearance',
				'type'        => 'text',
				'placeholder' => 'Washington County Elections',
				'label_for'   => 'blogname',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
			[
				'uid'       => 'logo',
				'label'     => 'Logo',
				'section'   => 'appearance',
				'type'      => 'upload',
				'label_for' => 'logo',
				'value'     => get_theme_mod( 'custom_logo' ), // This isn't a standard WP Option.
				'args'      => [ 'sanitize_callback' => [ __CLASS__, 'validate_and_save_logo' ] ],
			],
			[
				'uid'       => 'theme',
				'label'     => 'Theme',
				'section'   => 'appearance',
				'type'      => 'radio',
				'options'   => self::theme_list(),
				'label_for' => 'theme',
				'args'      => [ 'sanitize_callback' => [ __CLASS__, 'validate_theme' ] ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}

	/**
	 * List of themes to choose from.
	 *
	 * @return array
	 */
	public static function theme_list() {
		return [
			'blue'  => 'Blue',
			'green' => 'Green',
		];
	}

	/**
	 * Active theme.
	 *
	 * @return array
	 */
	public static function get_theme() {
		return 'theme-' . ( get_option( 'theme' ) ?? self::DEFAULT_THEME );
	}

	/**
	 * Ensure logo is a valid image ID and save it.
	 *
	 * @param string $image_id  Image ID.
	 *
	 * @return string
	 */
	public static function validate_and_save_logo( $image_id ) {
		$image_id = absint( $image_id );
		if ( ! $image_id || ! wp_attachment_is_image( $image_id ) ) {
			remove_theme_mod( 'custom_logo' );
			return false;
		}

		// We are storing this as a theme mod, not an option.
		// Need to manually save, since the Settings API won't do this automatically.
		set_theme_mod( 'custom_logo', $image_id );

		return $image_id;
	}

	/**
	 * Ensure theme is from list of valid themes.
	 *
	 * @param string $item  Theme ID.
	 *
	 * @return string
	 */
	public static function validate_theme( $item ) {
		$list = self::theme_list();

		if ( in_array( $item, array_keys( $list ), true ) ) {
			return $item;
		}

		return '';
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Elections_Settings', 'hooks' ] );
