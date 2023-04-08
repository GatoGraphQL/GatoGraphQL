<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

trait UsePRODocsMenuPageTrait
{
    protected function enqueuePRODocsAssets(): void
    {
        $mainPluginURL = \GraphQLAPI\GraphQLAPI\PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = \GraphQLAPI\GraphQLAPI\PluginApp::getMainPlugin()->getPluginVersion();

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
