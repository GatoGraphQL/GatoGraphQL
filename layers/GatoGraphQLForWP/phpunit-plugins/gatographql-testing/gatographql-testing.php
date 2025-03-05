<?php
/*
Plugin Name: Gato GraphQL - PHPUnit & Testing Utilities
Description: Utilities for testing Gato GraphQL
Version: 11.1.1
Requires at least: 6.1
Requires PHP: 8.1
Author: Gato GraphQL
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: gatographql-testing
Domain Path: /languages
*/

use GatoGraphQL\GatoGraphQL\Plugin as GatoGraphQLMainPlugin;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Plugin;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\PluginHelpers;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action(
    'plugins_loaded',
    function (): void {
        // Validate Gato GraphQL is installed, or exit
        if (!class_exists(GatoGraphQLMainPlugin::class)) {
            return;
        }
        
        // Load Composer’s autoloader
        require_once(__DIR__ . '/vendor/autoload.php');

        /**
         * Activate the plugin, only if:
         *
         * - we are in the DEV environment, or
         * - we are executing integration tests (hosted in InstaWP)
         */
        if (!PluginHelpers::enablePlugin()) {
            \add_action('admin_notices', function () {
                $adminNotice_safe = sprintf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    sprintf(
                        __('Functionality in plugin <strong>%s</strong> can only be enabled during development or testing.', 'gatographql-testing'),
                        __('Gato GraphQL - Testing', 'gatographql-testing')
                    )
                );
                echo $adminNotice_safe;
            });
            return;
        }

        // Initialize the plugin
        (new Plugin())->initialize();
    }
);
