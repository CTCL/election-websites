<?php
/**
 * Contact form submitted with errors.
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

$file = get_template_directory() . '/assets/images/error.svg';
?>
<h1><?php echo wp_kses_post( \CTCL\Elections\Helpers::inline_svg_tag( $file, 28, 28 ) ); ?>
	Sorry, something went wrong</h1>

<p>There was an error sending your message. Please try sending it again.</p>

<?php // phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>
<?php if ( $error ) : ?>
<code><?php echo esc_html( $error ); ?></code>
<?php endif; ?>
<?php // phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>

<p><a href="javascript:window.history.back();">Go back and try again</a></p>
