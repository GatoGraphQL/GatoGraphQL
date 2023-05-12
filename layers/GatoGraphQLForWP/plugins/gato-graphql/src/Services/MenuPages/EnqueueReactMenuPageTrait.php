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
            'gato-graphql-react',
            $mainPluginURL . 'assets/js/vendors/react.min.js',
            array(),
            $mainPluginVersion,
            $addInFooter
        );
        \wp_enqueue_script(
            'gato-graphql-react-dom',
            $mainPluginURL . 'assets/js/vendors/react-dom.min.js',
            array('gato-graphql-react'),
            $mainPluginVersion,
            $addInFooter
        );
    }
}
