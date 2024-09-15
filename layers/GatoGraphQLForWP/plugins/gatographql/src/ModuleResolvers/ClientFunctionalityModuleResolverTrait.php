<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolver;

trait ClientFunctionalityModuleResolverTrait
{
    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 182;
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::CLIENT;
    }
}
