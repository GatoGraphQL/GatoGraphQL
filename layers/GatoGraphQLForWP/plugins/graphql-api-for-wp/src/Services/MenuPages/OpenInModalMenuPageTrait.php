<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

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
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        /**
         * Hide the menus
         */
        \wp_enqueue_style(
            'gato-graphql-hide-admin-bar',
            $mainPluginURL . 'assets/css/hide-admin-bar.css',
            array(),
            $mainPluginVersion
        );
        /**
         * Styles for content within the modal window
         */
        \wp_enqueue_style(
            'gato-graphql-modal-window-content',
            $mainPluginURL . 'assets/css/modal-window-content.css',
            array(),
            $mainPluginVersion
        );
    }
}
