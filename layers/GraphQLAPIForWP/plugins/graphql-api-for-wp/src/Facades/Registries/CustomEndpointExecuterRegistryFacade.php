<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointExecuterRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CustomEndpointExecuterRegistryFacade
{
    public static function getInstance(): CustomEndpointExecuterRegistryInterface
    {
        /**
         * @var CustomEndpointExecuterRegistryInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CustomEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
