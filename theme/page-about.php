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
	if ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</main>

<?php
get_footer();
