<?php
/*
Plugin Name: GraphQL API - Extension Demo
Plugin URI: https://github.com/GraphQLAPI/extension-demo
Description: Demonstration of extending the GraphQL schema, for the GraphQL API for WordPress
Version: 0.8.0
Requires at least: 5.4
Requires PHP: 8.0
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-extension-demo
Domain Path: /languages
*/

use GraphQLAPI\ExtensionDemo\GraphQLAPIExtension;
use GraphQLAPI\ExtensionDemo\PluginInfo;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

register_activation_hook(__FILE__, function (): void {
    \update_option('graphql-api-extension', true);
});

/**
 * Load translations
 */
\add_action('init', function (): void {
    load_plugin_textdomain('graphql-api-extension-demo', false, plugin_basename(__FILE__) . '/languages');
});

/**
 * Create and set-up the extension
 */
add_action('plugins_loaded', function (): void {
    /**
     * Validate the GraphQL API plugin is active
     */
    if (!class_exists(Plugin::class)) {
        \add_action('admin_notices', function () {
            _e(sprintf(
                '<div class="notice notice-error">' .
                    '<p>%s</p>' .
                '</div>',
                sprintf(
                    __('Plugin <strong>%s</strong> is not installed or activated. Without it, plugin <strong>%s</strong> will not be loaded.', 'graphql-api-extension-demo'),
                    __('GraphQL API for WordPress', 'graphql-api-extension-demo'),
                    __('GraphQL API - Extension Demo', 'graphql-api-extension-demo')
                )
            ));
        });
        return;
    }

    // Load Composer’s autoloader
    require_once(__DIR__ . '/vendor/autoload.php');

    // Initialize the Plugin information
    PluginInfo::init([
        'version' => '0.8.0',
        'file' => __FILE__,
        'baseName' => plugin_basename(__FILE__),
        'dir' => dirname(__FILE__),
        'url' => plugin_dir_url(__FILE__),
    ]);

    ExtensionManager::register(new GraphQLAPIExtension(__FILE__, '0.8.0'))->setup();
});
