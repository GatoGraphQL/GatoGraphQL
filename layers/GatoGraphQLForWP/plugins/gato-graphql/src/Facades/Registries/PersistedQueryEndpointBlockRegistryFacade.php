<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointBlockRegistryInterface;

class PersistedQueryEndpointBlockRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointBlockRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointBlockRegistryInterface
         */
        $service = App::getContainer()->get(PersistedQueryEndpointBlockRegistryInterface::class);
        return $service;
    }
}
