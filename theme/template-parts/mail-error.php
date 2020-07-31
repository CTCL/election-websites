<?php
/**
 * Contact form submitted with errors.
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

?>
<h1>Uh-oh</h1>

<p>We weren’t able to send your message because of the following error:</p>

<?php if ( $error ) : ?>
<code><?php echo esc_html( $error ); ?></code>
<?php endif; ?>
