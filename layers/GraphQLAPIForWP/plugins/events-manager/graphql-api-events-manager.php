<?php
/*
Plugin Name: GraphQL API - Events Manager
Plugin URI: https://github.com/GraphQLAPI/events-manager
Description: Integration of plugin Events Manager with the GraphQL API for WordPress
Version: 0.7.13
Requires at least: 5.4
Requires PHP: 8.0
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-events-manager
Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use GraphQLAPI\EventsManager\PluginInfo;
use GraphQLAPI\EventsManager\GraphQLAPIExtension;

register_activation_hook(__FILE__, function (): void {
    \update_option('graphql-api-extension', true);
});

add_action('plugins_loaded', function (): void {
    if (!class_exists('\GraphQLAPI\GraphQLAPI\Plugin')) {
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
