<?php
/**
 * 404 Template.
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

get_header();

$theme = \CTCL\Elections\Elections_Settings::get_theme_slug();
$file  = get_template_directory() . '/assets/images/icons/' . sanitize_file_name( $theme ) . '/not-found.svg';
?>

<main>
	<?php echo wp_kses_post( \CTCL\Elections\Helpers::inline_svg_tag( $file, 200, 158 ) ); ?>

	<h1>Oops! We can’t find the page you’re looking for.</h1>

	<p>The page has moved or does not exist. Try visiting our <a href="/faq/">FAQs</a> for help or going back to the homepage.</p>

	<p><a class="button" href="/">Back to Home</a></p>
</main>

<?php
get_footer();
