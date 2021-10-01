<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

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
    
    public function getSchemaFieldArgNameResolvers(array $module): array
    {
        return [];
    }
    
    public function getSchemaFieldArgDescription(array $module, string $fieldArgName): ?string
    {
        return null;
    }
    
    public function getSchemaFieldArgDefaultValue(array $module, string $fieldArgName): mixed
    {
        return null;
    }
    
    public function getSchemaFieldArgTypeModifiers(array $module, string $fieldArgName): int
    {
        return 0;
    }

    /**
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    public function getFieldFilterInputMandatoryArgs(array $module): array
    {
        return [];
    }
}
