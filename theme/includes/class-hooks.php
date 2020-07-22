<?php
/**
 * Helper functions called from initialization actions.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * WordPress filters/actions and their callbacks
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */
class Hooks {

	/**
	 * Set up actions and filters.
	 */
	public static function setup_hooks() {
		/*
		 * Custom actions and filters.
		 */

		// Enqueue CSS and JS.
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );

		// Defer CSS and JS.
		add_filter( 'script_loader_tag', [ __CLASS__, 'defer_js' ], 10, 3 );
		add_filter( 'style_loader_tag', [ __CLASS__, 'defer_css' ], 10, 4 );
		add_filter( 'page_optimize_style_loader_tag', [ __CLASS__, 'defer_css' ], 10, 4 );

		// Set body class.
		add_filter( 'admin_body_class', [ __CLASS__, 'filter_admin_body_class' ] );
		add_filter( 'body_class', [ __CLASS__, 'filter_body_class' ] );

		// Disable default 'post' post type.
		add_action( 'admin_bar_menu', [ __CLASS__, 'remove_admin_bar_new_post' ], 99 );
		add_action( 'admin_menu', [ __CLASS__, 'update_menu_pages' ], 998 );
		add_action( 'load-post-new.php', [ __CLASS__, 'prevent_default_post_new' ] );
		add_action( 'load-edit.php', [ __CLASS__, 'prevent_default_post_new' ] );

		// Print <meta> description.
		add_action( 'wp_head', [ __CLASS__, 'meta_tags_output' ], 15 );

		// KSES: Allow additional tags/attributes.
		add_action( 'init', [ __CLASS__, 'kses_allow_additional_tags' ] );

		/*
		 * Change default WordPress behaviours.
		 */

		// Remove unused roles.
		remove_role( 'author' );
		remove_role( 'contributor' );
		remove_role( 'subscriber' );

		// Disable comments.
		add_filter( 'comments_open', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
		add_filter( 'comments_array', '__return_empty_array' );

		// Disable emojis.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		// Remove wlmanifest and EditURI links.
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public static function wp_enqueue_scripts() {
		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		wp_deregister_script( 'jquery' );

		wp_enqueue_script( 'main', get_template_directory_uri() . "/assets/js/main.{$type}.js", [], THEME_VERSION, true );
		wp_enqueue_style( 'main', get_template_directory_uri() . "/assets/css/main.{$type}.css", [], THEME_VERSION );
		wp_enqueue_style( 'source-sans', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap', [], THEME_VERSION );
	}

	/**
	 * Enqueue backend scripts and styles.
	 *
	 * @param string $hook The current admin page.
	 */
	public static function admin_enqueue_scripts( $hook ) {
		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		if ( in_array( $hook, [ 'elections_page_banner', 'toplevel_page_elections' ], true ) ) {
			wp_enqueue_media();
		}

		wp_enqueue_style( 'admin', get_template_directory_uri() . "/assets/css/admin.{$type}.css", [], THEME_VERSION );
		wp_enqueue_script( 'admin', get_template_directory_uri() . "/assets/js/admin.{$type}.js", [ 'jquery', 'underscore', 'wp-util' ], THEME_VERSION, false );
	}

	/**
	 * Defer JS. Only used when Page Optimize is not installed or disabled.
	 *
	 * @param string $tag    The `<script>` tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @param string $src    The script's source URL.
	 *
	 * @return string Script HTML string.
	 */
	public static function defer_js( $tag, $handle, $src ) {
		if ( in_array( $handle, [ 'main' ], true ) ) {
			return str_replace( ' src', ' defer="defer" src', $tag );
		}

		return $tag;
	}

	/**
	 * Defer CSS.
	 *
	 * @param string $html   The link tag for the enqueued style.
	 * @param string $handle The style's registered handle.
	 * @param string $href   The stylesheet's source URL.
	 * @param string $media  The stylesheet's media attribute.
	 *
	 * @return string Script HTML string.
	 */
	public static function defer_css( $html, $handle, $href, $media ) {
		if ( is_array( $handle ) ) {
			$is_main_page_optimize = in_array( 'main', $handle, true );
		} else {
			$is_main_page_optimize = false;
		}
		$is_main_standard = in_array( $handle, [ 'main', 'source-sans' ], true );

		if ( $is_main_page_optimize || $is_main_standard ) {
			$html  = '<link rel="stylesheet" href="' . esc_url( $href ) . '" media="print" onload="this.onload=null;this.media=\'all\'">' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
			$html .= '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '"></noscript>' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
		}

		return $html;
	}

	/**
	 * Add theme to body class.
	 *
	 * @param array $classes Existing CSS classes.
	 *
	 * @return array
	 */
	public static function filter_body_class( $classes ) {
		$classes[] = Elections_Settings::get_theme();

		return $classes;
	}

	/**
	 * Add theme to admin body class.
	 *
	 * @param string $classes Existing CSS classes.
	 *
	 * @return string
	 */
	public static function filter_admin_body_class( $classes ) {
		$classes .= ' ' . Elections_Settings::get_theme();

		return $classes;
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
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
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
	 * Outputs the <meta> tags
	 */
	public static function meta_tags_output() {
		$description = get_bloginfo( 'description' );
		if ( ! $description ) {
			return;
		}

		if ( is_front_page() ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		}
	}

	/**
	 * Remove the posts menu.
	 * Remove the comments menu page.
	 * Remove the feedback menu page. (From WordPress.com/Jetpack).
	 */
	public static function update_menu_pages() {
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'edit.php?post_type=feedback' );
	}

	/**
	 * Add custom image sizes for this theme.
	 */
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

			'svg'    => [
				'width'   => true,
				'height'  => true,
				'viewbox' => true,
				'fill'    => true,
				'xmlns'   => true,
			],

			'path'   => [
				'd'            => true,
				'fill'         => true,
				'stroke'       => true,
				'stroke-width' => true,
			],

			'mask'   => [
				'id'   => true,
				'fill' => true,
			],

			'rect'   => [
				'x'            => true,
				'y'            => true,
				'fill'         => true,
				'width'        => true,
				'height'       => true,
				'stroke-width' => true,
				'rx'           => true,
				'mask'         => true,
			],
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
