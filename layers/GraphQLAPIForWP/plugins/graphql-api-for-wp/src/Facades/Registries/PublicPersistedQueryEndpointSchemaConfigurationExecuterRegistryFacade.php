<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;

class PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryFacade
{
    public static function getInstance(): PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
    {
        /**
         * @var PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
         */
        $service = App::getContainer()->get(PublicPersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface::class);
        return $service;
    }
}
