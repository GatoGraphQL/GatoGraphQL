<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;

trait QueryableFieldResolverTrait
{
    protected function getFilterSchemaDefinitionItems(array $filterDataloadingModule): array
    {
        $moduleProcessorManager = ModuleProcessorManagerFacade::getInstance();
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        $schemaFieldArgs = GeneralUtils::arrayFlatten(
            array_map(
                function (array $module) use ($moduleProcessorManager) {
                    /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
                    $dataloadQueryArgsFilterInputModuleProcessor = $moduleProcessorManager->getProcessor($module);
                    return $dataloadQueryArgsFilterInputModuleProcessor->getFilterInputSchemaDefinitionItems($module);
                },
                $filterQueryArgsModules
            )
        );
        return $this->getSchemaFieldArgsWithCustomFilterInputData(
            $schemaFieldArgs,
            $filterDataModuleProcessor->getFieldDataFilteringDefaultValues($filterDataloadingModule),
            $filterDataModuleProcessor->getFieldDataFilteringMandatoryArgs($filterDataloadingModule)
        );
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
            if (
                in_array(
                    $schemaFieldArg[SchemaDefinition::ARGNAME_NAME],
                    $filterInputNameMandatoryArgs
                )
            ) {
                $schemaFieldArg[SchemaDefinition::ARGNAME_MANDATORY] = true;
            }
        }
        return $schemaFieldArgs;
    }
}
