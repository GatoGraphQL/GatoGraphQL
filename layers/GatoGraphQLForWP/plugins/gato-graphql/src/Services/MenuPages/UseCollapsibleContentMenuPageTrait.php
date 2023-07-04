<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ContentPrinters\CollapsibleContentPrinterTrait;
use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * Menu page that uses tabpanels to organize its content
 */
trait UseCollapsibleContentMenuPageTrait
{
    use CollapsibleContentPrinterTrait;
    
    /**
     * Enqueue the required assets
     */
    protected function enqueueCollapsibleContentAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        \wp_enqueue_script(
            'gato-graphql-collapse',
            $mainPluginURL . 'assets/js/collapse.js',
            array('jquery'),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'gato-graphql-collapse',
            $mainPluginURL . 'assets/css/collapse.css',
            array(),
            $mainPluginVersion
        );
    }
}
