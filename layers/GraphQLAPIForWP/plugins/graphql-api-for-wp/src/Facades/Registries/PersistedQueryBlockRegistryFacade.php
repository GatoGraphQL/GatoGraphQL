<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Root\Container\ContainerBuilderFactory;
use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryBlockRegistryInterface;

class PersistedQueryBlockRegistryFacade
{
    public static function getInstance(): PersistedQueryBlockRegistryInterface
    {
        /**
         * @var PersistedQueryBlockRegistryInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PersistedQueryBlockRegistryInterface::class);
        return $service;
    }
}
