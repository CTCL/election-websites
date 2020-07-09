<?php
/**
 * Helpers to get office details.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Office Details class.
 *
 * Helpers to get office details.
 */
class Office_Details {

	/**
	 * Allowed HTML
	 *
	 * @var array
	 */
	public static $allowed_link_tags = [ 'a' => [ 'href' => true ] ];

	/**
	 * The election official name.
	 *
	 * @return string
	 */
	public static function official() {
		return get_option( 'official_name' );
	}

	/**
	 * The election official title.
	 *
	 * @return string
	 */
	public static function title() {
		return get_option( 'official_title' );
	}

	/**
	 * The election office phone number.
	 *
	 * @return string
	 */
	public static function phone() {
		return get_option( 'phone' );
	}

	/**
	 * The election office email address.
	 *
	 * @param boolean $link  Whether to render this as a link (instead of plain text).
	 * @param boolean $echo  Whether to print this (instead of returning it).
	 *
	 * @return string
	 */
	public static function email( $link = false, $echo = false ) {
		$email = get_option( 'email_address' );

		if ( $link ) {
			$result = sprintf( '<a href="%s">%s</a>', esc_url( 'mailto:' . $email ), $email );
		} else {
			$result = $email;
		}

		if ( $echo ) {
			echo wp_kses( $result, self::$allowed_link_tags );
		} else {
			return $email;
		}
	}
}
