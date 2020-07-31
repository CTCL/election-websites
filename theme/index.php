<?php
/**
 * Index Template.
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

get_header();
?>

<main>
	<?php if ( ! is_front_page() ) : ?>
	<h1><?php echo esc_html( get_the_title() ); ?></h1>
	<?php endif; ?>

	<?php
	if ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</main>

<?php
get_footer();
