<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryEndpointAnnotatorRegistryFacade
{
    public static function getInstance(): PersistedQueryEndpointAnnotatorRegistryInterface
    {
        /**
         * @var PersistedQueryEndpointAnnotatorRegistryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PersistedQueryEndpointAnnotatorRegistryInterface::class);
        return $service;
    }
}
