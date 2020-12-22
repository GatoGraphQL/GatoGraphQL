<?php
/*
Plugin Name: GraphQL API - Convert Case Directives
Plugin URI: https://github.com/GraphQLAPI/convert-case-directives
Description: Directives to convert lower/title/upper case for the GraphQL API
Version: 0.1.0
Requires at least: 5.4
Requires PHP: 7.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-convert-case-directives
Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('GRAPHQL_API_CONVERT_CASE_DIRECTIVES_PLUGIN_FILE', __FILE__);
define('GRAPHQL_API_CONVERT_CASE_DIRECTIVES_VERSION', '0.1.0');

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Create and set-up the plugin instance
(new \GraphQLAPI\ConvertCaseDirectives\Plugin())->setup();
