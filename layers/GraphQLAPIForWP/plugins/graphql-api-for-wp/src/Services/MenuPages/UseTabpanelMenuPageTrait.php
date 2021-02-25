<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

/**
 * Menu page that uses tabpanels to organize its content
 */
trait UseTabpanelMenuPageTrait
{
    /**
     * Enqueue the required assets
     */
    protected function enqueueTabpanelAssets(): void
    {
        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'graphql-api-tabpanel',
            \GRAPHQL_API_URL . 'assets/css/tabpanel.css',
            array(),
            \GRAPHQL_API_VERSION
        );
        \wp_enqueue_script(
            'graphql-api-tabpanel',
            \GRAPHQL_API_URL . 'assets/js/tabpanel.js',
            array('jquery'),
            \GRAPHQL_API_VERSION
        );
    }
}
