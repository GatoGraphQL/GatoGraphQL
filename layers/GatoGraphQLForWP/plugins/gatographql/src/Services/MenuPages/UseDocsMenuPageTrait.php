<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\UseImageWidthsAssetsTrait;
use GatoGraphQL\GatoGraphQL\PluginApp;

trait UseDocsMenuPageTrait
{
    use UseImageWidthsAssetsTrait;

    protected function enqueueDocsAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        \wp_enqueue_style(
            'gatographql-docs',
            $mainPluginURL . 'assets/css/docs.css?a=2',
            array(),
            $mainPluginVersion
        );

        $this->enqueueImageWidthsAssets();
    }
}
