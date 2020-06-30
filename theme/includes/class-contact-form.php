<?php
namespace CTCL\ElectionWebsite;

class Contact_Form {

	public static function hooks() {
		add_shortcode( 'contactform', [ __CLASS__, 'render' ] );
	}

	public static function validate() {
		$errors = [];

		$name    = trim( wp_strip_all_tags( filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING ) ) );
		$email   = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
		$topic   = filter_input( INPUT_POST, 'topic', FILTER_SANITIZE_STRING );
		$message = trim( wp_strip_all_tags( filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING ) ) );

		if ( ! $name ) {
			$errors[] = 'Please enter your name';
		}

		if ( ! $email ) {
			$errors[] = 'Please enter your email address';
		}

		if ( ! $message ) {
			$errors[] = 'Please enter a message';
		}

		$default_topic = 'Other';
		$topic_list = [
			'Data Request',
			'Shoes',
			$default_topic,
		];

		if ( ! in_array( $topic, $topic_list, true ) ) {
			$topic = $default_topic;
		}

		return [ 'name' => $name, 'email' => $email, 'topic' => $topic, 'message' => $message, 'errors' => $errors ];
	}


	public static function render( $atts, $content = null ) {
		$token             = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );
		$show_form         = true;
		$validation_result = [ 'name' => '', 'email' => '', 'topic' => '', 'message' => '' ];

		Recaptcha::wp_enqueue_scripts();

		if ( $token ) {
			$validation_result = self::validate();

			if ( ! $validation_result['errors'] ) {
				$ip_address = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );

				$recaptcha_result = Recaptcha::verify( $token, $ip_address );
				if ( $recaptcha_result ) {
					$show_form = false;
					print "success";
				}
			}
		}

		if ( $show_form ) {
			ob_start();
		?>
		<form id="contact-form" action="<?php the_permalink(); ?>" method="post">

			<?php
			if ( isset( $validation_result['errors'] ) && $validation_result['errors'] ) {
				?>
				<p>Please correct the following errors:</p>
				<ul>
				<?php
				foreach ( $validation_result['errors'] as $error ) {
					echo "<li>" . esc_html( $error ) . "</li>";
				}
				?>
				</ul>
			<?php
			}
			?>

			<p>
				<label for="contact-name">Name</label>
				<input id="contact-email" type="text" name="name" value="<?php echo esc_attr( $validation_result['name'] ); ?>" />
			</p>

			<p>
				<label for="contact-name">Email address</label>
				<input id="contact-email" type="text" name="email" value="<?php echo esc_attr( $validation_result['email'] ); ?>" />
			</p>

			<p>
				<label for="contact-topic">Topic</label>
				<select id="topic" name="topic">
					<option>Data request</option>
				</select>
			</p>

			<p>
				<label for="contact-message">Message</label>
				<textarea id="contact-message" name="message" rows="10" cols="30">
				<?php echo esc_textarea( $validation_result['message'] ); ?>
				</textarea>
			</p>


			<input id="recaptcha-token" type="hidden" name="token" />
			<button class="g-recaptcha" data-sitekey="<?php echo esc_attr( Recaptcha::get_site_key() ); ?>" data-callback='submitContactForm' data-action='submit'>Send Message</button>
		</form>

		<?php
		} // else
		return ob_get_clean();
	}

}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Contact_Form', 'hooks' ] );
