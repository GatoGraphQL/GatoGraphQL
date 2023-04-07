<?php
/*
Plugin Name: GraphQL API for WordPress
Plugin URI: https://graphql-api.com
Description: Transform your WordPress site into a GraphQL server.
Version: 1.0.0-dev
Requires at least: 5.4
Requires PHP: 8.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api
Domain Path: /languages
GitHub Plugin URI: GraphQLAPI/graphql-api-for-wp-dist
*/

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\AppThread;
use GraphQLAPI\GraphQLAPI\Plugin;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load translations
 * @todo Re-enable when an actual translation (*.po/*.mo) is provided
 * @see https://github.com/leoloso/PoP/issues/2051
 */
// add_action('init', function (): void {
//     load_plugin_textdomain('graphql-api', false, plugin_basename(__FILE__) . '/languages');
// });

/**
 * Plugin's name and version.
 *
 * Use a stability suffix as supported by Composer.
 *
 * @see https://getcomposer.org/doc/articles/versions.md#stabilities
 */
$pluginVersion = '1.0.0-dev';
$pluginName = __('GraphQL API for WordPress', 'graphql-api');

/**
 * If the plugin is already registered, print an error and halt loading
 */
if (class_exists(Plugin::class) && !App::getMainPluginManager()->assertIsValid($pluginVersion)) {
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

// Initialize the GraphQL API App
App::setAppThread(new AppThread());
App::initializePlugin();

// Create and set-up the plugin instance
App::getMainPluginManager()->register(new Plugin(
    __FILE__,
    $pluginVersion,
    $pluginName,
    $commitHash
))->setup();
