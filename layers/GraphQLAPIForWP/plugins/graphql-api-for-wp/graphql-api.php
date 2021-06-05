<?php
/*
Plugin Name: GraphQL API for WordPress
Plugin URI: https://graphql-api.com
Description: Transform your WordPress site into a GraphQL server.
Version: 0.8.0
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

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load translations
 */
\add_action('init', function (): void {
    load_plugin_textdomain('graphql-api', false, plugin_basename(__FILE__) . '/languages');
});

// Check Composer's autoload has been generated
$autoloadFile = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadFile)) {
    \add_action('admin_notices', function () {
        _e(sprintf(
            '<div class="notice notice-error">' .
                '<p>%s</p>' .
            '</div>',
            __('Dependencies for <strong>GraphQL API for WordPress</strong> are missing. Please install them by running <code>composer install</code> on the plugin\'s root folder. Until then, the plugin will be disabled.', 'graphql-api')
        ));
    });
    return;
}

// Load Composer’s autoloader
require_once($autoloadFile);

// Create and set-up the plugin instance
MainPluginManager::register(new Plugin(
    __FILE__,
    '0.8.0',
    __('GraphQL API for WordPress', 'graphql-api'),
))->setup();
