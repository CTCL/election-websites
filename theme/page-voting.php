<?php
/**
 * @package CTCL\ElectionWebsite
 * @since 1.0.0
 */
get_header();
?>

<main class="text-block-page">
	<h1><?php echo esc_html( get_the_title() ); ?></h1>
	<p>Be prepared for the next election. Find out what contests will appear on the ballot, options for casting your vote, how to mark your ballot, and more.</p>
	<section>
		<h3>Ways You Can Vote</h3>
		<div class="tile-wrapper full-width">
			<div class="tile">Vote by Mail</div>
			<div class="tile">
				<div class="icon">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/polling_locations.svg' ); ?>" alr="Polling Locations" width="52" height="55" />
				</div>
				Find your Polling Place
			</div>
			<div class="tile">List of Voting Locations</div>
			<div class="tile">Accessible Voting</div>
		</div>
	</section>
	<section>
		<h3>Voting Resources</h3>
	</section>

</main>

<?php
get_footer();
