<?php
/**
 * CTCL election template functions and definitions
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

// Global constants.
define( 'THEME_VERSION', '0.9.4' );

if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
	$host = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING );
	if ( substr( $host, -strlen( '.test' ) ) === '.test' ) {
		define( 'WP_ENVIRONMENT_TYPE', 'local' );
	} elseif ( substr( $host, -strlen( '.dev' ) ) === '.dev' ) {
		define( 'WP_ENVIRONMENT_TYPE', 'staging' );
	}
}

if ( ! isset( $content_width ) ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	$content_width = 944;
}

// Helpers.
require_once __DIR__ . '/includes/class-activation.php';
require_once __DIR__ . '/includes/class-helpers.php';
require_once __DIR__ . '/includes/class-hooks.php';
require_once __DIR__ . '/includes/class-office-details.php';
require_once __DIR__ . '/includes/class-topics.php';

// Theme updater.
require_once __DIR__ . '/includes/class-updater.php';

// Banners.
require_once __DIR__ . '/includes/class-banner.php';
require_once __DIR__ . '/includes/class-alert-banner.php';

// Blocks.
require_once __DIR__ . '/includes/blocks/class-blocks.php';
require_once __DIR__ . '/includes/blocks/class-contact-form.php';
require_once __DIR__ . '/includes/blocks/class-office-info.php';
require_once __DIR__ . '/includes/blocks/class-tile.php';

// Settings.
require_once __DIR__ . '/includes/settings/class-settings.php';
require_once __DIR__ . '/includes/settings/class-elections-settings.php';
require_once __DIR__ . '/includes/settings/class-office-details-settings.php';
require_once __DIR__ . '/includes/settings/class-banner-settings.php';
require_once __DIR__ . '/includes/settings/class-topics-settings.php';
require_once __DIR__ . '/includes/settings/class-google-settings.php';

// Google integration.
require_once __DIR__ . '/includes/class-google-analytics.php';
require_once __DIR__ . '/includes/class-google-recaptcha.php'; // Must be after settings page.

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/includes/class-ctcl-cli.php';
}
