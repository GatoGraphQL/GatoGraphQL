<?php
/*
Plugin Name: Gato GraphQL - Extension Demo
Plugin URI: https://github.com/GatoGraphQL/gato-graphql-extension-demo-dist
Description: Demonstration of extending the GraphQL schema, for Gato GraphQL
Version: 1.0.0-dev
Requires at least: 5.4
Requires PHP: 8.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: gato-graphql-extension-demo
Domain Path: /languages
*/

use GatoGraphQL\ExtensionDemo\GatoGraphQLExtension;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load translations
 * @todo Re-enable when an actual translation (*.po/*.mo) is provided
 * @see https://github.com/leoloso/PoP/issues/2051
 */
// \add_action('init', function (): void {
//     load_plugin_textdomain('gato-graphql-extension-demo', false, plugin_basename(__FILE__) . '/languages');
// });

/**
 * Create and set-up the extension
 */
add_action(
    'plugins_loaded',
    function (): void {
        /**
         * Extension's name and version.
         *
         * Use a stability suffix as supported by Composer.
         *
         * @see https://getcomposer.org/doc/articles/versions.md#stabilities
         */
        $extensionVersion = '1.0.0-dev';
        $extensionName = \__('Gato GraphQL - Extension Demo', 'gato-graphql-extension-demo');
        $mainPluginVersionConstraint = '^1.0';
        
        /**
         * Validate Gato GraphQL is active
         */
        if (!class_exists(Plugin::class)) {
            \add_action('admin_notices', function () use ($extensionName) {
                _e(sprintf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    sprintf(
                        __('Plugin <strong>%s</strong> is not installed or activated. Without it, plugin <strong>%s</strong> will not be loaded.', 'gato-graphql-extension-demo'),
                        __('Gato GraphQL', 'gato-graphql-extension-demo'),
                        $extensionName
                    )
                ));
            });
            return;
        }

        $extensionManager = PluginApp::getExtensionManager();
        if (!$extensionManager->assertIsValid(
            GatoGraphQLExtension::class,
            $extensionVersion,
            $extensionName,
            $mainPluginVersionConstraint
        )) {
            return;
        }

        /**
         * The commit hash is added to the plugin version 
         * through the CI when merging the PR.
         *
         * It is required to regenerate the container when
         * testing a generated .zip plugin without modifying
         * the plugin version.
         * (Otherwise, we'd have to @purge-cache.)
         *
         * Important: Do not modify this code!
         * It will be replaced in the CI to append "#{commit hash}"
         * when generating the plugin. 
         */
        $commitHash = null;

        // Load Composer’s autoloader
        require_once(__DIR__ . '/vendor/autoload.php');

        // Create and set-up the extension instance
        $extensionManager->register(new GatoGraphQLExtension(
            __FILE__,
            $extensionVersion,
            $extensionName,
            $commitHash
        ))->setup();
    }
);
