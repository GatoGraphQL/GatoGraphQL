<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;

trait ExtensionsMenuPageTrait
{
    use EnqueuePluginAssetsTrait;

    protected function enqueueExtensionAssets(): void
    {
        /**
         * Hide the bottom part of the extension items on the table,
         * as it contains unneeded information, and just hiding it
         * is easier than editing the PHP code
         */
        $this->enqueueMainPluginAssetStyle(
            'gatographql-extensions',
            'assets/css/extensions.css'
        );
    }
}
