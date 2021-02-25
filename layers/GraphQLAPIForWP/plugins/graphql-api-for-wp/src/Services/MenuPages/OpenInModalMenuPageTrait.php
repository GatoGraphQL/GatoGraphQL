<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

/**
 * Menu page that opens in modal window
 */
trait OpenInModalMenuPageTrait
{
    /**
     * Enqueue the required assets
     */
    protected function enqueueModalAssets(): void
    {
        /**
         * Hide the menus
         */
        \wp_enqueue_style(
            'graphql-api-hide-admin-bar',
            \GRAPHQL_API_URL . 'assets/css/hide-admin-bar.css',
            array(),
            \GRAPHQL_API_VERSION
        );
        /**
         * Styles for content within the modal window
         */
        \wp_enqueue_style(
            'graphql-api-modal-window-content',
            \GRAPHQL_API_URL . 'assets/css/modal-window-content.css',
            array(),
            \GRAPHQL_API_VERSION
        );
    }
}
