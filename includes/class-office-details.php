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
	public static $allowed_link_tags = [
		'a' => [
			'href'       => true,
			'aria-label' => true,
		],
	];

	/**
	 * Allowed HTML in postal address and hours.
	 *
	 * @var array
	 */
	public static $allowed_br_tag = [
		'br' => [],
	];

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
		return get_option( 'ctcl_official_name' );
	}

	/**
	 * The election official title.
	 *
	 * @return string
	 */
	public static function official_title() {
		return get_option( 'ctcl_official_title' );
	}

	/**
	 * The election office phone number.
	 *
	 * @param boolean $link  Whether to render this as a link (instead of plain text).
	 *
	 * @return string
	 */
	public static function phone( $link = false ) {
		$phone = get_option( 'ctcl_phone' );
		if ( ! $phone ) {
			return;
		}

		$phone_digits = preg_replace( '/[^\d]/', '', $phone );

		$extension = get_option( 'ctcl_phone_extension' );
		if ( $extension ) {
			$phone        = sprintf( '%s x%s', $phone, $extension );
			$phone_digits = sprintf( '%s;%s', $phone_digits, $extension );
		}

		$aria_label = \CTCL\Elections\Helpers::format_phone_aria_label( $phone, $extension );

		return self::get_link( $phone, 'tel:' . $phone_digits, $link, false, $aria_label );
	}

	/**
	 * The election office fax number.
	 *
	 * @param boolean $link  Whether to render this as a link (instead of plain text).
	 *
	 * @return string
	 */
	public static function fax( $link = false ) {
		$fax = get_option( 'ctcl_fax' );
		if ( ! $fax ) {
			return;
		}

		$aria_label = 1;

		return self::get_link( $fax, 'tel:' . $fax, $link, false, $aria_label );
	}

	/**
	 * The election office address.
	 *
	 * @return string
	 */
	public static function address() {
		return get_option( 'ctcl_address' );
	}

	/**
	 * The election office address, line 2.
	 *
	 * @return string
	 */
	public static function address2() {
		return get_option( 'ctcl_address2' );
	}

	/**
	 * The election office city.
	 *
	 * @return string
	 */
	public static function city() {
		return get_option( 'ctcl_city' );
	}

	/**
	 * The election office state.
	 *
	 * @return string
	 */
	public static function state() {
		return get_option( 'ctcl_state' );
	}

	/**
	 * The election office zip code.
	 *
	 * @return string
	 */
	public static function zip() {
		return get_option( 'ctcl_zip' );
	}

	/**
	 * The election office hours.
	 *
	 * @return string
	 */
	public static function hours() {
		return get_option( 'ctcl_hours' );
	}


	/**
	 * The election office mailing address.
	 *
	 * @return string
	 */
	public static function mailing_address() {
		$address_parts = array_filter(
			[
				self::address(),
				self::address2(),
			]
		);
		
		$street         = join( "\n", $address_parts );
		$city_state_zip = sprintf( '%s, %s %s', self::city(), self::state(), self::zip() );

		$address = "";
		
		if(!empty($street)){
			$address .= $street;
		}
		
		if(trim($city_state_zip) != ","){
			if(!empty($street)){
				$address .= "\n";
			}
			
			$address .= $city_state_zip;
		}
		
		return nl2br($address);
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
		$handle = get_option( 'ctcl_twitter' );
		if ( ! $handle ) {
			return;
		}

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
		$handle = get_option( 'ctcl_facebook' );
		if ( ! $handle ) {
			return;
		}

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
		$handle = get_option( 'ctcl_instagram' );
		if ( ! $handle ) {
			return;
		}

		return self::get_link( 'Instagram', 'https://www.instagram.com/' . $handle, $link, $echo );
	}

	/**
	 * Print or return a link.
	 *
	 * @param string  $label       The link label (social media handle).
	 * @param boolean $url         The link URL.
	 * @param boolean $link        Whether to render this as a link (instead of plain text).
	 * @param boolean $echo        Whether to print this (instead of returning it).
	 * @param string  $aria_label  The aria-label attribute for the link (optional).
	 *
	 * @return string
	 */
	public static function get_link( $label, $url, $link, $echo = false, $aria_label = false ) {
		if ( $link && $aria_label ) {
			$result = sprintf( '<a href="%s" aria-label="%s">%s</a>', esc_url( $url ), esc_attr( $aria_label ), esc_html( $label ) );
		} elseif ( $link ) {
			$result = sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $label ) );
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
