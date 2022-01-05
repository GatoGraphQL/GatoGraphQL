<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointExecuterRegistryInterface;

class CustomEndpointExecuterRegistryFacade
{
    public static function getInstance(): CustomEndpointExecuterRegistryInterface
    {
        /**
         * @var CustomEndpointExecuterRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CustomEndpointExecuterRegistryInterface::class);
        return $service;
    }
}
