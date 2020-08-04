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
		wp_localize_script(
			'block-editors',
			'blockEditorVars',
			[
				'iconOptions' =>
					[
						(object) [
							'value' => 'accessible-voting',
							'label' => 'Accessible Voting',
						],
						(object) [
							'value' => 'add-to-the-ballot',
							'label' => 'Add to the Ballot',
						],
						(object) [
							'value' => 'become-a-poll-worker',
							'label' => 'Become a Poll Worker',
						],
						(object) [
							'value' => 'becoming-a-candidate',
							'label' => 'Becoming a Candidate',
						],
						(object) [
							'value' => 'campaign-resources',
							'label' => 'Campaign Resources',
						],
						(object) [
							'value' => 'check-registration-status',
							'label' => 'Check Registration Status',
						],
						(object) [
							'value' => 'elected-officials',
							'label' => 'Elected Officials',
						],
						(object) [
							'value' => 'election-calendar',
							'label' => 'Calendar',
						],
						(object) [
							'value' => 'military',
							'label' => 'Military',
						],
						(object) [
							'value' => 'news',
							'label' => 'News',
						],
						(object) [
							'value' => 'open-offices',
							'label' => 'Open Offices',
						],
						(object) [
							'value' => 'petitions-and-recalls',
							'label' => 'Petitions and Recalls',
						],
						(object) [
							'value' => 'polling-locations',
							'label' => 'Polling Locations',
						],
						(object) [
							'value' => 'register-by-mail',
							'label' => 'Register by Mail',
						],
						(object) [
							'value' => 'register-online',
							'label' => 'Register Online',
						],
						(object) [
							'value' => 'register-to-vote',
							'label' => 'Register to Vote',
						],
						(object) [
							'value' => 'sample-ballot',
							'label' => 'Sample Ballot',
						],
						(object) [
							'value' => 'view-election-results',
							'label' => 'View Election Results',
						],
						(object) [
							'value' => 'vote-by-mail',
							'label' => 'Vote by Mail',
						],
						(object) [
							'value' => 'voter-info',
							'label' => 'Voter Info',
						],
						(object) [
							'value' => 'voters-choice',
							'label' => 'Voter\'s Choice',
						],
						(object) [
							'value' => 'voting-locations',
							'label' => 'Voting Locations',
						],
						(object) [
							'value' => 'whats-on-the-ballot',
							'label' => 'What\'s on the Ballot',
						],
					],
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
			'ctcl-election-website/accordion-section-block',
			[
				'editor_script' => 'block-editors',
				'editor_style'  => 'block-editors',
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
