<?php
/*
Plugin Name: Gato GraphQL
Plugin URI: https://gatographql.com
GitHub Plugin URI: https://github.com/GatoGraphQL/GatoGraphQL
Description: Powerful and flexible GraphQL server for WordPress.
Version: 7.0.5
Requires at least: 6.1
Requires PHP: 8.1
Author: Gato GraphQL
Author URI: https://gatographql.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: gatographql
Domain Path: /languages
GitHub Plugin URI: GatoGraphQL/gatographql-dist
*/

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
// add_action('init', function (): void {
//     load_plugin_textdomain('gatographql', false, plugin_basename(__FILE__) . '/languages');
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
$pluginVersion = '7.0.5';
$pluginName = __('Gato GraphQL', 'gatographql');

/**
 * If the plugin is already registered, halt loading
 */
if (class_exists(Plugin::class)) {
    return;
}

// Validate that there is enough memory to run the plugin
require_once __DIR__ . '/includes/startup.php';
if (!\PoPIncludes\GatoGraphQL\Startup::checkGatoGraphQLMemoryRequirements($pluginName)) {
    return;
}

/**
 * Can't use Composer to load this file, as "vendor/" is loaded only
 * in the "plugins_loaded" hook, and that's too late to register
 * the capabilities.
 */
require_once __DIR__ . '/includes/capabilities.php';
require_once __DIR__ . '/includes/schema-editing-access-capabilities.php';
\PoPIncludes\GatoGraphQL\SchemaEditingAccessCapabilities::registerGatoGraphQLSchemaEditingAccessCapabilities(
    __FILE__,
    constant('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA')
);

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
