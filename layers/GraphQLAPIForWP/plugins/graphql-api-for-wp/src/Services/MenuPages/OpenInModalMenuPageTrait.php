<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

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
        $mainPluginURL = (string) MainPluginManager::getConfig('url');
        $mainPluginVersion = (string) MainPluginManager::getConfig('version');

        /**
         * Hide the menus
         */
        \wp_enqueue_style(
            'graphql-api-hide-admin-bar',
            $mainPluginURL . 'assets/css/hide-admin-bar.css',
            array(),
            $mainPluginVersion
        );
        /**
         * Styles for content within the modal window
         */
        \wp_enqueue_style(
            'graphql-api-modal-window-content',
            $mainPluginURL . 'assets/css/modal-window-content.css',
            array(),
            $mainPluginVersion
        );
    }
}
