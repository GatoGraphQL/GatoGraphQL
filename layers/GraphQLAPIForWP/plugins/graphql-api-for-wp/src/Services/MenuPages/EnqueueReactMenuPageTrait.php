<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

trait EnqueueReactMenuPageTrait
{

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueReactAssets(bool $addInFooter = true): void
    {
        $mainPluginURL = (string) MainPluginManager::getConfig('url');
        $mainPluginVersion = (string) MainPluginManager::getConfig('version');

        \wp_enqueue_script(
            'graphql-api-react',
            $mainPluginURL . 'assets/js/vendors/react.min.js',
            array(),
            $mainPluginVersion,
            $addInFooter
        );
        \wp_enqueue_script(
            'graphql-api-react-dom',
            $mainPluginURL . 'assets/js/vendors/react-dom.min.js',
            array('graphql-api-react'),
            $mainPluginVersion,
            $addInFooter
        );
    }
}
