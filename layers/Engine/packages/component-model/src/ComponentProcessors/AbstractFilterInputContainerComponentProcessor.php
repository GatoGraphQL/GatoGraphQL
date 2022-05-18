<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;

abstract class AbstractFilterInputContainerComponentProcessor extends AbstractFilterDataComponentProcessor implements FilterInputContainerComponentProcessorInterface
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    final public function getSubmodules(array $componentVariation): array
    {
        $filterInputModules = $this->getFilterInputComponentVariations($componentVariation);

        // Enable extensions to add more FilterInputs
        foreach ($this->getFilterInputHookNames() as $filterInputHookName) {
            $filterInputModules = App::applyFilters(
                $filterInputHookName,
                $filterInputModules,
                $componentVariation
            );
        }

        // Add the filterInputs to whatever came from the parent (if anything)
        return array_merge(
            parent::getSubmodules($componentVariation),
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

    public function getFieldFilterInputNameTypeResolvers(array $componentVariation): array
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponentVariations($componentVariation);
        $schemaFieldArgNameTypeResolvers = [];
        foreach ($filterQueryArgsModules as $componentVariation) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($componentVariation);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($componentVariation);
            $schemaFieldArgNameTypeResolvers[$filterInputName] = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeResolver($componentVariation);
        }
        return $schemaFieldArgNameTypeResolvers;
    }

    public function getFieldFilterInputDescription(array $componentVariation, string $fieldArgName): ?string
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponentVariations($componentVariation);
        foreach ($filterQueryArgsModules as $componentVariation) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($componentVariation);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($componentVariation);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDescription($componentVariation);
            }
        }
        return null;
    }

    public function getFieldFilterInputDefaultValue(array $componentVariation, string $fieldArgName): mixed
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponentVariations($componentVariation);
        foreach ($filterQueryArgsModules as $componentVariation) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($componentVariation);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($componentVariation);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDefaultValue($componentVariation);
            }
        }
        return null;
    }

    public function getFieldFilterInputTypeModifiers(array $componentVariation, string $fieldArgName): int
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponentVariations($componentVariation);
        foreach ($filterQueryArgsModules as $componentVariation) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getProcessor($componentVariation);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($componentVariation);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeModifiers($componentVariation);
            }
        }
        return SchemaTypeModifiers::NONE;
    }
}
