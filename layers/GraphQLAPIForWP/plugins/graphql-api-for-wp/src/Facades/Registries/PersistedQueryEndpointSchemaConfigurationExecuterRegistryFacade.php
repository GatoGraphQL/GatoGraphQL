<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryEndpointSchemaConfigurationExecuterRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface::class);
        return $service;
    }
}
