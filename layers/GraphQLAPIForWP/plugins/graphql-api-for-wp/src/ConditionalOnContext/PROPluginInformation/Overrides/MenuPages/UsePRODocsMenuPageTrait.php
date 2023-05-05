<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

trait UsePRODocsMenuPageTrait
{
    protected function enqueuePRODocsAssets(): void
    {
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'graphql-api-docs-pro',
            $mainPluginURL . 'assets-pro/css/docs.css',
            array(),
            $mainPluginVersion
        );
    }
}
