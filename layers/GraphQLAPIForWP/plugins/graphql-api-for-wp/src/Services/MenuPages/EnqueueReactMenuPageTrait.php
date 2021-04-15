<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\PluginInfo;

trait EnqueueReactMenuPageTrait
{

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueReactAssets(bool $addInFooter = true): void
    {
        \wp_enqueue_script(
            'graphql-api-react',
            PluginInfo::get('url') . 'assets/js/vendors/react.min.js',
            array(),
            PluginInfo::get('version'),
            $addInFooter
        );
        \wp_enqueue_script(
            'graphql-api-react-dom',
            PluginInfo::get('url') . 'assets/js/vendors/react-dom.min.js',
            array('graphql-api-react'),
            PluginInfo::get('version'),
            $addInFooter
        );
    }
}
