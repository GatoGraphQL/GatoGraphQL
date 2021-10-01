<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use Symfony\Contracts\Service\Attribute\Required;

trait QueryableFieldResolverTrait
{
    protected ModuleProcessorManagerInterface $moduleProcessorManager;

    #[Required]
    public function autowireQueryableFieldResolverTrait(
        ModuleProcessorManagerInterface $moduleProcessorManager,
    ): void {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }

    protected function getFilterSchemaFieldArgNameResolvers(array $filterDataloadingModule): array
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        $schemaFieldArgNameResolvers = [];
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            $schemaFieldArgNameResolvers[$filterInputName] = $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputTypeResolver($module);
        }
        return $schemaFieldArgNameResolvers;
    }

    protected function getFilterSchemaFieldArgDescription(array $filterDataloadingModule, string $fieldArgName): ?string
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            if ($filterInputName !== $fieldArgName) {
                continue;
            }
            return $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputDescription($module);
        }
        return null;
    }

    protected function getFilterSchemaFieldArgDefaultValue(array $filterDataloadingModule, string $fieldArgName): mixed
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            if ($filterInputName !== $fieldArgName) {
                continue;
            }
            return $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputDefaultValue($module);
        }
        return null;
    }

    protected function getFilterSchemaFieldArgTypeModifiers(array $filterDataloadingModule, string $fieldArgName): ?int
    {
        /** @var FilterInputContainerModuleProcessorInterface */
        $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
        $filterQueryArgsModules = $filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            if ($filterInputName !== $fieldArgName) {
                continue;
            }
            return $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputTypeModifiers($module);
        }
        return null;
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
