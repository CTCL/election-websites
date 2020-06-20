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
		$page_id   = get_the_ID();
		$curr_page = get_page( $page_id );
		$content   = apply_filters( 'the_content', $curr_page->post_content );
		echo wp_kses_post( $content )
	?>
</main>

<?php
get_footer();
