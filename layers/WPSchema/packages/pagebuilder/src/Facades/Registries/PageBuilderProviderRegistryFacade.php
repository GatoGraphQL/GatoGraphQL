<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Facades\Registries;

use PoP\Root\App;
use PoPWPSchema\PageBuilder\Registries\PageBuilderProviderRegistryInterface;

class PageBuilderProviderRegistryFacade
{
    public static function getInstance(): PageBuilderProviderRegistryInterface
    {
        /**
         * @var PageBuilderProviderRegistryInterface
         */
        $service = App::getContainer()->get(PageBuilderProviderRegistryInterface::class);
        return $service;
    }
}
