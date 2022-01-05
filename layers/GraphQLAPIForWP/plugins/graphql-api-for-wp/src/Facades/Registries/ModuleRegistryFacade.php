<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Obtain an instance of the ModuleRegistry.
 */
class ModuleRegistryFacade
{
    public static function getInstance(): ModuleRegistryInterface
    {
        /**
         * @var ModuleRegistryInterface
         */
        return \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(ModuleRegistryInterface::class);
    }
}
