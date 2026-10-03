<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Assets\EnqueuePluginAssetsTrait;

trait EnqueueReactMenuPageTrait
{
    use EnqueuePluginAssetsTrait;

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueReactAssets(bool $addInFooter = true): void
    {
        $this->enqueueMainPluginAssetScript(
            'gatographql-react',
            'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/react.production.min.js',
            array(),
            $addInFooter
        );
        $this->enqueueMainPluginAssetScript(
            'gatographql-react-dom',
            'vendor/graphql-by-pop/graphql-clients-for-wp/clients/voyager/assets/vendors/react-dom.production.min.js',
            array('gatographql-react'),
            $addInFooter
        );
    }
}
