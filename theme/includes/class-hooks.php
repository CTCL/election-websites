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
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public static function wp_enqueue_scripts() {
		$type = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? 'src' : 'min';

		wp_enqueue_script( 'main', get_template_directory_uri() . "/assets/js/main.{$type}.js", [ 'jquery', 'underscore', 'wp-util' ], THEME_VERSION, true );
		wp_enqueue_style( 'main', get_template_directory_uri() . "/assets/css/main.{$type}.css", [], THEME_VERSION );
		wp_enqueue_style( 'source-sans', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;700&display=swap', [], THEME_VERSION );
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
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Hooks', 'setup_hooks' ] );
add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Hooks', 'configure_theme' ] );
