<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Overrides\MenuPages;

use GraphQLAPI\GraphQLAPI\App;

trait UsePRODocsMenuPageTrait
{
    protected function enqueuePRODocsAssets(): void
    {
        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();

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
