<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Endpoints\AdminRESTAPIEndpointManager;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Settings\Options;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\Utilities\CustomHeaderAppender;
use WP_REST_Response;

use function add_action;
use function delete_option;
use function get_option;
use function flush_rewrite_rules;

class Plugin
{
    public function initialize(): void
    {
        /**
         * Send custom headers needed for development
         */
        new CustomHeaderAppender();

        /**
         * Initialize REST endpoints
         */
        new AdminRESTAPIEndpointManager();

        /**
         * Executing `flush_rewrite_rules` at the end of the execution
         * of the REST controller doesn't work, so do it at the
         * beginning instead, if set via a flag.
         */
        if (get_option(Options::FLUSH_REWRITE_RULES)) {
            delete_option(Options::FLUSH_REWRITE_RULES);
            add_action('init', flush_rewrite_rules(...), PHP_INT_MAX);
        }

        /**
         * Remove the "_link" entry from the WP REST API response,
         * so that the GraphQL response does not include the domain,
         * and then the same tests work for both "Integration Tests"
         * and "PROD Integration Tests".
         *
         * The hooks for CPTs must be generated one by one.
         *
         * @see https://stackoverflow.com/a/53505460
         * @see wp-includes/rest-api/endpoints/class-wp-rest-posts-controller.php
         */
        $cpts = ['post', 'page', 'attachment'];
        $hooks = [
            'rest_prepare_application_password',
            'rest_prepare_attachment',
            'rest_prepare_autosave',
            'rest_prepare_block_type',
            'rest_prepare_comment',
            'rest_prepare_nav_menu_item',
            'rest_prepare_menu_location',
            'rest_prepare_block_pattern',
            'rest_prepare_plugin',
            'rest_prepare_status',
            'rest_prepare_post_type',
            'rest_prepare_revision',
            'rest_prepare_sidebar',
            'rest_prepare_taxonomy',
            'rest_prepare_theme',
            'rest_prepare_url_details',
            'rest_prepare_user',
            'rest_prepare_widget_type',
            'rest_prepare_widget',
            ...array_map(
                fn (string $cpt) => 'rest_prepare_' . $cpt,
                $cpts
            )
        ];
        foreach ($hooks as $hook) {
            \add_filter(
                $hook,
                $this->removeRESTAPIResponseLink(...),
                PHP_INT_MAX
            );
        }
    }

    function removeRESTAPIResponseLink(WP_REST_Response $data): WP_REST_Response
    {
        foreach ($data->get_links() as $_linkKey => $_linkVal) {
            $data->remove_link($_linkKey);
        }
        return $data;
    }
}
