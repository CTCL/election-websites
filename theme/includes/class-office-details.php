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
	 * The election office name.
	 *
	 * @return string
	 */
	public static function title() {
		return get_bloginfo( 'title' );
	}

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
	public static function official_title() {
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
	 * The election office Twitter account.
	 *
	 * @param boolean $link  Whether to render this as a link (instead of plain text).
	 * @param boolean $echo  Whether to print this (instead of returning it).
	 *
	 * @return string
	 */
	public static function twitter( $link = false, $echo = false ) {
		$handle = get_option( 'twitter' );

		return self::get_link( 'Twitter', 'https://www.twitter.com/' . $handle, $link, $echo );
	}

	/**
	 * The election office Facebook page.
	 *
	 * @param boolean $link  Whether to render this as a link (instead of plain text).
	 * @param boolean $echo  Whether to print this (instead of returning it).
	 *
	 * @return string
	 */
	public static function facebook( $link = false, $echo = false ) {
		$handle = get_option( 'facebook' );

		return self::get_link( 'Facebook', 'https://www.facebook.com/' . $handle, $link, $echo );
	}

	/**
	 * The election office Instagram account.
	 *
	 * @param boolean $link  Whether to render this as a link (instead of plain text).
	 * @param boolean $echo  Whether to print this (instead of returning it).
	 *
	 * @return string
	 */
	public static function instagram( $link = false, $echo = false ) {
		$handle = get_option( 'instagram' );

		return self::get_link( 'Instagram', 'https://www.instagram.com/' . $handle, $link, $echo );
	}

	/**
	 * Print or return a link.
	 *
	 * @param boolean $label  The link label (social media handle).
	 * @param boolean $url    The link URL.
	 * @param boolean $link   Whether to render this as a link (instead of plain text).
	 * @param boolean $echo   Whether to print this (instead of returning it).
	 *
	 * @return string
	 */
	public static function get_link( $label, $url, $link, $echo ) {
		if ( $link ) {
			$result = sprintf( '<a href="%s">%s</a>', esc_url( $url ), $label );
		} else {
			$result = $label;
		}

		if ( $echo ) {
			echo wp_kses( $result, self::$allowed_link_tags );
		} else {
			return $result;
		}
	}

	/**
	 * The election office email address.
	 *
	 * @param boolean $link  Whether to render this as a link (instead of plain text).
	 * @param boolean $echo  Whether to print this (instead of returning it).
	 *
	 * @return string
	 */
	public static function email_address( $link = false, $echo = false ) {
		$email = get_option( 'ctcl_email_address' );

		return self::get_link( $email, 'mailto:' . $email, $link, $echo );
	}
}
