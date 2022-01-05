<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades\Registries;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Registries\ModuleTypeRegistryInterface;

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
        return App::getContainer()->get(ModuleTypeRegistryInterface::class);
    }
}
