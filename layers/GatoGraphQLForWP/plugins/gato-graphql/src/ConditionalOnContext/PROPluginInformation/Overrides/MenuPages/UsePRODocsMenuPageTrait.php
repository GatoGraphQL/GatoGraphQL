<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

trait UsePRODocsMenuPageTrait
{
    protected function enqueuePRODocsAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'gato-graphql-docs-pro',
            $mainPluginURL . 'assets-pro/css/docs.css',
            array(),
            $mainPluginVersion
        );
    }
}
