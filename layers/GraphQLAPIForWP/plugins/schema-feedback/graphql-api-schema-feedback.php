<?php
/*
Plugin Name: GraphQL API - Schema Feedback
Plugin URI: https://github.com/GraphQLAPI/schema-feedback
Description: Make the GraphQL API provide feedback in the response of the GraphQL query
Version: 0.7.13
Requires at least: 5.4
Requires PHP: 8.0
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api-schema-feedback
Domain Path: /languages
*/

use GraphQLAPI\SchemaFeedback\GraphQLAPIExtension;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('GRAPHQL_API_SCHEMA_FEEDBACK_PLUGIN_FILE', __FILE__);
define('GRAPHQL_API_SCHEMA_FEEDBACK_VERSION', '0.7.13');

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Create and set-up the plugin instance
(new GraphQLAPIExtension(__FILE__))->setup();
