<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

trait ResponsiveVideoContainerMenuPageTrait
{
    /**
     * Enqueue the required assets
     */
    protected function enqueueResponsiveVideoContainerAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        /**
         * Styles for content within the modal window
         */
        \wp_enqueue_style(
            'gato-graphql-responsive-video-container',
            $mainPluginURL . 'assets/css/responsive-video-container.css',
            array(),
            $mainPluginVersion
        );
    }
}
