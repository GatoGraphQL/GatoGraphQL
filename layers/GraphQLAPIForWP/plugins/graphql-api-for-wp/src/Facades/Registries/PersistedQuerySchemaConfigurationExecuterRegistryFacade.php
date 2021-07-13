<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQuerySchemaConfigurationExecuterRegistryInterface;

class PersistedQuerySchemaConfigurationExecuterRegistryFacade
{
    public static function getInstance(): PersistedQuerySchemaConfigurationExecuterRegistryInterface
    {
        /**
         * @var PersistedQuerySchemaConfigurationExecuterRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PersistedQuerySchemaConfigurationExecuterRegistryInterface::class);
        return $service;
    }
}
