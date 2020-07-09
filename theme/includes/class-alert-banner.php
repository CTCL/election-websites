<?php
/**
 * Helpers to get alert banner content.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Alert Banner class.
 *
 * Helpers to get alert banner content.
 */
class Alert_Banner {

	/**
	 * Whether or not the alert banner is enabled.
	 *
	 * @return boolean
	 */
	public static function is_enabled() {
		return 1 === absint( get_option( 'alert_banner_enabled' ) );
	}

	/**
	 * The alert banner title.
	 *
	 * @return string
	 */
	public static function title() {
		return get_option( 'alert_banner_title' );
	}

	/**
	 * The alert banner description.
	 *
	 * @return string
	 */
	public static function description() {
		return get_option( 'alert_banner_description' );
	}

	/**
	 * The alert banner URL.
	 *
	 * @return string
	 */
	public static function link() {
		return get_option( 'alert_banner_link' );
	}
}
