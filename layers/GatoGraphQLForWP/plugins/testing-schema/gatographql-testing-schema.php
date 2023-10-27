<?php
/*
Plugin Name: Gato GraphQL - Testing Schema
Plugin URI: https://github.com/GatoGraphQL/GatoGraphQL
Description: Addition of elements to the GraphQL schema to test the Gato GraphQL plugin
Version: 1.0.13
Requires at least: 5.4
Requires PHP: 8.1
Author: Gato GraphQL
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: gatographql-testing-schema
Domain Path: /languages
*/

use GatoGraphQL\TestingSchema\GatoGraphQLExtension;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load translations
 * @todo Re-enable when an actual translation (*.po/*.mo) is provided
 * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2051
 */
// \add_action('init', function (): void {
//     load_plugin_textdomain('gatographql-testing-schema', false, plugin_basename(__FILE__) . '/languages');
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
         * 
         * Important: Do not modify the formatting of this PHP code!
         * A regex will search for this exact pattern, to update the
         * version in the ReleaseWorker when deploying for PROD.
         *
         * @see src/OnDemand/Symplify/MonorepoBuilder/Release/ReleaseWorker/ConvertVersionForProdInPluginMainFileReleaseWorker.php
         *
         * @gatographql-readonly-code
         */
        $extensionVersion = '1.0.13';
        $extensionName = \__('Gato GraphQL - Testing Schema', 'gatographql-testing-schema');
        /**
         * Important: Do not modify the formatting of this PHP code!
         * A regex will search for this exact pattern, to update the
         * version in the ReleaseWorker when deploying for PROD.
         *
         * @see src/OnDemand/Symplify/MonorepoBuilder/Release/ReleaseWorker/BumpVersionForDevInPluginMainFileReleaseWorker.php
         *
         * @gatographql-readonly-code
         */
        $mainPluginVersionConstraint = '^1.0';
        
        /**
         * Validate Gato GraphQL is active
         */
        if (!class_exists(Plugin::class)) {
            \add_action('admin_notices', function () use ($extensionName) {
                $adminNotice_safe = sprintf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    sprintf(
                        __('Plugin <strong>%s</strong> is not installed or activated. Without it, plugin <strong>%s</strong> will not be loaded.', 'gatographql-testing-schema'),
                        __('Gato GraphQL', 'gatographql-testing-schema'),
                        $extensionName
                    )
                );
                echo $adminNotice_safe;
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
         *
         * @gatographql-readonly-code
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
