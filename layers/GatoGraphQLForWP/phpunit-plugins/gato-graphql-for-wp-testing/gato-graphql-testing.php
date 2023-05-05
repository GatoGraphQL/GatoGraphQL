<?php
/*
Plugin Name: Gato GraphQL - PHPUnit & Testing Utilities
Description: Utilities for testing Gato GraphQL
Version: 1.0.0-dev
Requires at least: 5.4
Requires PHP: 8.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: gato-graphql-testing
Domain Path: /languages
*/

use GatoGraphQL\GatoGraphQL\Plugin as GatoGraphQLMainPlugin;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Environment;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Plugin;
use PoP\Root\Environment as RootEnvironment;

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
        $enablePlugin = RootEnvironment::isApplicationEnvironmentDev();
        if (!$enablePlugin && isset($_SERVER['HTTP_HOST'])) {
            $validTestingDomains = array_merge(
                Environment::getContinuousIntegrationValidTestingDomains(),
                Environment::getLocalDevelopmentValidTestingDomains()
            );
            // Calculate the top level domain (app.site.com => site.com)
            $hostNames = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
            $domain = $hostNames[1] . '.' . $hostNames[0];
            $enablePlugin = in_array($domain, $validTestingDomains);
        }
        if (!$enablePlugin) {
            \add_action('admin_notices', function () {
                _e(sprintf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    sprintf(
                        __('Functionality in plugin <strong>%s</strong> can only be enabled during development or testing.', 'gato-graphql-testing'),
                        __('Gato GraphQL - Testing', 'gato-graphql-testing')
                    )
                ));
            });
            return;
        }

        // Initialize the plugin
        (new Plugin())->initialize();
    }
);
