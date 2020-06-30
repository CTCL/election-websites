<?php
namespace CTCL\ElectionWebsite;

class Recaptcha {

	const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

	public static function hooks() {
		add_action( 'admin_menu', [ __CLASS__, 'register_options_page' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function register_options_page() {
		add_options_page( 'ReCAPTCHA', 'ReCAPTCHA', 'manage_options', 'recaptcha', [ __CLASS__, 'options_page' ] );
	}

	public static function register_settings() {
		register_setting( 'recaptcha_options_group', 'repatcha_site_key' );
		register_setting( 'recaptcha_options_group', 'repatcha_secret_key' );
	}

	public static function get_site_key() {
		return get_option( 'repatcha_site_key' );
	}

	public static function get_secret_key() {
		return get_option( 'repatcha_secret_key' );
	}

	public static function wp_enqueue_scripts() {
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [], THEME_VERSION, true );
	}

	public static function verify( $response, $ip_address ) {
		$secret = self::get_secret_key();
		if ( ! $secret ) {
			return false;
		}

		$parameters = array_filter( [
			'secret'   => $secret,
			'response' => $response,
			'remoteip' => $ip_address,
		] );

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

		/*
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

		<h2>ReCAPTCHA</h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'recaptcha_options_group' ); ?>

			<table>
				<tr>
					<th><label for="repatcha_site_key">Site Key</label></th>
					<td><input type="text" size="50" id="repatcha_site_key" name="repatcha_site_key" value="<?php echo esc_attr( get_option( 'repatcha_site_key' ) ); ?>" /></td>
				</tr>

				<tr>
					<th><label for="repatcha_secret_key">Secret Key</label></th>
					<td><input type="text" size="50" id="repatcha_secret_key" name="repatcha_secret_key" value="<?php echo esc_attr( get_option( 'repatcha_secret_key' ) ); ?>" /></td>
				</tr>
			</table>

			<?php submit_button(); ?>
			</form>

		<?php
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Recaptcha', 'hooks' ] );
