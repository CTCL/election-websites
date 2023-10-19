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

	const DEFAULT_THEME  = 'blue';
	const DEFAULT_BANNER = 'dark';

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
				'uid'         => 'blogdescription',
				'label'       => 'Description',
				'section'     => 'appearance',
				'type'        => 'textarea',
				'placeholder' => 'Find out elections dates and register to vote in Washington County.',
				'label_for'   => 'blogdescription',
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
				'uid'       => 'ctcl_theme',
				'label'     => 'Theme',
				'section'   => 'appearance',
				'type'      => 'radio',
				'options'   => self::theme_list(),
				'label_for' => 'theme',
				'args'      => [ 'sanitize_callback' => [ __CLASS__, 'validate_theme' ] ],
			],
			[
				'uid'       => 'ctcl_banner_style',
				'label'     => 'Banner',
				'section'   => 'appearance',
				'type'      => 'radio',
				'options'   => self::banner_style_list(),
				'label_for' => 'ctcl_banner_style',
				'args'      => [ 'sanitize_callback' => [ __CLASS__, 'validate_banner_style' ] ],
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
	 * List of banner styles to choose from.
	 *
	 * @return array
	 */
	public static function banner_style_list() {
		return [
			'dark'      => 'Dark Blue',
			'light'     => 'Light Blue',
			'dark-red'  => 'Dark Red',
			'light-red' => 'Light Red',
			'teal'      => 'Teal',
		];
	}

	/**
	 * Active theme slug.
	 *
	 * @return array
	 */
	public static function get_theme_slug() {
		return ( get_option( 'ctcl_theme', null ) ?? self::DEFAULT_THEME );
	}

	/**
	 * Active theme.
	 *
	 * @return array
	 */
	public static function get_theme() {
		return 'theme-' . self::get_theme_slug();
	}

	/**
	 * Active banner style.
	 *
	 * @return array
	 */
	public static function get_banner_style() {
		return 'banner-' . ( get_option( 'ctcl_banner_style', null ) ?? self::DEFAULT_BANNER );
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
	 * When item is in a supplied list, return it. Otherwise return empty string.
	 *
	 * @param string $item  Item.
	 * @param array  $list  List.
	 *
	 * @return string
	 */
	public static function validate_list_item( $item, $list ) {
		if ( in_array( $item, array_keys( $list ), true ) ) {
			return $item;
		}

		return '';
	}

	/**
	 * Ensure theme is from list of valid themes.
	 *
	 * @param string $item  Theme ID.
	 *
	 * @return string
	 */
	public static function validate_theme( $item ) {
		return self::validate_list_item( $item, self::theme_list() );
	}

	/**
	 * Ensure banner style is from list of valid banner styles.
	 *
	 * @param string $item  Banner ID.
	 *
	 * @return string
	 */
	public static function validate_banner_style( $item ) {
		return self::validate_list_item( $item, self::banner_style_list() );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Elections_Settings', 'hooks' ] );
