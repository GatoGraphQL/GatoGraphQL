<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleFilters;

interface ModuleFilterInterface
{
    public function getName();
    public function excludeModule(array $module, array &$props);
    public function removeExcludedSubmodules(array $module, $submodules);
    public function prepareForPropagation(array $module, array &$props);
    public function restoreFromPropagation(array $module, array &$props);
}
