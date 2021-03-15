<?php
/**
 * CTCL CLI Commands.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Configure settings, and load images and prewritten content.
 */
class CTCL_CLI extends \WP_CLI_Command {

	/**
	 * Set page optimization configuration.
	 *
	 * ## EXAMPLES
	 * wp ctcl configure-settings
	 *
	 * @subcommand configure-settings
	 *
	 * @param array $args        Array of command-line arguments.
	 * @param array $assoc_args  Associative array of arguments.
	 */
	public function configure_settings( $args, $assoc_args ) {
		Activation::enable_optimization();
	}

	/**
	 * Add banner and placeholder images to the media library.
	 *
	 * ## EXAMPLES
	 * wp ctcl add-images
	 *
	 * @subcommand add-images
	 *
	 * @param array $args        Array of command-line arguments.
	 * @param array $assoc_args  Associative array of arguments.
	 */
	public function add_images( $args, $assoc_args ) {
		Activation::upload_included_images();
	}

	/**
	 * Add prewritten content.
	 *
	 * ## EXAMPLES
	 * wp ctcl add-content
	 *
	 * @subcommand add-content
	 *
	 * @param array $args        Array of command-line arguments.
	 * @param array $assoc_args  Associative array of arguments.
	 */
	public function add_content( $args, $assoc_args ) {
		Activation::add_election_content();
	}
}

\WP_CLI::add_command( 'ctcl', '\CTCL\Elections\CTCL_CLI' );
