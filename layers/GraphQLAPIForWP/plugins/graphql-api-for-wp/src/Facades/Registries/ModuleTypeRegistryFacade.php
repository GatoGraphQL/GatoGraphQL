<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use GraphQLAPI\GraphQLAPI\Registries\ModuleTypeRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Obtain an instance of the ModuleTypeRegistry
 */
class ModuleTypeRegistryFacade
{
    public static function getInstance(): ModuleTypeRegistryInterface
    {
        /**
         * @var ModuleTypeRegistryInterface
         */
        return \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(ModuleTypeRegistryInterface::class);
    }
}
