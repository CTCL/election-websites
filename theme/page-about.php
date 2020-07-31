<?php
/**
 * Template Name: About Us
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

get_header();
?>
<main>
	<?php
	$nonce = filter_input( INPUT_POST, \CTCL\Elections\Contact_Form::NONCE_KEY, FILTER_SANITIZE_STRING );
	if ( $nonce ) {
		$validation_result = \CTCL\Elections\Contact_Form::validate( $nonce );

		if ( ! $validation_result['errors'] ) {
			$send_result = \CTCL\Elections\Contact_Form::send_message( $validation_result );
			if ( $send_result ) {
				get_template_part( 'template-parts/mail-success' );
			} else {
				$validation_result['errors']['form'] = 'Sending the message failed.';
				set_query_var( 'error', $validation_result['errors']['form'] );
				get_template_part( 'template-parts/mail-error' );
			}
		}
	}

	if ( ! $nonce || $validation_result['errors'] ) {
		if ( have_posts() ) {
			the_post();
			the_content();
		}
	}
	?>
</main>

<?php
get_footer();
