<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;

trait QueryableFieldResolverTrait
{
    protected function getFilterSchemaDefinitionItems(array $filterDataloadingModule): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $filterqueryargs_modules = $moduleprocessor_manager->getProcessor($filterDataloadingModule)->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        return GeneralUtils::arrayFlatten(
            array_map(
                function (array $module) use ($moduleprocessor_manager) {
                    /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
                    $dataloadQueryArgsFilterInputModuleProcessor = $moduleprocessor_manager->getProcessor($module);
                    return $dataloadQueryArgsFilterInputModuleProcessor->getFilterInputSchemaDefinitionItems($module);
                },
                $filterqueryargs_modules
            )
        );
    }
}
