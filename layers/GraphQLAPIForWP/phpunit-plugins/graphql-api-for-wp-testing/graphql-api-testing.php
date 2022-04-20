<?php
/*
Plugin Name: Testing - GraphQL API for WordPress
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

/**
 * Send the WP REST nonce as a header, to make it easier
 * to execute REST endpoints for integration tests.
 *
 * @see layers/GraphQLAPIForWP/phpunit-packages/webserver-requests/tests/AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTest.php
 */
function addRESTNonceAsHeader(): void
{
    if (!\is_user_logged_in()) {
        return;
    }
    header('X-WP-REST-NONCE: ' . wp_create_nonce('wp_rest'));
}
add_filter('init', 'addRESTNonceAsHeader');
