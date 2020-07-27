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

	// TODO: detect presence of form (in case page is renamed); maybe add to block.

	/**
	 * Set up actions and filters.
	 */
	public static function hooks() {
		if ( is_page( 'about-us' ) ) {
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
	 * Validate the contact form content as an email.
	 *
	 * @return array
	 */
	public static function validate() {
		$errors = [];

		$fullname = trim( wp_strip_all_tags( filter_input( INPUT_POST, 'fullname', FILTER_SANITIZE_STRING ) ) );
		$email    = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
		$topic    = filter_input( INPUT_POST, 'topic', FILTER_SANITIZE_STRING );
		$message  = trim( wp_strip_all_tags( filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING ) ) );

		if ( ! $fullname ) {
			$errors[] = 'Please enter your name';
		}

		if ( ! $email ) {
			$errors[] = 'Please enter your email address';
		}

		if ( ! $topic || ! in_array( $topic, self::topic_list(), true ) ) {
			$topic    = '';
			$errors[] = 'Please select a topic';
		}

		if ( ! $message ) {
			$errors[] = 'Please enter a message';
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
	 * Send the contact form content as an email.
	 *
	 * @param array $atts Form contents (sender, subject, message body).
	 *
	 * @return string
	 */
	public static function send_message( $atts ) {
		$recipient = \CTCL\Elections\Office_Details::email_address();
		$subject   = $atts['topic'];
		$sender    = sprintf( '"%s" <%s>', $atts['fullname'], $atts['email'] );
		$headers   = [
			'Reply-To' => $sender,
		];
		$message   = sprintf( "From: %s\n\n%s", $sender, $atts['message'] );

		if ( 'production' === WP_ENVIRONMENT ) {
			$result = wp_mail( $recipient, $subject, $message, $headers ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail
		} else {
			$result = true;
		}

		if ( $result ) {
			return 'Thanks! Your message has been sent.';
		} else {
			return 'Your message failed to send. Please try again later.';
		}
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
			$permalink = admin_url( 'admin.php?page=' . Settings::MENU_SLUG );
			return 'Please specify an email address in <a href="' . esc_url( $permalink ) . '">Settings > Office Details</a>';
		}

		$nonce = filter_input( INPUT_POST, self::NONCE_KEY, FILTER_SANITIZE_STRING );

		// Initial load; show the form.
		if ( ! $nonce ) {
			return self::render();
		}

		// Nonce is present; validate the form.
		$validation_result = self::validate();
		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			$validation_result['errors'][] = 'Re-submit the form with the nonce present';
			return self::render( $validation_result );
		}

		// If no ReCAPTCHA, process the form.
		$recaptcha_enabled = Google_Recaptcha::is_configured();
		if ( ! $recaptcha_enabled ) {
			if ( $validation_result['errors'] ) {
				return self::render( $validation_result );
			} else {
				return self::send_message( $validation_result );
			}
		}

		// If ReCAPTCHA, validate the token, and process the form if the token is valid.
		$token = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );
		if ( ! $token ) {
			$validation_result['errors'][] = 'Re-submit the form with the token present';
			return self::render( $validation_result );
		}

		if ( $validation_result['errors'] ) {
			return self::render( $validation_result );
		} else {
			$ip_address = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );

			$recaptcha_result = Google_Recaptcha::verify( $token, $ip_address );
			if ( $recaptcha_result ) {
				return self::send_message( $validation_result );
			} else {
				$validation_result['errors'][] = 'ReCAPTCHA could not validate you are a human';
				return self::render( $validation_result );
			}
		}

		return self::render();
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

		ob_start();
		?>
		<form class="contact-form" id="contact-form" action="<?php the_permalink(); ?>" method="post">

			<?php
			wp_nonce_field( self::NONCE_ACTION, self::NONCE_KEY );
			if ( $attr['errors'] ) {
				?>
				<p>Please correct the following errors:</p>
				<ul class="errors">
				<?php
				foreach ( $attr['errors'] as $error ) {
					echo '<li>' . esc_html( $error ) . '</li>';
				}
				?>
				</ul>
				<?php
			}
			?>

			<p>
				<label for="contact-fullname">Name</label>
				<input id="contact-fullname" type="text" name="fullname" value="<?php echo esc_attr( $attr['fullname'] ); ?>" />
			</p>

			<p>
				<label for="contact-name">Email address</label>
				<input id="contact-email" type="text" name="email" value="<?php echo esc_attr( $attr['email'] ); ?>" />
			</p>

			<p>
				<label for="contact-topic">Topic</label>
				<select id="topic" name="topic">
					<?php
					foreach ( self::topic_list() as $current_topic ) {
						echo '<option' . selected( $current_topic, $attr['topic'], false ) . '>' . esc_html( $current_topic ) . '</option>';
					}
					?>
				</select>
			</p>

			<p>
				<label for="contact-message">Message</label>
				<textarea id="contact-message" name="message" rows="10" cols="30"><?php echo esc_textarea( $attr['message'] ); ?></textarea>
			</p>


			<input id="recaptcha-token" type="hidden" name="token" />
			<button class="g-recaptcha" data-sitekey="<?php echo esc_attr( Google_Recaptcha::get_site_key() ); ?>" data-callback='window.ctcl.submitContactForm' data-action='submit'>Send Message</button>
		</form>

		<?php
		return ob_get_clean();
	}
}

add_action( 'wp', [ '\CTCL\Elections\Contact_Form', 'hooks' ] );
