<?php
/**
 * Helpers to get front page banner content.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Banner class.
 *
 * Helpers to get banner content.
 */
class Banner {

	/**
	 * Whether or not the front page banner is enabled.
	 *
	 * @return boolean
	 */
	public static function is_enabled() {
		return 1 === absint( get_option( 'ctcl_banner_enabled' ) );
	}

	/**
	 * The front page banner title.
	 *
	 * @return string
	 */
	public static function title() {
		return get_option( 'ctcl_banner_title' );
	}

	/**
	 * The front page banner description.
	 *
	 * @return string
	 */
	public static function description() {
		return get_option( 'ctcl_banner_description' );
	}

	/**
	 * The front page banner URL.
	 *
	 * @return string
	 */
	public static function link() {
		return get_option( 'ctcl_banner_link' );
	}

	/**
	 * The front page banner button text.
	 *
	 * @return string
	 */
	public static function button() {
		return get_option( 'ctcl_banner_button' );
	}

	/**
	 * The front page image ID.
	 *
	 * @return integer
	 */
	public static function image_id() {
		return get_option( 'ctcl_banner_image' );
	}
}
