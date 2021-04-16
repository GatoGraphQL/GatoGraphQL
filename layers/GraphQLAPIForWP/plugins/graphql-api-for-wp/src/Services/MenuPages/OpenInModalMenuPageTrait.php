<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\PluginInfo;

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
            PluginInfo::get('url') . 'assets/css/hide-admin-bar.css',
            array(),
            PluginInfo::get('version')
        );
        /**
         * Styles for content within the modal window
         */
        \wp_enqueue_style(
            'graphql-api-modal-window-content',
            PluginInfo::get('url') . 'assets/css/modal-window-content.css',
            array(),
            PluginInfo::get('version')
        );
    }
}
