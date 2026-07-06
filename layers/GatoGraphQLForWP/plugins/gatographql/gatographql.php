<?php
/*
Plugin Name: Gato GraphQL
Plugin URI: https://gatographql.com
Description: Powerful and flexible GraphQL server for WordPress.
Version: 19.0.1
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

use GatoGraphQL\GatoGraphQL\Constants\ExtensionDataOptions;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceVersion;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use PoPIncludes\GatoGraphQL\Startup;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

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
$pluginVersion = '19.0.1';
$pluginName = 'Gato GraphQL';
$pluginProductName = 'Gato GraphQL';

/**
 * If the plugin is already registered, halt loading
 */
if (class_exists(Plugin::class)) {
    return;
}

/**
 * Execute always first, to guarantee the capability is registered even if the
 * webserver doesn't have enough memory. Otherwise, once it fails, the capability
 * won't be registered (even after increasing the memory limit), and the plugin
 * will not be available on the menu.
 *
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

// Validate that there is enough memory to run the plugin
require_once __DIR__ . '/includes/startup.php';
if (!Startup::checkGatoGraphQLMemoryRequirements($pluginName)) {
    return;
}

add_action('init', function (): void {
    // Register the global JS-pack resolver once (covers every extension's scripts).
    Startup::registerScriptTranslationFileResolver();
    Startup::loadTextdomainWithFallback(__DIR__ . '/languages/', basename(__FILE__, '.php') . '-');
}, PHP_INT_MIN);

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

// Validate the license
$extensionManager = PluginApp::getExtensionManager();
if (!$extensionManager->assertCommercialLicenseHasBeenActivated(
    __FILE__,
    $pluginProductName,
    $pluginName,
    $pluginVersion,
    [
        ExtensionDataOptions::CHANGELOG_URL => 'https://gatographql.com/changelog',
        ExtensionDataOptions::HOMEPAGE_URL => 'https://gatographql.com',
        ExtensionDataOptions::MARKETPLACE_PRODUCT_IDS => [
            MarketplaceVersion::V2_FLUENTCART => 249,
        ],
        ExtensionDataOptions::IS_LICENSE_NEEDED => false,
    ]
)) {
    return;
}
