<?php
namespace CTCL\ElectionWebsite;

/**
 * WordPress filters/actions and their callbacks
 * @package CTCL\ElectionWebsite
 * @since 1.0.0
 */

class Hooks {
	/**
	 * Set up WordPress hooks.
	 */
	public static function setup_hooks() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );

		// disable default post type
		add_action( 'admin_bar_menu', [ __CLASS__, 'remove_admin_bar_new_post' ], 99 );
		add_action( 'admin_menu', [ __CLASS__, 'update_menu_pages' ], 998 );
		add_action( 'load-post-new.php', [ __CLASS__, 'prevent_default_post_new' ] );
		add_action( 'load-edit.php', [ __CLASS__, 'prevent_default_post_new' ] );

		// disable comments
		add_filter( 'comments_open', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
		add_filter( 'comments_array', '__return_empty_array' );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public static function wp_enqueue_scripts() {
		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		wp_enqueue_script( 'main', get_template_directory_uri() . "/assets/js/main.{$type}.js", [ 'jquery', 'underscore', 'wp-util' ], THEME_VERSION, true );
		wp_enqueue_style( 'main', get_template_directory_uri() . "/assets/css/main.{$type}.css", [], THEME_VERSION );
		wp_enqueue_style( 'source-sans', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap', [], THEME_VERSION );
	}

	/**
	 * Set up theme defaults and register supported WordPress features.
	 */
	public static function configure_theme() {

		$menus = [
			'header-menu' => 'Top Nav Menu',
		];

		register_nav_menus( $menus );

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'custom-logo' );
	}

	/**
	 * Remove nodes from admin bar menu.
	 * @param $wp_admin_bar
	 */
	public static function remove_admin_bar_new_post( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'new-post' );
	}

	/**
	 * Prevent the creation of new posts without a post type.
	 * Redirect to admin dashboard if no post type in post-new editor.
	 */
	public static function prevent_default_post_new() {
		global $typenow;
		if ( 'post' === $typenow ) {
			wp_safe_redirect( admin_url( '/' ) );
			die();
		}
	}

	/**
	 * Remove the posts menu.
	 * Remove the comments menu page.
	 */
	public static function update_menu_pages() {
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
	}

	public static function set_image_sizes() {
		add_image_size( 'header-icon', 56, 56 );
	}

	/**
	 * Initialize custom blocks
	 */
	public static function init_block_editors() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		wp_register_script(
			'block-editor',
			get_template_directory_uri() . "/assets/js/blocks.{$type}.js",
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
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Hooks', 'setup_hooks' ] );
add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Hooks', 'configure_theme' ] );
add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Hooks', 'set_image_sizes' ] );
add_action( 'init', [ '\CTCL\ElectionWebsite\Hooks', 'init_block_editors' ] );
