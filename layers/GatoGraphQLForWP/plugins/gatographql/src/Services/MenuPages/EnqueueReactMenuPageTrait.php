<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

trait EnqueueReactMenuPageTrait
{
    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueReactAssets(bool $addInFooter = true): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        \wp_enqueue_script(
            'gatographql-react',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/react.production.min.js',
            array(),
            $mainPluginVersion,
            $addInFooter
        );
        \wp_enqueue_script(
            'gatographql-react-dom',
            $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/react-dom.production.min.js',
            array('gatographql-react'),
            $mainPluginVersion,
            $addInFooter
        );
    }
}
