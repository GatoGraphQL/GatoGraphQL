<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FilterDataModuleProcessorInterface;
use PoP\ComponentModel\Facades\FilterInputProcessors\FilterInputProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;

trait QueryableFieldResolverTrait
{
    protected function getFilterSchemaDefinitionItems(array $filterDataloadingModule): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        /** @var FilterDataModuleProcessorInterface */
        $filterDataModuleProcessor = $moduleprocessor_manager->getProcessor($filterDataloadingModule);
        $filterqueryargs_modules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
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

    protected function getFilterInputName(array $filterInput): string
    {
        $filterInputProcessorManager = FilterInputProcessorManagerFacade::getInstance();
        /** @var FormComponentModuleProcessorInterface */
        $filterInputProcessor = $filterInputProcessorManager->getProcessor($filterInput);
        return $filterInputProcessor->getName($filterInput);
    }
}
