<?php
/**
 * Front Page Template.
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

get_header();

$tiles = [
	'register-to-vote'     => 'Register to Vote',
	'vote-by-mail-2'       => 'Vote by Mail',
	'election-results'     => 'View Election Results',
	'whats-on-the-ballot'  => 'What’s on the ballot',
	'polling-locations'    => 'Where to Vote',
	'become-a-poll-worker' => 'Become a Poll Worker',
	'campaign-resources'   => 'Campaign Resources',
	'news'                 => 'News & Press Releases',
];


?>

<main class="front-page">
	<nav class="tile-wrapper">
		<?php
		foreach ( $tiles as $slug => $page_title ) {
			$url = home_url( $slug );
			echo '<a href="' . esc_url( $url ) . '" class="tile">
					  <div id="' . esc_attr( $slug ) . '" class="bounding-box"></div>
					  <span>' . esc_html( $page_title ) . '</span></a>';
		}
		?>
	</div>
</main>

<?php
get_footer();
