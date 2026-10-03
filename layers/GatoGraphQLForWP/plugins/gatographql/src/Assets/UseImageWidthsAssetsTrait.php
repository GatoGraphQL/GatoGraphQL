<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Assets;

trait UseImageWidthsAssetsTrait
{
    use EnqueuePluginAssetsTrait;

    protected function enqueueImageWidthsAssets(): void
    {
        $this->enqueueMainPluginAssetStyle(
            'gatographql-image-widths',
            'assets/css/image-widths.css'
        );
    }
}
