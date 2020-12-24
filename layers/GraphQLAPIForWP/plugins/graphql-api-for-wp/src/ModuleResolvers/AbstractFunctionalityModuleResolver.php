<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolver;

abstract class AbstractFunctionalityModuleResolver extends AbstractModuleResolver
{
    /**
     * The type of the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::FUNCTIONALITY;
    }
}
