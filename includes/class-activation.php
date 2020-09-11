<?php
/**
 * Functions called on theme installation/activation.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Functions called on theme installation/activation.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */
class Activation {

	const DEFAULT_TOPIC_LIST = [
		'Voter registration',
		'Voting by mail',
		'Voting in person',
		'Election results',
		'Data request',
		'Election staff opportunities',
		'COVID-19',
	];

	/**
	 * Set up actions.
	 */
	public static function actions() {
		self::standardize_directory_name();
		self::enable_auto_updates();
		self::enable_optimization();
		self::upload_included_images();
		self::configure_menu();
		self::configure_home_page();
		self::configure_permalink_structure();
		self::configure_default_topics();
	}

	/**
	 * If theme directory name ends with a version number, remove it.
	 * GitHub releases end with version
	 */
	public static function standardize_directory_name() {
		$current_theme = wp_get_theme();

		$name = $current_theme->get_stylesheet();
		$path = $current_theme->get_stylesheet_directory();

		if ( 0 === strpos( $name, Updater::THEME_SLUG ) && Updater::THEME_SLUG !== $name ) {
			rename( $path, get_theme_root() . '/' . Updater::THEME_SLUG );  // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_rename
			switch_theme( Updater::THEME_SLUG );
		}
	}

	/**
	 * Enable CSS, JS and JS defer for the Page Optimize plugin.
	 */
	public static function enable_optimization() {
		update_option( 'page_optimize-js', 1 );
		update_option( 'page_optimize-load-mode', 'defer' );
		update_option( 'page_optimize-css', 1 );
	}

	/**
	 * Enable automatic updates for this theme.
	 */
	public static function enable_auto_updates() {
		$theme_list = get_option( 'auto_update_themes' );

		if ( ! is_array( $theme_list ) ) {
			$theme_list = [];
		}

		if ( ! in_array( Updater::THEME_SLUG, $theme_list, true ) ) {
			$theme_list[] = Updater::THEME_SLUG;
			update_option( 'auto_update_themes', $theme_list );
		}
	}

	/**
	 * Check if an image already exists in the media library.
	 *
	 * @param string $file  File name.
	 *
	 * @return boolean
	 */
	public static function image_exists( $file ) {
		if ( ! $file ) {
			return false;
		}

		$query = new \WP_Query(
			[
				'meta_query'  => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					[
						'key'     => '_wp_attached_file',
						'compare' => 'LIKE',
						'value'   => $file,
					],
				],
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
			]
		);

		return count( $query->posts ) > 0;
	}

	/**
	 * Upload banner and placeholder images to the media library.
	 */
	public static function upload_included_images() {
		$image_list = [
			'banner'      => [
				'balllot-box-light-blue.svg' => 'Ballot box (light blue)',
				'ballot-box-green.svg'       => 'Ballot box (green)',
				'mailbox-light-blue.svg'     => 'Mailbox (light blue)',
				'mailbox-green.svg'          => 'Mailbox (green)',
				'mailbox-dark-blue.svg'      => 'Mailbox (dark blue)',
			],
			'placeholder' => [
				'county-logo.png'     => 'Sample county logo',
				'registrar-photo.png' => 'Sample registrar photo',
			],
		];

		foreach ( $image_list as $category => $image_data ) {
			foreach ( $image_data as $filename => $description ) {

				if ( self::image_exists( $filename ) ) {
					continue;
				}

				$file = get_theme_file_uri( 'assets/images/' . $category . '/' . $filename );
				Helpers::upload_image( $file, $description );
			}
		}
	}

	/**
	 * Configure the home page.
	 */
	public static function configure_home_page() {
		update_option( 'show_on_front', 'page' );

		$home_page = get_page_by_path( 'home' ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_page_by_path_get_page_by_path
		if ( ! $home_page ) {
			return;
		}
		update_option( 'page_on_front', $home_page->ID );
	}

	/**
	 * Configure permalink structure.
	 */
	public static function configure_permalink_structure() {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	/**
	 * Configure the top nav menu.
	 */
	public static function configure_menu() {
		$nav_menu = wp_get_nav_menu_object( 'top-nav-menu' );
		if ( ! $nav_menu ) {
			return false;
		}

		$locations = [ 'header-menu' => $nav_menu->term_id ];
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Configure the default topic list.
	 */
	public static function configure_default_topics() {
		$topic_list = get_option( 'ctcl_topic_list' );

		// Topics already exist. Do nothing.
		if ( is_array( $topic_list ) && $topic_list ) {
			return;
		}

		update_option( 'ctcl_topic_list', self::DEFAULT_TOPIC_LIST );
	}

	/**
	 * Create pages with prewritten content.
	 */
	public static function add_election_content() {
	}
}

add_action( 'after_switch_theme', [ '\CTCL\Elections\Activation', 'actions' ] );

