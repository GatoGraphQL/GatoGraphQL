<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;

class EndpointSchemaConfigurationExecuterRegistryFacade
{
    public static function getInstance(): EndpointSchemaConfigurationExecuterRegistryInterface
    {
        /**
         * @var EndpointSchemaConfigurationExecuterRegistryInterface
         */
        $service = App::getContainer()->get(EndpointSchemaConfigurationExecuterRegistryInterface::class);
        return $service;
    }
}
