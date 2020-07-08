<?php
namespace CTCL\Elections;

class Recaptcha {

	const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

	public static function get_site_key() {
		return get_option( 'recaptcha_site_key' );
	}

	public static function get_secret_key() {
		return get_option( 'recaptcha_secret_key' );
	}

	public static function is_configured() {
		return self::get_site_key() && self::get_secret_key();
	}

	public static function wp_enqueue_scripts() {
		if ( ! self::is_configured() ) {
			return;
		}

		// Don't set a resource version here. We don't want query parameters passed to Google.
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}

	public static function verify( $response, $ip_address ) {
		if ( ! self::is_configured() ) {
			return false;
		}

		$secret = self::get_secret_key();

		$parameters = array_filter(
			[
				'secret'   => $secret,
				'response' => $response,
				'remoteip' => $ip_address,
			]
		);

		$response = wp_remote_post( self::API_URL, [ 'body' => $parameters ] );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return false;
		}

		$result_json = json_decode( $response['body'], true );

		if ( JSON_ERROR_NONE !== json_last_error() ) {
			return false;
		}

		/* phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			Result format
			{
				"success": true|false,
				"challenge_ts": timestamp,  // timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
				"hostname": string,         // the hostname of the site where the reCAPTCHA was solved
				"error-codes": [...]        // optional
			}
		*/

		return $result_json['success'];
	}
}
