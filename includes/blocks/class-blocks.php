<?php
/**
 * Register custom blocks and block categories.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Blocks class.
 *
 * Implements block registration and categorization.
 */
class Blocks {

	/**
	 * Set up actions and filters.
	 */
	public static function hooks() {
		add_action( 'init', [ __CLASS__, 'init_block_editors' ] );
		add_filter( 'block_categories', [ __CLASS__, 'block_categories' ], 10, 2 );
	}

	/**
	 * Initialize custom blocks
	 */
	public static function init_block_editors() {
		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		wp_register_script(
			'block-editors',
			get_template_directory_uri() . "/assets/js/blocks.${type}.js",
			[ 'wp-block-editor', 'wp-element', 'wp-components', 'wp-data' ],
			THEME_VERSION,
			true
		);

		wp_register_style(
			'block-editors',
			get_template_directory_uri() . "/assets/css/block-editors.${type}.css",
			[],
			THEME_VERSION
		);

		$base_url = get_template_directory_uri() . '/assets/images/icons/';
		$theme    = \CTCL\Elections\Elections_Settings::get_theme_slug();

		$voter_icons = [
			'accessible-voting'         => 'Accessible Voting',
			'add-to-the-ballot'         => 'Add to the Ballot',
			'ballot-box'                => 'Ballot Box',
			'become-a-poll-worker'      => 'Become a Poll Worker',
			'becoming-a-candidate'      => 'Becoming a Candidate',
			'election-calendar'         => 'Calendar',
			'campaign-resources'        => 'Campaign Resources',
			'check-registration-status' => 'Check Registration Status',
			'counting-ballots'          => 'Counting Ballots',
			'person-mask'               => 'COVID-19',
			'elected-officials'         => 'Elected Officials',
			'military'                  => 'Military',
			'news'                      => 'News',
			'open-offices'              => 'Open Offices',
			'petitions-and-recalls'     => 'Petitions and Recalls',
			'polling-locations'         => 'Polling Locations',
			'precinct-map'              => 'Precinct Map',
			'register-by-mail'          => 'Register by Mail',
			'register-online'           => 'Register Online',
			'register-to-vote'          => 'Register to Vote',
			'sample-ballot'             => 'Sample Ballot',
			'view-election-results'     => 'View Election Results',
			'vote-by-mail'              => 'Vote by Mail',
			'voter-info'                => 'Voter Info',
			'voters-choice'             => 'Voter’s Choice',
			'voting-locations'          => 'Voting Locations',
			'whats-on-the-ballot'       => 'What’s on the Ballot',
		];

		$election_icons = [
			'accessible-voting'         => 'Accessible Voting',
			'add-to-the-ballot'         => 'Add to the List',
			'petitions-and-recalls'     => 'Adding Numbers',
			'campaign-resources'        => 'Announcement',
			'ballot-box'                => 'Ballot Box',
			'election-calendar'         => 'Calendar',
			'become-a-poll-worker'      => 'Certification',
			'whats-on-the-ballot'       => 'Checklist',
			'person-mask'               => 'COVID-19',
			'view-election-results'     => 'Data',
			'sample-ballot'             => 'Document for Review',
			'register-by-mail'          => 'Envelope',
			'voting-locations'          => 'Government Building',
			'voter-info'                => 'Information',
			'open-offices'              => 'List of People',
			'polling-locations'         => 'Locations',
			'vote-by-mail'              => 'Mail',
			'precinct-map'              => 'Map',
			'news'                      => 'News',
			'counting-ballots'          => 'Papers',
			'elected-officials'         => 'People',
			'becoming-a-candidate'      => 'People Documents',
			'voters-choice'             => 'Recognition',
			'check-registration-status' => 'Search',
			'military'                  => 'Shield',
			'register-to-vote'          => 'Sign Up',
			'register-online'           => 'Website',
		];

		$icon_list = get_option( 'audience' ) === 'officials' ? $election_icons : $voter_icons;

		wp_localize_script(
			'block-editors',
			'blockEditorVars',
			[
				'iconOptions' => $icon_list,
				'baseUrl'     => $base_url . $theme,
			]
		);

		register_block_type(
			'ctcl-election-website/numbered-section-block',
			[
				'editor_script' => 'block-editors',
				'editor_style'  => 'block-editors',
			]
		);

		register_block_type(
			'ctcl-election-website/tile-nav-section-block',
			[
				'editor_script' => 'block-editors',
				'editor_style'  => 'block-editors',
			]
		);

		register_block_type(
			'ctcl-election-website/tile-nav-block',
			[
				'editor_script'   => 'block-editors',
				'editor_style'    => 'block-editors',
				'render_callback' => [ '\CTCL\Elections\Tile', 'block_render' ],
			]
		);


		register_block_type(
			'ctcl-election-website/accordion-group-block',
			[
				'editor_script' => 'block-editors',
				'editor_style'  => 'block-editors',
			]
		);

		register_block_type(
			'ctcl-election-website/accordion-section-block',
			[
				'editor_script' => 'block-editors',
				'editor_style'  => 'block-editors',
			]
		);

		register_block_type(
			'ctcl-election-website/accordion-nested-group-block',
			[
				'editor_script' => 'block-editors',
				'editor_style'  => 'block-editors',
			]
		);

		register_block_type(
			'ctcl-election-website/read-more-block',
			[
				'editor_script' => 'block-editors',
				'editor_style'  => 'block-editors',
			]
		);

		register_block_type(
			'ctcl-election-website/contact-form',
			[
				'render_callback' => [ '\CTCL\Elections\Contact_Form', 'block_render' ],
			]
		);

		register_block_type(
			'ctcl-election-website/office-info',
			[
				'render_callback' => [ '\CTCL\Elections\Office_Info', 'block_render' ],
			]
		);
	}

	/**
	 * Add Election Blocks to the list of block categories.
	 *
	 * @param array   $categories  Array of block categories.
	 * @param WP_Post $post         Post being loaded.
	 *
	 * @return array
	 */
	public static function block_categories( $categories, $post ) {
		array_unshift(
			$categories,
			[
				'title' => 'Election Blocks',
				'slug'  => 'election-blocks',
				'icon'  => 'dashicons-carrot',
			]
		);

		return $categories;
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Blocks', 'hooks' ] );
