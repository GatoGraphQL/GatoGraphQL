<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;

/**
 * Menu page that uses tabpanels to organize its content
 */
trait UseTabpanelMenuPageTrait
{
    use EnqueuePluginAssetsTrait;

    /**
     * Enqueue the required assets
     */
    protected function enqueueTabpanelAssets(): void
    {
        /**
         * Add tabs to the documentation
         */
        $this->enqueueMainPluginAssetStyle(
            'gatographql-tabpanel',
            'assets/css/tabpanel.css'
        );
        $this->enqueueMainPluginAssetScript(
            'gatographql-tabpanel',
            'assets/js/tabpanel.js',
            array('jquery')
        );
    }
}
