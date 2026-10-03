<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;
use GatoGraphQL\GatoGraphQL\Assets\UseImageWidthsAssetsTrait;

trait UseDocsMenuPageTrait
{
    use EnqueuePluginAssetsTrait;
    use UseImageWidthsAssetsTrait;

    protected function enqueueDocsAssets(): void
    {
        $this->enqueueMainPluginAssetStyle(
            'gatographql-docs',
            'assets/css/docs.css'
        );

        $this->enqueueImageWidthsAssets();
    }
}
