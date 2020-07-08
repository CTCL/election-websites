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

	public static function is_enabled() {
		return 1 === absint( get_option( 'banner_enabled' ) );
	}

	public static function title() {
		return get_option( 'banner_title' );
	}

	public static function description() {
		return get_option( 'banner_description' );
	}

	public static function link() {
		return get_option( 'banner_link' );
	}

	public static function image_id() {
		return get_option( 'banner_image' );
	}
}
