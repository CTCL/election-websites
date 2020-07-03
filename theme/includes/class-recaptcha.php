<?php
namespace CTCL\ElectionWebsite;

class Recaptcha {

	const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

	public static function hooks() {
		add_action( 'admin_menu', [ __CLASS__, 'register_submenu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function register_submenu() {
		add_submenu_page( 'elections', 'ReCAPTCHA', 'ReCAPTCHA', 'manage_options', 'recaptcha', [ __CLASS__, 'options_page' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'recaptcha_section',
			false,
			false,
			'recaptcha_fields'
		);

		$fields = [
			'recaptcha_fields' =>
			[
				[
					'uid'         => 'recaptcha_site_key',
					'label'       => 'Site Key',
					'section'     => 'recaptcha_section',
					'type'        => 'text',
					'placeholder' => 'Site Key',
					'label_for'   => 'recaptcha_site_key',
				],
				[
					'uid'         => 'recaptcha_secret_key',
					'label'       => 'Secret Key',
					'section'     => 'recaptcha_section',
					'type'        => 'text',
					'placeholder' => 'Secret Key',
					'label_for'   => 'recaptcha_secret_key',
				],
			],
		];

		\CTCL\ElectionWebsite\Settings::configure_fields( $fields );
	}

	public static function get_site_key() {
		return get_option( 'recaptcha_site_key' );
	}

	public static function get_secret_key() {
		return get_option( 'recaptcha_secret_key' );
	}

	public static function wp_enqueue_scripts() {
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [], THEME_VERSION, true );
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

	public static function options_page() {
		?>
		<form method="post" action="options.php">
			<h2>ReCAPTCHA</h2>
			<?php
				settings_fields( 'recaptcha_fields' );
			if ( filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING ) ) {
				\CTCL\ElectionWebsite\Settings::admin_notice();
			}
				do_settings_sections( 'recaptcha_fields' );
				submit_button();
			?>
		</form>
		<?php
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Recaptcha', 'hooks' ] );
