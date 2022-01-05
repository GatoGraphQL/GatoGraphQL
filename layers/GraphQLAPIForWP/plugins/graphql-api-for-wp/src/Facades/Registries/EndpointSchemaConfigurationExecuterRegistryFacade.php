<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EndpointSchemaConfigurationExecuterRegistryFacade
{
    public static function getInstance(): EndpointSchemaConfigurationExecuterRegistryInterface
    {
        /**
         * @var EndpointSchemaConfigurationExecuterRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(EndpointSchemaConfigurationExecuterRegistryInterface::class);
        return $service;
    }
}
