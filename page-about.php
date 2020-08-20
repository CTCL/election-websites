<?php
/**
 * Template Name: About Us
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

get_header();
?>
<main <?php post_class(); ?>>
	<?php
	$nonce = filter_input( INPUT_POST, \CTCL\Elections\Contact_Form::NONCE_KEY, FILTER_SANITIZE_STRING );
	if ( $nonce ) {
		$validation_result = \CTCL\Elections\Contact_Form::validate( $nonce );

		if ( ! $validation_result['errors'] ) {
			$send_result = \CTCL\Elections\Contact_Form::send_message( $validation_result );
			if ( $send_result ) {
				get_template_part( 'template-parts/mail-success' );
			} else {
				set_query_var( 'error', 'Sending the message failed.' );
				get_template_part( 'template-parts/mail-error' );
			}
		}
	}

	if ( ! $nonce || $validation_result['errors'] ) {
		echo '<h1>' . esc_html( get_the_title() ) . '</h1>';

		if ( have_posts() ) {
			the_post();
			the_content();
		}
	}
	?>
</main>

<?php
get_footer();
