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

	const INLINE_IMAGE_CACHE_KEY = 'inline-images';

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

	/**
	 * Upload an image to the media library.
	 *
	 * @param string $file         Image URL.
	 * @param string $description  Image description.
	 *
	 * @return boolean|WP_Error
	 */
	public static function upload_image( $file, $description ) {
		$file_array = [
			'name'     => wp_basename( $file ),
			'tmp_name' => download_url( $file ),
		];

		// If error storing temporarily, return the error.
		if ( is_wp_error( $file_array['tmp_name'] ) ) {
			return $file_array['tmp_name'];
		}

		// Do the validation and storage stuff.
		$id = media_handle_sideload( $file_array, 0, $description );

		// If error storing permanently, unlink.
		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Forbidden,WordPress.PHP.NoSilencedErrors.Discouraged,WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
			return $id;
		}

		return true;
	}

	/**
	 * Output a data URL for an SVG.
	 *
	 * @param string $svg_data   SVG XML data.
	 *
	 * @return string
	 */
	public static function inline_svg_url( $svg_data ) {
		return 'data:image/svg+xml,' . str_replace( [ '%20', '%3D', '%3A', '%2F' ], [ ' ', '=', ':', '/' ], rawurlencode( $svg_data ) );

	}

	/**
	 * Generate an <img> tag for an inline SVG.
	 *
	 * @param string  $file    Path to SVG file.
	 * @param integer $height  Image height.
	 * @param integer $width   Image width.
	 * @param string  $alt     Image alt text.
	 *
	 * @return string
	 */
	public static function inline_svg_tag( $file, $height, $width, $alt = '' ) {
		if ( ! file_exists( $file ) ) {
			return;
		}

		$svg_data = file_get_contents( $file ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown

		$data_url = self::inline_svg_url( $svg_data );

		return '<img width="' . absint( $width ) . '" height="' . absint( $height ) . '" alt="' . esc_attr( $alt ) . '" src="' . esc_url( $data_url ) . '" />';
	}

	/**
	 * Output the image with a data URL.
	 *
	 * @param integer $image_id   Image ID.
	 * @param string  $size        Image size.
	 * @param string  $alt         Image description.
	 *
	 * @return string
	 */
	public static function inline_image( $image_id, $size, $alt = false ) {
		$cache_key = 'inline_image_' . $image_id;
		$html      = wp_cache_get( $cache_key );
		if ( false !== $html ) {
			return $html;
		}

		$html = wp_get_attachment_image( $image_id, $size, false, [ 'alt' => $alt ] );

		$matches = [];

		if ( ! preg_match( '/src="([^"]+)"/', $html, $matches ) ) {
			return;
		}

		if ( ! isset( $matches[1] ) ) {
			return;
		}

		$file_path  = get_attached_file( $image_id );
		$image_data = file_get_contents( $file_path ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
		if ( ! $image_data ) {
			return;
		}

		$mime_type = mime_content_type( $file_path );

		if ( 'image/svg' === $mime_type ) {
			$data_url = self::inline_svg_url( $image_data );
		} else {
			$base64_image_data = base64_encode( $image_data );
			$data_url          = 'data:' . $mime_type . ';base64,' . $base64_image_data;
		}

		$html = str_replace( $matches[0], 'src="' . $data_url . '"', $html );

		wp_cache_set( $cache_key, $html, self::INLINE_IMAGE_CACHE_KEY, 600 );

		return $html;
	}

	/**
	 * Output class="error" when error array contains index.
	 *
	 * @param array  $array   The array of errors.
	 * @param string $index  An index in the array.
	 */
	public static function error_class( $array, $index ) {
		if ( isset( $array[ $index ] ) && $array[ $index ] ) {
			echo ' class="error"';
		}
	}

	/**
	 * Format item as error message.
	 *
	 * @param array  $array   The array of errors.
	 * @param string $index   The index in the array to retrieve.
	 */
	public static function error_message( $array, $index ) {
		if ( isset( $array[ $index ] ) && $array[ $index ] ) {
			echo '<span class="error">' . esc_html( $array[ $index ] ) . '</span>';
		}
	}
}
