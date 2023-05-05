<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolver;

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
