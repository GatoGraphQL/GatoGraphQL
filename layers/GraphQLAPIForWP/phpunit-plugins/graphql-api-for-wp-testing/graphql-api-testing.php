<?php
/*
Plugin Name: GraphQL API for WordPress - PHPUnit & Testing Utilities
Description: Utilities for testing the GraphQL API for WordPress
Version: 0.9.0
Requires at least: 5.4
Requires PHP: 8.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-testing
Domain Path: /languages
*/

use GraphQLAPI\GraphQLAPI\Plugin as GraphQLAPIMainPlugin;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Plugin;
use PoP\Root\Environment as RootEnvironment;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action(
    'plugins_loaded',
    function(): void {
        // Validate the GraphQL API plugin is installed, or exit
        if (!class_exists(GraphQLAPIMainPlugin::class)) {
            return;
        }

        /**
         * Activate the plugin, only if:
         *
         * - we are in the DEV environment, or
         * - we are executing integration tests (hosted in InstaWP)
         */
        $enablePlugin = RootEnvironment::isApplicationEnvironmentDev();
        if (!$enablePlugin) {
            $validTestingDomains = [
                'instawp.xyz',
                'lndo.site',
            ];
            // Calculate the top level domain (app.site.com => site.com)
            $hostNames = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
            $host = $hostNames[1] . '.' . $hostNames[0];
            $enablePlugin = in_array($host, $validTestingDomains);
        }
        if (!$enablePlugin) {
            \add_action('admin_notices', function () {
                _e(sprintf(
                    '<div class="notice notice-error">' .
                        '<p>%s</p>' .
                    '</div>',
                    sprintf(
                        __('Functionality in plugin <strong>%s</strong> can only be enabled during development or testing.', 'graphql-api-testing'),
                        __('GraphQL API - Testing', 'graphql-api-testing')
                    )
                ));
            });
            return;
        }
        
        // Load Composer’s autoloader
        require_once(__DIR__ . '/vendor/autoload.php');

        // Initialize the plugin
        (new Plugin())->initialize();
    }
);
