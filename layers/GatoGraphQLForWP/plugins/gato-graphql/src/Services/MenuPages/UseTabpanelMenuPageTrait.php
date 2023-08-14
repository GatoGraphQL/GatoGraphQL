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
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'gatographql-tabpanel',
            $mainPluginURL . 'assets/css/tabpanel.css',
            array(),
            $mainPluginVersion
        );
        \wp_enqueue_script(
            'gatographql-tabpanel',
            $mainPluginURL . 'assets/js/tabpanel.js',
            array('jquery'),
            $mainPluginVersion
        );
    }
}
