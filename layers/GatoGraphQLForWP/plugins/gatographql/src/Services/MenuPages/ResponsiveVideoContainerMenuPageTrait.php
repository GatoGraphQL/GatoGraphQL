<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;

trait ResponsiveVideoContainerMenuPageTrait
{
    use EnqueuePluginAssetsTrait;

    /**
     * Enqueue the required assets
     */
    protected function enqueueResponsiveVideoContainerAssets(): void
    {
        /**
         * Styles for content within the modal window
         */
        $this->enqueueMainPluginAssetStyle(
            'gatographql-responsive-video-container',
            'assets/css/responsive-video-container.css'
        );
    }
}
