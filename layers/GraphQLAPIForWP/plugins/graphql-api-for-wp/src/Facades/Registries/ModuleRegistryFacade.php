<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\SystemServices\Registries\ModuleRegistryInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;

/**
 * Obtain an instance of the ModuleRegistry.
 * Use the System Container because it is required for
 * setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class ModuleRegistryFacade
{
    public static function getInstance(): ModuleRegistryInterface
    {
        /**
         * @var ModuleRegistryInterface
         */
        return SystemContainerBuilderFactory::getInstance()->get(ModuleRegistryInterface::class);
    }
}
