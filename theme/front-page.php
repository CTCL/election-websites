<?php
/**
 * Front Page Template.
 *
 * @package CTCL\ElectionWebsite
 * @since   1.0.0
 */

get_header();

$tiles = [
	'registration'          => 'Register to Vote',
	'vote-by-mail'          => 'Vote by Mail',
	'view-election-results' => 'View Election Results',
	'whats-on-ballot'       => 'What’s on the ballot',
	'where-to-vote'         => 'Where to Vote',
	'become-poll-worker'    => 'Become a Poll Worker',
	'campaign-resources'    => 'Campaign Resources',
	'news'                  => 'News & Press Releases',
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
