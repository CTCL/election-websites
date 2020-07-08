<?php
namespace CTCL\Elections;

/**
 * WordPress filters/actions and their callbacks
 * @package CTCL\Elections
 * @since 1.0.0
 */

class Hooks {
	/**
	 * Set up WordPress hooks.
	 */
	public static function setup_hooks() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );

		// disable default post type
		add_action( 'admin_bar_menu', [ __CLASS__, 'remove_admin_bar_new_post' ], 99 );
		add_action( 'admin_menu', [ __CLASS__, 'update_menu_pages' ], 998 );
		add_action( 'load-post-new.php', [ __CLASS__, 'prevent_default_post_new' ] );
		add_action( 'load-edit.php', [ __CLASS__, 'prevent_default_post_new' ] );

		// disable comments
		add_filter( 'comments_open', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
		add_filter( 'comments_array', '__return_empty_array' );

		// KSES: Allow additional tags/attributes
		add_action( 'init', [ __CLASS__, 'kses_allow_additional_tags' ] );
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
	 * Enqueue scripts and styles.
	 */
	public static function admin_enqueue_scripts( $hook ) {
		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		if ( in_array( $hook, [ 'elections_page_banner' ], true ) ) {
			wp_enqueue_media();
		}

		wp_enqueue_style( 'admin', get_template_directory_uri() . "/assets/css/admin.{$type}.css", [], THEME_VERSION );
		wp_enqueue_script( 'admin', get_template_directory_uri() . "/assets/js/admin.{$type}.js", [ 'jquery', 'underscore', 'wp-util' ], THEME_VERSION, false );
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
		add_image_size( 'banner', 228, 228 );
	}

	/**
	 * Allow additional tags and attributes.
	 */
	public static function kses_allow_additional_tags() {
		global $allowedposttags;

		$style_attributes = [
			'class' => true,
			'id'    => true,
			'style' => true,
		];

		$allowed_tags_data = [
			'form'   => array_merge(
				$style_attributes,
				[
					'action' => true,
					'method' => true,
				]
			),

			'select' => array_merge(
				$style_attributes,
				[
					'name' => true,
				]
			),

			'option' => array_merge(
				$style_attributes,
				[
					'value'    => true,
					'selected' => true,
				]
			),

			'input'  => array_merge(
				$style_attributes,
				[
					'name'        => true,
					'value'       => true,
					'placeholder' => true,
					'type'        => true,
				]
			),
		];


		foreach ( $allowed_tags_data as $tag => $new_attributes ) {
			if ( ! isset( $allowedposttags[ $tag ] ) || ! is_array( $allowedposttags[ $tag ] ) ) {
				$allowedposttags[ $tag ] = []; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}

			$allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $new_attributes ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Hooks', 'setup_hooks' ] );
add_action( 'after_setup_theme', [ '\CTCL\Elections\Hooks', 'configure_theme' ] );
add_action( 'after_setup_theme', [ '\CTCL\Elections\Hooks', 'set_image_sizes' ] );
