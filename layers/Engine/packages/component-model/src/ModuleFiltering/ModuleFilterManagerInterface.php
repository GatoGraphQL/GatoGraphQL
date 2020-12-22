<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleFiltering;

use PoP\ComponentModel\ModuleFilters\ModuleFilterInterface;

interface ModuleFilterManagerInterface
{
    public function getSelectedModuleFilterName(): ?string;
    public function setSelectedModuleFilterName(string $selectedModuleFilterName);
    public function getNotExcludedModuleSets();
    public function add(ModuleFilterInterface ...$moduleFilters);
    public function neverExclude($neverExclude);
    public function excludeModule(array $module, array &$props);
    public function removeExcludedSubmodules(array $module, $submodules);
    /**
     * The `prepare` function advances the modulepath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $module, array &$props);
    public function restoreFromPropagation(array $module, array &$props);
}
