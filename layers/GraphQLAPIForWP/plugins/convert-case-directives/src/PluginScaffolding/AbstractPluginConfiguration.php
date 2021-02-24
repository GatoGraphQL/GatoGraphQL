<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives\PluginScaffolding;

use PoP\ComponentModel\Misc\GeneralUtils;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;

abstract class AbstractPluginConfiguration
{
    /**
     * Provide the classes of the components whose
     * schema initialization must be skipped
     *
     * @return array
     */
    public static function getSkippingSchemaComponentClasses(): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();

        // Component classes are skipped if the module is disabled
        $skipSchemaModuleComponentClasses = array_filter(
            static::getModuleComponentClasses(),
            function ($module) use ($moduleRegistry) {
                return !$moduleRegistry->isModuleEnabled($module);
            },
            ARRAY_FILTER_USE_KEY
        );
        return GeneralUtils::arrayFlatten(
            array_values(
                $skipSchemaModuleComponentClasses
            )
        );
    }

    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what component classes must skip initialization
     *
     * @return array
     */
    abstract protected static function getModuleComponentClasses(): array;
}
