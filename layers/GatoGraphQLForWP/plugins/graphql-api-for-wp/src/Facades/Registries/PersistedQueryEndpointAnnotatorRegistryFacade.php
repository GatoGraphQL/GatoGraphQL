<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;

class PersistedQueryEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointAnnotatorRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointAnnotatorRegistryInterface
         */
        $service = App::getContainer()->get(PersistedQueryEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
