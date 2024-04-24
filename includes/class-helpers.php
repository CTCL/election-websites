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

		// strip leading 1s.
		if ( '1' === $phone[0] ) {
			$phone = substr( $phone, 1 );
		}

		return sprintf( '(%s) %s-%s', substr( $phone, 0, 3 ), substr( $phone, 3, 3 ), substr( $phone, 6, 4 ) );
	}

	/**
	 * Format phone extensions.
	 *
	 * @param string $extension  Extension.
	 *
	 * @return string
	 */
	public static function format_phone_extension( $extension ) {
		return filter_var( $extension, FILTER_SANITIZE_NUMBER_INT );
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
	 * Format a phone number as an aria-label.
	 *
	 * @param string $phone   Phone number.
	 * @param string $extension  Phone extension.
	 *
	 * @return string
	 */
	public static function format_phone_aria_label( $phone, $extension = false ) {
		$aria_label = preg_replace( '/[^\d]/', '', $phone );
		$aria_label = preg_replace( '/(\d)/', '${1} ', $aria_label );
		$aria_label = sprintf( '%s.%s.%s', substr( $aria_label, 0, 5 ), substr( $aria_label, 6, 5 ), trim( substr( $aria_label, 11, 8 ) ) );

		if ( $extension ) {
			$extension   = preg_replace( '/(\d)/', ' ${1}', $extension );
			$aria_label .= ' x' . $extension;
		}

		return $aria_label;
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
		return defined( 'REST_REQUEST' ) && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	}

	/**
	 * Upload an image to the media library.
	 *
	 * @param string $file       Image URL.
	 * @param string $description  Image description.
	 *
	 * @return boolean|WP_Error
	 */
	public static function upload_image( $file, $description ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

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
	 * @param string  $file Path to SVG file.
	 * @param integer $height  Image height.
	 * @param integer $width   Image width.
	 * @param string  $alt   Image alt text.
	 *
	 * @return string
	 */
	public static function inline_svg_tag( $file, $height, $width, $alt = '' ) {
		if ( ! file_exists( $file ) ) {
			return;
		}

		$cache_key = 'inline_image_file_' . basename( $file );
		$html      = wp_cache_get( $cache_key );
		if ( false !== $html ) {
			return $html;
		}

		$svg_data = file_get_contents( $file ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown

		$data_url = self::inline_svg_url( $svg_data );

		$html = '<img width="' . absint( $width ) . '" height="' . absint( $height ) . '" alt="' . esc_attr( $alt ) . '" src="' . esc_url( $data_url ) . '" />';

		wp_cache_set( $cache_key, $html, self::INLINE_IMAGE_CACHE_KEY, 600 );

		return $html;
	}

	/**
	 * Output the image with a data URL.
	 *
	 * @param integer $image_id  Image ID.
	 * @param string  $size      Image size.
	 * @param string  $alt       Image description.
	 *
	 * @return string
	 */
	public static function inline_image( $image_id, $size, $alt = false ) {
		$cache_key = 'inline_image_' . $image_id . '_' . $size;
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

		/*
		 * We have to do a bunch of work here to be compatible with WordPress.com.
		 *
		 * On WordPress.com, the thumbnail sizes don't exist in the filesystem, so
		 * /srv/htdocs/wp-content/uploads/2021/01/foo-244x205.png does not exist
		 * but
		 * /srv/htdocs/wp-content/uploads/2021/01/foo.png exists
		 *
		 * We call file_get_contents() on the URL, which exists for both
		 * WPCOM and non-WPCOM sites.
		 *
		 * Separately, construct the filesystem path to the full-sized image
		 * so we can call mime_content_type() on it, since you can't get
		 * MIME types from URLs.
		*/

		// Upload directory. Needed for URL and path construction.
		$upload_directory = wp_get_upload_dir();
		if ( ! isset( $upload_directory['basedir'] ) ) {
			return;
		}

		// Array of metadata. Need to get the relative path.
		$image_metadata = wp_get_attachment_metadata( $image_id );
		if ( ! isset( $image_metadata['file'] ) ) {
			return;
		}

		// Relative path to the original image, i.e. 2020/02/blah.png.
		$image_relative_path = $image_metadata['file'];

		// Full filesystrem path to the original image, i.e.
		$filesystem_path = $upload_directory['baseurl'] . '/' . $image_relative_path;

		// Get thumbnail image path, if it exists (i.e. image > $size px).
		$imagedata = image_get_intermediate_size( $image_id, $size );

		// If the image is over $size px wide, image_get_intermediate_size() returns an array,
		// so get a $size px thumbnail. Otherwise, use the full-sized image.
		if ( is_array( $imagedata ) && isset( $imagedata['path'] ) && $imagedata['path'] ) {

			// URL for thumnail (not filesystem path because of WPCOM).
			$image_url = $upload_directory['baseurl'] . '/' . dirname( $image_relative_path ) . '/' . $imagedata['file'];

		} else {
			// Filesystem path for full-sized image.
			$image_url = $filesystem_path;
		}

		$image_data = file_get_contents( $image_url ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
		if ( ! $image_data ) {
			return;
		}

		// Read the MIME type using a file path, not a URL.
		$mime_type = mime_content_type( $upload_directory['basedir'] . '/' . $image_relative_path );

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
	 * @param array  $error_array   The array of errors.
	 * @param string $index  An index in the array.
	 */
	public static function error_class( $error_array, $index ) {
		if ( isset( $error_array[ $index ] ) && $error_array[ $index ] ) {
			echo ' class="error"';
		}
	}

	/**
	 * Format item as error message.
	 *
	 * @param array  $error_array   The array of errors.
	 * @param string $index   The index in the array to retrieve.
	 */
	public static function error_message( $error_array, $index ) {
		if ( isset( $error_array[ $index ] ) && $error_array[ $index ] ) {
			echo '<span class="error">' . esc_html( $error_array[ $index ] ) . '</span>';
		}
	}
}
