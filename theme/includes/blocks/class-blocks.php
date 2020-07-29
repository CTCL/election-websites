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
						'accessible-voting',
						'add-to-the-ballot',
						'become-a-poll-worker',
						'becoming-a-candidate',
						'campaign-resources',
						'check-registration-status',
						'elected-officials',
						'election-calendar',
						'military',
						'news',
						'open-offices',
						'petitions-and-recalls',
						'polling-locations',
						'register-by-mail',
						'register-online',
						'register-to-vote',
						'sample-ballot',
						'view-election-results',
						'vote-by-mail',
						'voter-info',
						'voters-choice',
						'voting-locations',
						'whats-on-the-ballot',
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
