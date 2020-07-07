<?php
namespace CTCL\ElectionWebsite;

class Helpers {

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

	public static function format_phone_number( $p ) {
		$p = preg_replace( '/[^\d]/', '', $p );
		if ( ! $p ) {
			return '';
		}

		return sprintf( '(%s) %s-%s', substr( $p, 0, 3 ), substr( $p, 3, 3 ), substr( $p, 6, 4 ) );
	}

	public static function format_zip( $s ) {
		return substr( preg_replace( '/[^\d]/', '', $s ), 0, 5 );
	}

	public static function format_twitter( $s ) {
		return substr( preg_replace( '/[^A-Z0-9_]/i', '', $s ), 0, 15 );
	}

	public static function format_facebook( $s ) {
		return preg_replace( '/[^A-Z0-9\.]/i', '', $s );
	}

	public static function format_instagram( $s ) {
		return preg_replace( '/[^A-Z0-9_\.]/i', '', $s );
	}

	public static function validate_state( $s ) {
		$state_list = self::state_list();

		if ( in_array( $s, array_keys( $state_list ), true ) ) {
			return $s;
		}

		return '';
	}

	public static function is_block_backend() {
		return defined( 'REST_REQUEST' ) && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context', FILTER_SANITIZE_STRING );
	}
}
