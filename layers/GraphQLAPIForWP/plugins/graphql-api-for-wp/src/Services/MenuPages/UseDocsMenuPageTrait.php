<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;

trait UseDocsMenuPageTrait
{
    protected function enqueueDocsAssets(): void
    {
        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();

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
