<?php
namespace CTCL\ElectionWebsite;

class Recaptcha {

	const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

	public static function get_site_key() {
		return get_option( 'recaptcha_site_key' );
	}

	public static function get_secret_key() {
		return get_option( 'recaptcha_secret_key' );
	}

	// TODO: detect presence of form (in case page is renamed); maybe add to block
	public static function wp_enqueue_scripts() {
		if ( is_page( 'about-us' ) ) {
			wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [], THEME_VERSION, true );
		}
	}

	public static function verify( $response, $ip_address ) {
		$secret = self::get_secret_key();
		if ( ! $secret ) {
			return false;
		}

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
