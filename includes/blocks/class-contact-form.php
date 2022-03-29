<?php
/**
 * Render, validate and send the Contact Form block.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Contact Form class.
 *
 * Implements the contact form block.
 */
class Contact_Form {

	const DEFAULT_TOPIC = 'Other';
	const NONCE_KEY     = 'contact_form_nonce';
	const NONCE_ACTION  = 'contact_form_submit';

	/**
	 * Set up actions and filters.
	 */
	public static function hooks() {
		if ( is_page_template( 'page-about.php' ) ) {
			add_action( 'wp_enqueue_scripts', [ '\CTCL\Elections\Google_Recaptcha', 'wp_enqueue_scripts' ] );
		}
	}

	/**
	 * Whether or not the contact form is enabled. Requires an email address to be present in Settings > Office Details.
	 *
	 * @return array
	 */
	public static function is_enabled() {
		return strlen( \CTCL\Elections\Office_Details::email_address() ) > 0;
	}

	/**
	 * Get the list of topics.
	 *
	 * @return array
	 */
	public static function topic_list() {
		$topics = Topics::get_list();
		if ( is_array( $topics ) ) {
			array_unshift( $topics, '' );
			$topics[] = self::DEFAULT_TOPIC;
		} else {
			$topics = [ '', self::DEFAULT_TOPIC ];
		}

		return $topics;
	}

	/**
	 * Validate the contact form fields.
	 *
	 * @return array
	 */
	public static function validate_fields() {
		$errors = [];

		$fullname = trim( wp_strip_all_tags( filter_input( INPUT_POST, 'fullname', FILTER_SANITIZE_STRING ) ) );
		$email    = filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL );
		$topic    = filter_input( INPUT_POST, 'topic', FILTER_SANITIZE_STRING );
		$message  = trim( wp_strip_all_tags( filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING ) ) );

		if ( ! $fullname ) {
			$errors['fullname'] = 'Enter your name.';
		}

		if ( ! $email ) {
			$errors['email'] = 'Enter your email address.';
		}

		if ( get_option( 'audience' ) == 'voters' && ( ! $topic || ! in_array( $topic, self::topic_list(), true ) ) ) {
			$topic           = '';
			$errors['topic'] = 'Select a topic.';
		}

		if ( ! $message ) {
			$errors['message'] = 'Enter a message.';
		}

		return [
			'fullname' => $fullname,
			'email'    => $email,
			'topic'    => $topic,
			'message'  => $message,
			'errors'   => $errors,
		];
	}

	/**
	 * Validate the contact form fields, nonce and ReCAPTCHA.
	 *
	 * @param string $nonce The nonce.
	 *
	 * @return array
	 */
	public static function validate( $nonce ) {
		$validation_result = self::validate_fields();

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			$validation_result['errors']['form'] = 'The nonce was missing.';
		}

		// If ReCAPTCHA enabled, validate the token.
		$recaptcha_enabled = \CTCL\Elections\Google_Recaptcha::is_configured();
		if ( $recaptcha_enabled ) {
			$token = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );
			if ( $token ) {
				$ip_address = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP );

				$recaptcha_result = \CTCL\Elections\Google_Recaptcha::verify( $token, $ip_address );
				if ( ! $recaptcha_result ) {
					$validation_result['errors']['form'] = 'ReCAPTCHA could not validate you are a human.';
				}
			} else {
				$validation_result['errors']['form'] = 'The ReCATCHA token was missing.';
			}
		}

		return $validation_result;
	}

	/**
	 * Send the contact form content as an email.
	 *
	 * @param array $atts Form contents (sender, subject, message body).
	 *
	 * @return string
	 */
	public static function send_message( $atts ) {
		$recipient = \CTCL\Elections\Office_Details::email_address();
		$subject   = 'Contact Form';
		$sender    = sprintf( '%s <%s>', $atts['fullname'], $atts['email'] );
		
		$headers = [
			'Reply-To' => $sender,
		];
		$message = sprintf( "From: %s\n\n%s", $sender, $atts['message'] );

		if ( 'production' === wp_get_environment_type() ) {
			$result = wp_mail( $recipient, $subject, $message, $headers ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail
		} else {
			$result = true;
		}
		// var_dump ([$recipient, $subject, $message, $headers, $sender, $atts, $result]); exit();
		return $result;
	}

	/**
	 * Validate and render the Contact Form block.
	 *
	 * @param array  $block_attributes  Array of block attributes.
	 * @param string $content           Post content.
	 *
	 * @return string
	 */
	public static function block_render( $block_attributes, $content ) {
		if ( ! self::is_enabled() ) {
			$permalink = admin_url( 'admin.php?page=office_details' );
			return 'Please specify an email address in <a href="' . esc_url( $permalink ) . '">Settings > Office Details</a>';
		}

		global $validation_result;

		return self::render( $validation_result );
	}

	/**
	 * Render the Contact Form block.
	 *
	 * @param array $attr         Array of contact form attributes.
	 *
	 * @return string
	 */
	public static function render( $attr = null ) {
		$attr = shortcode_atts(
			[
				'fullname' => '',
				'email'    => '',
				'topic'    => '',
				'message'  => '',
				'errors'   => [],
			],
			$attr
		);

		$errors = $attr['errors'];

		ob_start();
		?>
		<form class="contact-form" id="contact-form" action="<?php the_permalink(); ?>" method="post">

			<?php
			wp_nonce_field( self::NONCE_ACTION, self::NONCE_KEY );
			if ( $errors ) {
				echo "<p class='error intro'>Please fix the errors below:</p>";
			}
			?>

			<p>
				<label for="contact-fullname">
					First and last name
					<?php \CTCL\Elections\Helpers::error_message( $errors, 'fullname' ); ?>
				</label>
				<input id="contact-fullname" type="text" name="fullname" value="<?php echo esc_attr( $attr['fullname'] ); ?>"<?php \CTCL\Elections\Helpers::error_class( $errors, 'fullname' ); ?>/>
			</p>

			<p>
				<label for="contact-email">
					Email address
					<?php \CTCL\Elections\Helpers::error_message( $errors, 'email' ); ?>
				</label>
				<input id="contact-email" type="email" name="email" value="<?php echo esc_attr( $attr['email'] ); ?>"<?php \CTCL\Elections\Helpers::error_class( $errors, 'email' ); ?>/>
			</p>
	
			<?php if ( get_option( 'audience' ) == 'voters' ) : ?>
				<p>
					<label for="content-topic">
						Topic
						<?php \CTCL\Elections\Helpers::error_message( $errors, 'topic' ); ?>
					</label>
					<select id="content-topic" name="topic">
						<?php foreach ( self::topic_list() as $topic ) : ?>
							<option><?= $topic ?></option>
						<?php endforeach ?>
					</select>
				</p>
			<?php endif ?>
			
			<p>
				<label for="contact-message">
					Message
					<?php \CTCL\Elections\Helpers::error_message( $errors, 'message' ); ?>
				</label>
				<textarea id="contact-message" name="message" rows="10" cols="30" <?php \CTCL\Elections\Helpers::error_class( $errors, 'message' ); ?>><?php echo esc_textarea( $attr['message'] ); ?></textarea>
			</p>


			<input id="recaptcha-token" type="hidden" name="token" />
			<button class="g-recaptcha" data-sitekey="<?php echo esc_attr( Google_Recaptcha::get_site_key() ); ?>" data-callback='ctclSubmitContactForm' data-action='submit'>Send Message</button>
		</form>

		<?php
		return ob_get_clean();
	}
}

add_action( 'wp', [ '\CTCL\Elections\Contact_Form', 'hooks' ] );
