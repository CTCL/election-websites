<?php
/**
 * Index Template.
 *
 * @package CTCL\ElectionWebsite
 * @since   1.0.0
 */

get_header();
?>

<main>
	<h1><?php echo esc_html( get_the_title() ); ?></h1>
	<?php
		$curr_page = get_post();
	if ( is_object( $curr_page ) ) {
		$content = apply_filters( 'the_content', $curr_page->post_content );
		echo wp_kses_post( $content );
	}
	?>
</main>

<?php
get_footer();
