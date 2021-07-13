<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;

trait ClientFunctionalityModuleResolverTrait
{
    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 120;
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::CLIENT;
    }
}
