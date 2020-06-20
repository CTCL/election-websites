<?php
/**
 * Front Page Template.
 *
 * @package CTCL\ElectionWebsite
 * @since   1.0.0
 */

get_header();
?>

<main class="front-page">
	<div class="tile-wrapper full-width">
		<div class="tile">Register to Vote</div>
		<div class="tile">Vote by Mail</div>
		<a href="<?php echo esc_url( get_permalink( 'Results' ) ); ?>">
			<div class="tile">View Election Results</div>
		</a>
		<div class="tile">Register to Vote</div>
		<div class="tile">Register to Vote</div>
		<div class="tile">Register to Vote</div>
		<div class="tile">List of Voting Locations</div>
		<div class="tile">Accessible Voting</div>
	</div>
</main>

<?php
get_footer();
