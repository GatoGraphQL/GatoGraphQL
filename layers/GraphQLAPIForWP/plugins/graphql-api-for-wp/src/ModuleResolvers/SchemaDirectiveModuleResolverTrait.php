<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;

trait SchemaDirectiveModuleResolverTrait
{
    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 90;
    }

    /**
     * The type of the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::SCHEMA_DIRECTIVE;
    }
}
