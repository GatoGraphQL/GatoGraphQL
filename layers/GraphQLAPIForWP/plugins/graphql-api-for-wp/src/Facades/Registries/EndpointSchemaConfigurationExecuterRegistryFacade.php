<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EndpointSchemaConfigurationExecuterRegistryFacade
{
    public static function getInstance(): EndpointSchemaConfigurationExecuterRegistryInterface
    {
        /**
         * @var EndpointSchemaConfigurationExecuterRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(EndpointSchemaConfigurationExecuterRegistryInterface::class);
        return $service;
    }
}
