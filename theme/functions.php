<?php
/**
 * CTCL election template functions and definitions
 *
 * @package CTCL\ElectionWebsite
 * @since   1.0.0
 */

// Useful global constants
define( 'THEME_VERSION', '0.5' );

require_once __DIR__ . '/includes/class-helpers.php';
require_once __DIR__ . '/includes/class-hooks.php';

// blocks
require_once __DIR__ . '/includes/class-blocks.php';
require_once __DIR__ . '/includes/class-contact-form.php';
require_once __DIR__ . '/includes/class-office-info.php';

// settings
require_once __DIR__ . '/includes/class-banner-settings.php';
require_once __DIR__ . '/includes/class-google-settings.php';
require_once __DIR__ . '/includes/class-inquiries-settings.php';
require_once __DIR__ . '/includes/class-office-details-settings.php';

// google integration
require_once __DIR__ . '/includes/class-google-analytics.php';
require_once __DIR__ . '/includes/class-recaptcha.php'; // must be after settings page
