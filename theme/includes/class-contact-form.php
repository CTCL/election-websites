<?php
namespace CTCL\ElectionWebsite;

class Contact_Form {

	public static $default_topic = 'Other';

	public static function setup_hooks() {
		add_shortcode( 'contactform', [ __CLASS__, 'render' ] );

		// KSES: Allow additional tags/attributes
		add_action( 'init', [ __CLASS__, 'kses_allow_additional_tags' ] );
	}

	// TODO: detect presence of form (in case page is renamed); maybe add to block
	public static function hooks() {
		if ( is_page( 'about-us' ) ) {
			add_action( 'wp_enqueue_scripts', [ '\CTCL\ElectionWebsite\Recaptcha', 'wp_enqueue_scripts' ] );
		}
	}

	// TODO: Get list from settings page
	public static function topic_list() {
		return [
			'Data Request',
			'Shoes',
			self::$default_topic,
		];
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

		if ( ! in_array( $topic, self::$topic_list, true ) ) {
			$topic = self::$default_topic;
		}

		return [
			'name'    => $name,
			'email'   => $email,
			'topic'   => $topic,
			'message' => $message,
			'errors'  => $errors,
		];
	}


	public static function render( $atts, $content = null ) {
		$token             = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );
		$show_form         = true;
		$validation_result = [
			'name'    => '',
			'email'   => '',
			'topic'   => '',
			'message' => '',
		];

		if ( $token ) {
			$validation_result = self::validate();

			if ( ! $validation_result['errors'] ) {
				$ip_address = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );

				$recaptcha_result = Recaptcha::verify( $token, $ip_address );
				if ( $recaptcha_result ) {
					$show_form = false;
					print 'success';
				}
			}
		}

		if ( $show_form ) {
			ob_start();
			?>
		<form class="contact-form" id="contact-form" action="<?php the_permalink(); ?>" method="post">

			<?php
			if ( isset( $validation_result['errors'] ) && $validation_result['errors'] ) {
				?>
				<p>Please correct the following errors:</p>
				<ul>
				<?php
				foreach ( $validation_result['errors'] as $error ) {
					echo '<li>' . esc_html( $error ) . '</li>';
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
					<?php
					foreach ( self::topic_list() as $current_topic ) {
						echo '<option>' . esc_html( $current_topic ) . '</option>';
					}
					?>
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

	/**
	 * Allow additional tags and attributes.
	 */
	public static function kses_allow_additional_tags() {
		global $allowedposttags;

		$style_attributes = [
			'class' => true,
			'id'    => true,
			'style' => true,
		];

		$allowed_tags_data = [
			'form'   => array_merge(
				$style_attributes,
				[
					'action' => true,
					'method' => true,
				]
			),

			'select' => array_merge(
				$style_attributes,
				[
					'name' => true,
				]
			),

			'option' => array_merge(
				$style_attributes,
				[
					'value'    => true,
					'selected' => true,
				]
			),

			'input'  => array_merge(
				$style_attributes,
				[
					'name'        => true,
					'value'       => true,
					'placeholder' => true,
					'type'        => true,
				]
			),
		];


		foreach ( $allowed_tags_data as $tag => $new_attributes ) {
			if ( ! isset( $allowedposttags[ $tag ] ) || ! is_array( $allowedposttags[ $tag ] ) ) {
				$allowedposttags[ $tag ] = []; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}

			$allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $new_attributes ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Contact_Form', 'setup_hooks' ] );
add_action( 'wp', [ '\CTCL\ElectionWebsite\Contact_Form', 'hooks' ] );
