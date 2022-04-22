<?php
/*
Plugin Name: GraphQL API for WordPress - PHPUnit & Testing Utilities
Description: Utilities for testing the GraphQL API for WordPress
Version: 0.9.0
Requires at least: 5.4
Requires PHP: 8.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-testing
Domain Path: /languages
*/

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Endpoints\AdminRESTAPIEndpointManager;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Utilities\CustomHeaderAppender;
use GraphQLAPI\GraphQLAPI\Plugin;

use function add_action;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action(
    'plugins_loaded',
    function(): void {
        // Validate the GraphQL API plugin is installed, or exit
        if (!class_exists(Plugin::class)) {
            return;
        }
        
        // Load Composer’s autoloader
        require_once(__DIR__ . '/vendor/autoload.php');
        
        /**
         * Send custom headers needed for development
         */
        new CustomHeaderAppender();
        
        /**
         * Initialize REST endpoints
         */
        new AdminRESTAPIEndpointManager();
    }
);
