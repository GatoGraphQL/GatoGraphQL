<?php
/*
Plugin Name: GraphQL API for WordPress
Plugin URI: https://graphql-api.com
Description: Transform your WordPress site into a GraphQL server.
Version: 0.7.13
Requires at least: 5.4
Requires PHP: 7.4
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api
Domain Path: /languages
GitHub Plugin URI: GraphQLAPI/graphql-api-for-wp-dist
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use GraphQLAPI\GraphQLAPI\Plugin;

/**
 * Make sure this plugin is not duplicated.
 * For instance, if zip file already exists in Downloads folder, then
 * the newly downloaded file will be renamed (eg: graphql-api(2).zip)
 * and the plugin will exist twice, as graphql-api/... and graphql-api2/...
 */
if (defined('GRAPHQL_API_VERSION')) {
    \add_action('admin_notices', function () {
        _e(sprintf(
            '<div class="notice notice-error">' .
                '<p>%s</p>' .
            '</div>',
            sprintf(
                __('Plugin <strong>GraphQL API for WordPress</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'),
                \GRAPHQL_API_VERSION,
                '0.7.13'
            )
        ));
    });
    return;
}
define('GRAPHQL_API_VERSION', '0.7.13');
define('GRAPHQL_API_PLUGIN_FILE', __FILE__);
define('GRAPHQL_API_DIR', dirname(__FILE__));
define('GRAPHQL_API_URL', plugin_dir_url(__FILE__));
define('GRAPHQL_API_BASE_NAME', plugin_basename(__FILE__)); // "graphql-api/graphql-api.php"
define('GRAPHQL_API_PLUGIN_NAME', dirname(plugin_basename(__FILE__))); // "graphql-api"

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
(new Plugin(__FILE__))->setup();
