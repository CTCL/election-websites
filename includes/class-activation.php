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

	/**
	 * Set up actions.
	 */
	public static function actions() {
		self::standardize_directory_name();
		self::enable_optimization();
		self::upload_included_images();
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
	 * Create pages with prewritten content.
	 */
	public static function add_election_content() {

	}
}

add_action( 'after_switch_theme', [ '\CTCL\Elections\Activation', 'actions' ] );

