<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Assets;

use GatoGraphQL\GatoGraphQL\PluginApp;

trait UseImageWidthsAssetsTrait
{
    protected function enqueueImageWidthsAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        \wp_enqueue_style(
            'gato-graphql-image-widths',
            $mainPluginURL . 'assets/css/image-widths.css',
            array(),
            $mainPluginVersion
        );
    }
}
