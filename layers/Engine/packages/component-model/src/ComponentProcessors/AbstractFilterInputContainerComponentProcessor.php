<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;

abstract class AbstractFilterInputContainerComponentProcessor extends AbstractFilterDataComponentProcessor implements FilterInputContainerComponentProcessorInterface
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    final public function getSubmodules(array $module): array
    {
        $filterInputModules = $this->getFilterInputModules($module);

        // Enable extensions to add more FilterInputs
        foreach ($this->getFilterInputHookNames() as $filterInputHookName) {
            $filterInputModules = App::applyFilters(
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

    public function getFieldFilterInputNameTypeResolvers(array $module): array
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
        $schemaFieldArgNameTypeResolvers = [];
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($module);
            $schemaFieldArgNameTypeResolvers[$filterInputName] = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeResolver($module);
        }
        return $schemaFieldArgNameTypeResolvers;
    }

    public function getFieldFilterInputDescription(array $module, string $fieldArgName): ?string
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($module);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDescription($module);
            }
        }
        return null;
    }

    public function getFieldFilterInputDefaultValue(array $module, string $fieldArgName): mixed
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($module);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDefaultValue($module);
            }
        }
        return null;
    }

    public function getFieldFilterInputTypeModifiers(array $module, string $fieldArgName): int
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringModules($module);
        foreach ($filterQueryArgsModules as $module) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($module);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($module);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeModifiers($module);
            }
        }
        return SchemaTypeModifiers::NONE;
    }
}
