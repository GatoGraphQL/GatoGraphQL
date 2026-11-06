<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;

/**
 * Menu page that opens in modal window
 */
trait OpenInModalMenuPageTrait
{
    use EnqueuePluginAssetsTrait;
    use ResponsiveVideoContainerMenuPageTrait;

    /**
     * Enqueue the required assets
     */
    protected function enqueueModalAssets(): void
    {
        /**
         * Hide the menus
         */
        $this->enqueueMainPluginAssetStyle(
            'gatographql-hide-admin-bar',
            'assets/css/hide-admin-bar.css'
        );

        /**
         * Styles for content within the modal window
         */
        $this->enqueueMainPluginAssetStyle(
            'gatographql-modal-window-content',
            'assets/css/modal-window-content.css'
        );

        $this->enqueueResponsiveVideoContainerAssets();
    }
}
