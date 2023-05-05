<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;

class PersistedQueryEndpointSchemaConfigurationExecuterRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
         */
        $service = App::getContainer()->get(PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface::class);
        return $service;
    }
}
