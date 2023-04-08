<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

trait EnqueueReactMenuPageTrait
{
    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueReactAssets(bool $addInFooter = true): void
    {
        $mainPluginURL = \GraphQLAPI\GraphQLAPI\PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = \GraphQLAPI\GraphQLAPI\PluginApp::getMainPlugin()->getPluginVersion();

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
