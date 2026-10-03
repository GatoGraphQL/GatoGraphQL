<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;
use GatoGraphQL\GatoGraphQL\ContentPrinters\CollapsibleContentPrinterTrait;

/**
 * Menu page that uses tabpanels to organize its content
 */
trait UseCollapsibleContentMenuPageTrait
{
    use EnqueuePluginAssetsTrait;
    use CollapsibleContentPrinterTrait;

    /**
     * Enqueue the required assets
     */
    protected function enqueueCollapsibleContentAssets(): void
    {
        $this->enqueueMainPluginAssetScript(
            'gatographql-collapse',
            'assets/js/collapse.js',
            array('jquery')
        );
        $this->enqueueMainPluginAssetStyle(
            'gatographql-collapse',
            'assets/css/collapse.css'
        );
    }
}
