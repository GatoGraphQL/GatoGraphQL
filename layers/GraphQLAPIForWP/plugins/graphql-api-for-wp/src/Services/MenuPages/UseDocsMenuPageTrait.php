<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

trait UseDocsMenuPageTrait
{
    protected function enqueueDocsAssets(): void
    {
        $mainPluginURL = \GraphQLAPI\GraphQLAPI\PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = \GraphQLAPI\GraphQLAPI\PluginApp::getMainPlugin()->getPluginVersion();

        /**
         * Add tabs to the documentation
         */
        \wp_enqueue_style(
            'graphql-api-docs',
            $mainPluginURL . 'assets/css/docs.css',
            array(),
            $mainPluginVersion
        );
    }
}
