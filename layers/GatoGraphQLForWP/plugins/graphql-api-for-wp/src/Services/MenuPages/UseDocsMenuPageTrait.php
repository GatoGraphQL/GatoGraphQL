<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

trait UseDocsMenuPageTrait
{
    protected function enqueueDocsAssets(): void
    {
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'gato-graphql-docs',
            $mainPluginURL . 'assets/css/docs.css',
            array(),
            $mainPluginVersion
        );
    }
}
