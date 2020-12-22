<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleFilters;

abstract class AbstractModuleFilter implements ModuleFilterInterface
{
    public function excludeModule(array $module, array &$props)
    {
        return false;
    }

    public function removeExcludedSubmodules(array $module, $submodules)
    {
        return $submodules;
    }

    public function prepareForPropagation(array $module, array &$props)
    {
    }

    public function restoreFromPropagation(array $module, array &$props)
    {
    }
}
