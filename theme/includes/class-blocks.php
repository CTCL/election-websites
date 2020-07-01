<?php
namespace CTCL\ElectionWebsite;

class Blocks {

	public static function hooks() {
		add_action( 'init', [ __CLASS__, 'init_block_editors' ] );
		add_filter( 'block_categories', [ __CLASS__, 'block_categories' ] );
	}

	/**
	 * Initialize custom blocks
	 */
	public static function init_block_editors() {
		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		wp_register_script(
			'block-editor',
			get_template_directory_uri() . "/assets/js/blocks.${type}.js",
			[ 'wp-block-editor', 'wp-element', 'wp-hooks', 'wp-compose' ],
			THEME_VERSION,
			true
		);

		wp_register_style(
			'block-editors',
			get_template_directory_uri() . "/assets/css/block-editors.${type}.css",
			[],
			THEME_VERSION
		);

		register_block_type(
			'ctcl-election-website/numbered-section-block',
			[
				'editor_script' => 'block-editor',
				'editor_style'  => 'block-editors',
			]
		);

		register_block_type(
			'ctcl-election-website/contact-form',
			[
				'render_callback' => [ '\CTCL\ElectionWebsite\Contact_Form', 'block_render' ],
			]
		);
	}

	public static function block_categories( $categories ) {
		$categories[] = [
			'title' => 'Election Blocks',
			'slug'  => 'election-blocks',
			'icon'  => 'dashicons-carrot',
		];

		return $categories;
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Blocks', 'hooks' ] );
