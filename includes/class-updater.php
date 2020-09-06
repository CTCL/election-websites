<?php
/**
 * Check for theme updates against GitHub releases.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Manage the WordPress theme updates.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */
class Updater {

	const UPDATE_URL     = 'https://api.github.com/repos/usdigitalresponse/election-websites/releases/latest';
	const TRANSIENT_NAME = 'election-websites-update-theme';
	const CACHE_KEY      = 'ctcl_election_website_update_data';

	/**
	 * Set up actions and filters.
	 */
	public static function hooks() {
		add_filter( 'pre_set_site_transient_update_themes', [ __CLASS__, 'check' ], 10, 2 );
		add_filter( 'site_transient_update_themes', [ __CLASS__, 'site_transient_update_themes' ] );
		add_filter( 'upgrader_source_selection', [ __CLASS__, 'upgrader_source_selection' ], 10, 4 );
	}

	/**
	 * Check for updates.
	 *
	 * @param mixed  $value     New value of site transient.
	 * @param string $transient Transient name.
	 *
	 * @return array
	 */
	public static function check( $value, $transient ) {
		if ( ! isset( $value->checked ) ) {
			return $value;
		}

		$response = wp_cache_get( self::CACHE_KEY );
		if ( false === $response ) {
			$response = wp_remote_get( self::UPDATE_URL ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
			wp_cache_set( self::CACHE_KEY, $response, false, 300 );
		}

		if ( is_wp_error( $response ) ) {
			return $value;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			return $value;
		}

		$result_json = json_decode( $response['body'], true );
		if ( JSON_ERROR_NONE !== json_last_error() ) {
			return $value;
		}

		$new_version     = $result_json['tag_name'];
		$current_version = wp_get_theme()->Version;

		if ( $new_version <= $current_version ) {
			delete_site_transient( self::TRANSIENT_NAME );
			return $value;
		}

		$theme_data = [
			'new_version' => $new_version,
			'url'         => $result_json['html_url'],
			'package'     => $result_json['zipball_url'],
		];

		$theme_response  = [ get_option( 'template' ) => $theme_data ];
		$value->response = array_merge( ! empty( $value->response ) ? $value->response : [], $theme_response );

		$last_update               = new \StdClass();
		$last_update->checked      = $transient->checked;
		$last_update->response     = $theme_response;
		$last_update->last_checked = time();

		set_site_transient( self::TRANSIENT_NAME, $last_update );

		return $value;
	}

	/**
	 * Add theme to list of available updates.
	 *
	 * @param  array $update_transient Array of updates.
	 *
	 * @return array
	 */
	public static function site_transient_update_themes( $update_transient ) {
		$update_themes = get_site_transient( self::TRANSIENT_NAME );

		if ( ! is_object( $update_themes ) || ! isset( $update_themes->response ) ) {
			return $update_transient;
		}

		/* Fix for warning messages on Dashboard / Updates page */
		if ( ! is_object( $update_transient ) ) {
			$update_transient = new stdClass();
		}

		$update_transient->response = array_merge( ! empty( $update_transient->response ) ? $update_transient->response : [], $update_themes->response );

		return $update_transient;
	}

	/**
	 * Adjust the source file location for the upgrade package.
	 * Remove the tag information appended by GitHub.
	 *
	 * @param  string      $source        File source location.
	 * @param  string      $remote_souce  Remote file source location.
	 * @param  WP_Upgrader $upgrader      WP_Upgrader instance.
	 * @param  array       $hook_extra    Extra arguments passed to hooked filters.
	 *
	 * @return string
	 */
	public function upgrader_source_selection( $source, $remote_souce, $upgrader, $hook_extra ) {
		$theme_folder = get_option( 'template' );

		if ( false === strpos( $source, $theme_folder ) ) {
			return $source;
		}

		$directory_name = pathinfo( $source, PATHINFO_DIRNAME );
		$new_source     = trailingslashit( $directory_name . '/' . $theme_folder );
		rename( $source, $new_source ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_rename
		return $new_source;
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Updater', 'hooks' ] );
