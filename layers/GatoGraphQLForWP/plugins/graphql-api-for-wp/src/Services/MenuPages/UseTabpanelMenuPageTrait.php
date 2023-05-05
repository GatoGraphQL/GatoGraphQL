<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

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
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'gato-graphql-tabpanel',
            $mainPluginURL . 'assets/css/tabpanel.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_script(
            'gato-graphql-tabpanel',
            $mainPluginURL . 'assets/js/tabpanel.js',
            array('jquery'),
            $mainPluginVersion
        );
    }
}
