<?php
/**
 * Contact form submitted successfully.
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

$file = get_template_directory() . '/assets/images/checkmark.svg';
?>
<h1><?php echo wp_kses_post( \CTCL\Elections\Helpers::inline_svg_tag( $file, 28, 28 ) ); ?>
	Thank you for reaching out!</h1>

<p>We’ve received your message and will respond as soon as possible. In the meantime, you may find helpful information in our <a href="/faq/">Frequently Asked Questions</a>.</p>

<p><a href="javascript:window.history.back();">Go back</a></p>
