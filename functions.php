<?php
/**
 * Load the theme functionalities.
 */

// Require theme library autoloader.
require_once dirname( __FILE__ ) . '/lib/autoload.php';

// Require HDS Partial Helpers
require_once dirname( __FILE__ ) . '/hds-helpers.php';

// Theme setup
Geniem\Theme\ThemeController::instance();

// ModelController
Geniem\Theme\ModelController::instance();

/**
 * Global helper function to fetch the ThemeController instance
 *
 * @return Geniem\Theme\ThemeController
 */
function ThemeController() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
    return Geniem\Theme\ThemeController::instance();
}

/**
 * Global helper function to fetch the ModelController instance
 *
 * @return Geniem\Theme\ModelController
 */
function ModelController() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
    return Geniem\Theme\ModelController::instance();
}

if ( WP_ENV === 'development' ) {
    /**
     * Notice level errors trigger Whoops, which is bad because
     * usually the triggering code lives inside plugins.
     */
    error_reporting( E_ALL & ~E_NOTICE ); // phpcs:ignore
}
