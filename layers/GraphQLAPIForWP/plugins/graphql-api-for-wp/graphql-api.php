<?php
/*
Plugin Name: GraphQL API for WordPress
Plugin URI: https://graphql-api.com
Description: Transform your WordPress site into a GraphQL server.
Version: 0.9.0
Requires at least: 5.4
Requires PHP: 8.0
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api
Domain Path: /languages
GitHub Plugin URI: GraphQLAPI/graphql-api-for-wp-dist
*/

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginConfiguration;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load translations
 */
add_action('init', function (): void {
    load_plugin_textdomain('graphql-api', false, plugin_basename(__FILE__) . '/languages');
});

$pluginVersion = '0.9.0';
$pluginName = __('GraphQL API for WordPress', 'graphql-api');

/**
 * If the plugin is already registered, print an error and halt loading
 */
if (class_exists(Plugin::class) && !App::getMainPluginManager()->assertIsValid($pluginVersion)) {
    return;
}

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Initialize the GraphQL API App
App::initializePlugin();

// Create and set-up the plugin instance
App::getMainPluginManager()->register(new Plugin(
    __FILE__,
    $pluginVersion,
    $pluginName,
    new PluginConfiguration()
))->setup();
