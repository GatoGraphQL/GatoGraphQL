<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\PluginApp;

trait ExtensionsMenuPageTrait
{
    protected function enqueueExtensionAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        /**
         * Hide the bottom part of the extension items on the table,
         * as it contains unneeded information, and just hiding it
         * is easier than editing the PHP code
         */
        \wp_enqueue_style(
            'gatographql-extensions',
            $mainPluginURL . 'assets/css/extensions.css',
            array(),
            $mainPluginVersion
        );
    }
}
