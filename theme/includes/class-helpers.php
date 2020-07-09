<?php
/**
 * Helper functions called directly, and not from initialization actions.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Helper functions.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */
class Helpers {
	/**
	 * List US states and territories.
	 *
	 * @return array
	 */
	public static function state_list() {
		return [
			''   => 'Select a State',
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AS' => 'American Samoa',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'DC' => 'District of Columbia',
			'FM' => 'Federated States of Micronesia',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'GU' => 'Guam',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MH' => 'Marshall Islands',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'MP' => 'Northern Mariana Islands',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PW' => 'Palau',
			'PA' => 'Pennsylvania',
			'PR' => 'Puerto Rico',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VI' => 'Virgin Islands',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming',
		];
	}

	/**
	 * Format US phone numbers.
	 *
	 * @param string $phone  Phone number.
	 *
	 * @return string
	 */
	public static function format_phone_number( $phone ) {
		$phone = preg_replace( '/[^\d]/', '', $phone );
		if ( ! $phone ) {
			return '';
		}

		return sprintf( '(%s) %s-%s', substr( $phone, 0, 3 ), substr( $phone, 3, 3 ), substr( $phone, 6, 4 ) );
	}

	/**
	 * Format 5-digit zip codes.
	 *
	 * @param string $zip  Zip code.
	 *
	 * @return string
	 */
	public static function format_zip( $zip ) {
		return substr( preg_replace( '/[^\d]/', '', $zip ), 0, 5 );
	}

	/**
	 * Format Twitter usernames.
	 *
	 * @param string $username  Username.
	 *
	 * @return string
	 */
	public static function format_twitter( $username ) {
		return substr( preg_replace( '/[^A-Z0-9_]/i', '', $username ), 0, 15 );
	}

	/**
	 * Format Facebook usernames.
	 *
	 * @param string $username  Username.
	 *
	 * @return string
	 */
	public static function format_facebook( $username ) {
		return preg_replace( '/[^A-Z0-9\.]/i', '', $username );
	}

	/**
	 * Format Instagram usernames.
	 *
	 * @param string $username  Username.
	 *
	 * @return string
	 */
	public static function format_instagram( $username ) {
		return preg_replace( '/[^A-Z0-9_\.]/i', '', $username );
	}

	/**
	 * Ensure state is from list of valid state abbreviations.
	 *
	 * @param string $state  State abbreviation.
	 *
	 * @return string
	 */
	public static function validate_state( $state ) {
		$state_list = self::state_list();

		if ( in_array( $state, array_keys( $state_list ), true ) ) {
			return $state;
		}

		return '';
	}

	/**
	 * Determine if we are in the backend while rendering a block.
	 *
	 * @return boolean
	 */
	public static function is_block_backend() {
		return defined( 'REST_REQUEST' ) && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context', FILTER_SANITIZE_STRING );
	}
}
