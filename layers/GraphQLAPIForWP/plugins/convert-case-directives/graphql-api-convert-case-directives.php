<?php
/*
Plugin Name: GraphQL API - Convert Case Directives
Plugin URI: https://github.com/GraphQLAPI/convert-case-directives
Description: Directives to convert lower/title/upper case for the GraphQL API for WordPress
Version: 0.7.13
Requires at least: 5.4
Requires PHP: 8.0
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-convert-case-directives
Domain Path: /languages
*/

use GraphQLAPI\ConvertCaseDirectives\PluginInfo;
use GraphQLAPI\ConvertCaseDirectives\GraphQLAPIExtension;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

register_activation_hook(__FILE__, function (): void {
    \update_option('graphql-api-extension', true);
});

/**
 * Create and set-up the extension
 */
add_action('plugins_loaded', function (): void {
    /**
     * Validate the GraphQL API plugin is active
     */
    if (!class_exists('\GraphQLAPI\GraphQLAPI\Plugin')) {
        \add_action('admin_notices', function () {
            _e(sprintf(
                '<div class="notice notice-error">' .
                    '<p>%s</p>' .
                '</div>',
                sprintf(
                    __('Plugin <strong>%s</strong> is not installed or activated. Without it, plugin <strong>%s</strong> will not be loaded.', 'graphql-api-events-manager'),
                    __('GraphQL API for WordPress', 'graphql-api-convert-case-directives'),
                    __('GraphQL API - Convert Case Directives', 'graphql-api-convert-case-directives')
                )
            ));
        });
        return;
    }

    /**
     * Make sure this plugin is not duplicated.
     */
    if (class_exists('\GraphQLAPI\ConvertCaseDirectives\PluginInfo')) {
        \add_action('admin_notices', function () {
            _e(sprintf(
                '<div class="notice notice-error">' .
                    '<p>%s</p>' .
                '</div>',
                sprintf(
                    __('Plugin <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'graphql-api'),
                    __('GraphQL API - Convert Case Directives', 'graphql-api-events-manager'),
                    PluginInfo::get('version'),
                    '0.7.13'
                )
            ));
        });
        return;
    }

    // Load Composer’s autoloader
    require_once(__DIR__ . '/vendor/autoload.php');

    // Initialize the Plugin information
    PluginInfo::init([
        'version' => '0.7.13',
        'dir' => dirname(__FILE__),
        'url' => plugin_dir_url(__FILE__),
    ]);

    (new GraphQLAPIExtension(__FILE__))->setup();
});
