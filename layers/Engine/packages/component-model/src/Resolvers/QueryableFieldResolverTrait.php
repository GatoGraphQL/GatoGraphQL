<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Facades\FilterInputProcessors\FilterInputProcessorManagerFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FilterDataModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;

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

    /**
     * In the FilterInputModule we do not define default values, since different fields
     * using the same FilterInput may need a different default value.
     * Then, allow to override these values now.
     *
     * @param array<string,mixed> $filterInputNameDefaultValues A list of filterInputName as key, and its value
     * @param string[] $filterInputNameMandatoryArgs
     */
    protected function getSchemaFieldArgsWithCustomFilterInputData(
        array $schemaFieldArgs,
        array $filterInputNameDefaultValues,
        array $filterInputNameMandatoryArgs,
    ): array {
        foreach ($schemaFieldArgs as &$schemaFieldArg) {
            foreach ($filterInputNameDefaultValues as $filterInputName => $defaultValue) {
                if ($schemaFieldArg[SchemaDefinition::ARGNAME_NAME] !== $filterInputName) {
                    continue;
                }
                $schemaFieldArg[SchemaDefinition::ARGNAME_DEFAULT_VALUE] = $defaultValue;
                break;
            }
            if (in_array(
                $schemaFieldArg[SchemaDefinition::ARGNAME_NAME],
                $filterInputNameMandatoryArgs
            )) {
                $schemaFieldArg[SchemaDefinition::ARGNAME_MANDATORY] = true;
            }
        }
        return $schemaFieldArgs;
    }
}
