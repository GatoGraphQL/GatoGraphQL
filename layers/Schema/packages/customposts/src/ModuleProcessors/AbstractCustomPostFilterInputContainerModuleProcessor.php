<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\AbstractFilterDataModuleProcessor;

abstract class AbstractCustomPostFilterInputContainerModuleProcessor extends AbstractFilterDataModuleProcessor
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

    abstract public function getFilterInputModules(array $module): array;

    /**
     * @return string[]
     */
    public function getFilterInputHookNames(): array
    {
        return [
            self::HOOK_FILTER_INPUTS,
        ];
    }

    public function getClassFilterInputHookName(): string
    {
        return static::HOOK_FILTER_INPUTS;
    }
}
