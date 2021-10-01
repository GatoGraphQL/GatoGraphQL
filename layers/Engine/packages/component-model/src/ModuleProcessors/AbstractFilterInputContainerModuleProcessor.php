<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;

abstract class AbstractFilterInputContainerModuleProcessor extends AbstractFilterDataModuleProcessor implements FilterInputContainerModuleProcessorInterface
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    final public function getSubmodules(array $module): array
    {
        $filterInputModules = $this->getFilterInputModules($module);

        // Enable extensions to add more FilterInputs
        foreach ($this->getFilterInputHookNames() as $filterInputHookName) {
            $filterInputModules = $this->hooksAPI->applyFilters(
                $filterInputHookName,
                $filterInputModules,
                $module
            );
        }

        // Add the filterInputs to whatever came from the parent (if anything)
        return array_merge(
            parent::getSubmodules($module),
            $filterInputModules
        );
    }

    /**
     * @return string[]
     */
    protected function getFilterInputHookNames(): array
    {
        return [
            self::HOOK_FILTER_INPUTS,
        ];
    }
    
    public function getFieldFilterInputNameResolvers(array $module): array
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
        $schemaFieldArgNameResolvers = [];
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            $schemaFieldArgNameResolvers[$filterInputName] = $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputTypeResolver($module);
        }
        return $schemaFieldArgNameResolvers;
    }
    
    public function getFieldFilterInputDescription(array $module, string $fieldArgName): ?string
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
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
    
    public function getFieldFilterInputDefaultValue(array $module, string $fieldArgName): mixed
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
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
    
    public function getFieldFilterInputTypeModifiers(array $module, string $fieldArgName): int
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputModuleProcessorInterface */
            $dataloadQueryArgsFilterInputModuleProcessor = $this->moduleProcessorManager->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputModuleProcessor->getName($module);
            if ($filterInputName !== $fieldArgName) {
                continue;
            }
            return $dataloadQueryArgsFilterInputModuleProcessor->getSchemaFilterInputTypeModifiers($module);
        }
        return 0;
    }
}
