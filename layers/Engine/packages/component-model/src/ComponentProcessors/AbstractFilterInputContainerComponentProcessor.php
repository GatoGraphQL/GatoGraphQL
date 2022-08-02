<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;

abstract class AbstractFilterInputContainerComponentProcessor extends AbstractFilterDataComponentProcessor implements FilterInputContainerComponentProcessorInterface
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    /**
     * @return Component[]
     */
    final public function getSubcomponents(Component $component): array
    {
        $filterInputComponents = $this->getFilterInputComponents($component);

        // Enable extensions to add more FilterInputs
        foreach ($this->getFilterInputHookNames() as $filterInputHookName) {
            $filterInputComponents = App::applyFilters(
                $filterInputHookName,
                $filterInputComponents,
                $component
            );
        }

        // Add the filterInputs to whatever came from the parent (if anything)
        return array_merge(
            parent::getSubcomponents($component),
            $filterInputComponents
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

    public function getFieldFilterInputNameTypeResolvers(Component $component): array
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        $schemaFieldArgNameTypeResolvers = [];
        foreach ($filterQueryArgsModules as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            $schemaFieldArgNameTypeResolvers[$filterInputName] = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeResolver($component);
        }
        return $schemaFieldArgNameTypeResolvers;
    }

    public function getFieldFilterInputDescription(Component $component, string $fieldArgName): ?string
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsModules as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDescription($component);
            }
        }
        return null;
    }

    public function getFieldFilterInputDefaultValue(Component $component, string $fieldArgName): mixed
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsModules as $component) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($component);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($component);
            if ($filterInputName === $fieldArgName) {
                return $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputDefaultValue($component);
            }
        }
        return null;
    }

    public function getFieldFilterInputTypeModifiers(Component $component, string $fieldArgName): int
    {
        $filterQueryArgsModules = $this->getDataloadQueryArgsFilteringComponents($component);
        foreach ($filterQueryArgsModules as $filterInput) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $dataloadQueryArgsFilterInputComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterInput);
            $filterInputName = $dataloadQueryArgsFilterInputComponentProcessor->getName($filterInput);
            if ($filterInputName === $fieldArgName) {
                $fieldFilterInputTypeModifiers = $dataloadQueryArgsFilterInputComponentProcessor->getFilterInputTypeModifiers($component);
                if ($this->makeFieldFilterInputMandatoryIfHasDefaultValue($component, $fieldArgName)
                    && null !== $this->getFieldFilterInputDefaultValue($component, $fieldArgName)
                ) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                return $fieldFilterInputTypeModifiers;
            }
        }
        return SchemaTypeModifiers::NONE;
    }

    /**
     * Is the input that has a default value also mandatory?
     *
     * This helps avoid errors from expecting type `string` in
     * some PHP function and receiving `null`.
     *
     * Eg: { posts { dateStr(format: null) } }
     */
    protected function makeFieldFilterInputMandatoryIfHasDefaultValue(Component $component, string $fieldArgName): bool
    {
        return true;
    }
}
