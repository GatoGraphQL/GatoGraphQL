<?php
use GraphQLAPI\SchemaFeedback\Plugin;
/*
Plugin Name: GraphQL API - Schema Feedback
Plugin URI: https://github.com/GraphQLAPI/schema-feedback
Description: Make the GraphQL API provide feedback in the response of the GraphQL query
Version: 0.1.0
Requires at least: 5.4
Requires PHP: 7.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-schema-feedback
Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use PoP\Engine\AppLoader;

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Plugin instance
$plugin = new Plugin();

// Functions to execute when activating/deactivating the plugin
\register_activation_hook(__FILE__, [$plugin, 'activate']);
\register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);

// Initialize the plugin's Component and, with it, all its dependencies from PoP
AppLoader::addComponentClassesToInitialize(
    [
        \PoP\SchemaFeedback\Component::class,
    ]
);

/**
 * Wait until "plugins_loaded" to initialize the plugin
 */
add_action('plugins_loaded', function () use ($plugin) {
    $plugin->initialize();
});
