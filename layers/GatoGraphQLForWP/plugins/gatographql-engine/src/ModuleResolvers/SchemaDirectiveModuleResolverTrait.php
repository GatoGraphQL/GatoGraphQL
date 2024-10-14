<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolver;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;

trait SchemaDirectiveModuleResolverTrait
{
    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 80;
    }

    /**
     * The type of the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::SCHEMA_DIRECTIVE;
    }

    public function getSettingsCategory(string $module): string
    {
        return SettingsCategoryResolver::SCHEMA_TYPE_CONFIGURATION;
    }
}
