<?php
/*
Plugin Name: Gato GraphQL Engine
Plugin URI: https://gatographql.com
GitHub Plugin URI: https://github.com/GatoGraphQL/GatoGraphQL
Description: Standalone engine for Gato GraphQL, the powerful and flexible GraphQL server for WordPress.
Version: 6.1.0-dev
Requires at least: 6.1
Requires PHP: 8.1
Author: Gato GraphQL
Author URI: https://gatographql.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: gatographql-engine
Domain Path: /languages
GitHub Plugin URI: GatoGraphQLForWordPress/gatographql-engine-dist
*/

use GatoGraphQL\GatoGraphQLEngine\Plugin;
use GatoGraphQL\GatoGraphQLEngine\PluginApp;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load translations
 * @todo Re-enable when an actual translation (*.po/*.mo) is provided
 * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2051
 */
// add_action('init', function (): void {
//     load_plugin_textdomain('gatographql-engine', false, plugin_basename(__FILE__) . '/languages');
// });

/**
 * Plugin's name and version.
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
$pluginVersion = '6.1.0-dev';
$pluginName = __('Gato GraphQL Engine', 'gatographql-engine');

/**
 * If the plugin is already registered, print an error and halt loading
 */
if (class_exists(Plugin::class) && !PluginApp::getMainPluginManager()->assertIsValid($pluginVersion)) {
    return;
}

/**
 * Validate that there is enough memory to run the plugin.
 *
 * > Note that to have no memory limit, set this directive to -1.
 *
 * @see https://www.php.net/manual/en/ini.core.php#ini.sect.resource-limits
 */
$phpMemoryLimit = \ini_get('memory_limit');
$phpMemoryLimitInBytes = \wp_convert_hr_to_bytes($phpMemoryLimit);
if ($phpMemoryLimitInBytes !== -1) {
    // Minimum: 64MB
    $minRequiredPHPMemoryLimit = '64M';
    $minRequiredPHPMemoryLimitInBytes = \wp_convert_hr_to_bytes($minRequiredPHPMemoryLimit);
    if ($phpMemoryLimitInBytes < $minRequiredPHPMemoryLimitInBytes) {
        \add_action('admin_notices', function () use ($minRequiredPHPMemoryLimit, $phpMemoryLimit) {
            printf(
                '<div class="notice notice-error"><p>%s</p></div>',
                sprintf(
                    __('Plugin <strong>%1$s</strong> requires at least <strong>%2$s</strong> of memory, however the server\'s PHP memory limit is set to <strong>%3$s</strong>. Please increase the memory limit to load %1$s.', 'gatographql-engine'),
                    __('Gato GraphQL Engine', 'gatographql-engine'),
                    $minRequiredPHPMemoryLimit,
                    $phpMemoryLimit
                )
            );
        });
        return;
    }
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

// Initialize the Gato GraphQL App
PluginApp::initializePlugin();

// Create and set-up the plugin instance
PluginApp::getMainPluginManager()->register(new Plugin(
    __FILE__,
    $pluginVersion,
    $pluginName,
    $commitHash
))->setup();
