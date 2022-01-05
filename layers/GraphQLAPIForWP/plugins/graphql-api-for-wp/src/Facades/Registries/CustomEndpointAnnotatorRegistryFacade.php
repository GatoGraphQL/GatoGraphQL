<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointAnnotatorRegistryInterface;

class CustomEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): CustomEndpointAnnotatorRegistryInterface
    {
        /**
         * @var CustomEndpointAnnotatorRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CustomEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
