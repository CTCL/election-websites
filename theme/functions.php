<?php
/**
 * CTCL election template functions and definitions
 *
 * @package CTCL\Elections
 * @since   1.0.0
 */

// Global constants.
define( 'THEME_VERSION', '0.5' );

// Helpers.
require_once __DIR__ . '/includes/class-helpers.php';
require_once __DIR__ . '/includes/class-hooks.php';
require_once __DIR__ . '/includes/class-office-details.php';

// Banners.
require_once __DIR__ . '/includes/class-banner.php';
require_once __DIR__ . '/includes/class-alert-banner.php';

// Blocks.
require_once __DIR__ . '/includes/blocks/class-blocks.php';
require_once __DIR__ . '/includes/blocks/class-contact-form.php';
require_once __DIR__ . '/includes/blocks/class-office-info.php';

// Settings.
require_once __DIR__ . '/includes/settings/class-settings.php';
require_once __DIR__ . '/includes/settings/class-office-details-settings.php';
require_once __DIR__ . '/includes/settings/class-banner-settings.php';
require_once __DIR__ . '/includes/settings/class-topics-settings.php';
require_once __DIR__ . '/includes/settings/class-google-settings.php';

// Google integration.
require_once __DIR__ . '/includes/class-google-analytics.php';
require_once __DIR__ . '/includes/class-google-recaptcha.php'; // Must be after settings page.
