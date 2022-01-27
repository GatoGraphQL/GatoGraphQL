<?php

declare(strict_types=1);

namespace PoP\Root\Facades\Registries;

use PoP\Root\App;
use PoP\Root\Registries\AppStateProviderRegistryInterface;

class AppStateProviderRegistryFacade
{
    public static function getInstance(): AppStateProviderRegistryInterface
    {
        /**
         * @var AppStateProviderRegistryInterface
         */
        $service = App::getContainer()->get(AppStateProviderRegistryInterface::class);
        return $service;
    }
}
