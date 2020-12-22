<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

trait QueryableFieldResolverTrait
{
    protected function getFilterSchemaDefinitionItems(array $filterDataloadingModule): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $filterqueryargs_modules = $moduleprocessor_manager->getProcessor((array)$filterDataloadingModule)->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        return GeneralUtils::arrayFlatten(
            array_map(
                function ($module) use ($moduleprocessor_manager) {
                    return $moduleprocessor_manager->getProcessor($module)->getFilterInputSchemaDefinitionItems($module);
                },
                $filterqueryargs_modules
            )
        );
    }
}
