<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointExecuterRegistryInterface;

class CustomEndpointExecuterRegistryFacade
{
    public static function getInstance(): CustomEndpointExecuterRegistryInterface
    {
        /**
         * @var CustomEndpointExecuterRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CustomEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
