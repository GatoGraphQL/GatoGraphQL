<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;

abstract class AbstractFilterInputContainerComponentProcessor extends AbstractFilterDataComponentProcessor implements FilterInputContainerComponentProcessorInterface
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    final public function getSubComponents(array $component): array
    {
        $filterInputModules = $this->getFilterInputComponents($component);

        // Enable extensions to add more FilterInputs
        foreach ($this->getFilterInputHookNames() as $filterInputHookName) {
            $filterInputModules = App::applyFilters(
                $filterInputHookName,
                $filterInputModules,
                $component
            );
        }

        // Add the filterInputs to whatever came from the parent (if anything)
        return array_merge(
            parent::getSubComponents($component),
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

    public function getFieldFilterInputNameTypeResolvers(array $component): array
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        $schemaFieldArgNameTypeResolvers = [];
        foreach ($filterQueryArgsModules as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            $schemaFieldArgNameTypeResolvers[$filterInputName] = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeResolver($component);
        }
        return $schemaFieldArgNameTypeResolvers;
    }

    public function getFieldFilterInputDescription(array $component, string $fieldArgName): ?string
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsModules as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDescription($component);
            }
        }
        return null;
    }

    public function getFieldFilterInputDefaultValue(array $component, string $fieldArgName): mixed
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsModules as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDefaultValue($component);
            }
        }
        return null;
    }

    public function getFieldFilterInputTypeModifiers(array $component, string $fieldArgName): int
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsModules as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeModifiers($component);
            }
        }
        return SchemaTypeModifiers::NONE;
    }
}
