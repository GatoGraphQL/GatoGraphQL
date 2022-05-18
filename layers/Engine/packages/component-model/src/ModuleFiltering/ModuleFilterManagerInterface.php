<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleFiltering;

use PoP\ComponentModel\ModuleFilters\ModuleFilterInterface;

interface ModuleFilterManagerInterface
{
    public function addModuleFilter(ModuleFilterInterface $moduleFilter): void;
    public function getSelectedModuleFilterName(): ?string;
    public function setSelectedModuleFilterName(string $selectedModuleFilterName): void;
    public function getNotExcludedComponentVariationSets(): ?array;
    public function neverExclude($neverExclude): void;
    public function excludeModule(array $module, array &$props): bool;
    public function removeExcludedSubmodules(array $module, array $submodules): array;
    /**
     * The `prepare` function advances the modulepath one level down, when interating into the submodules, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $module, array &$props): void;
    public function restoreFromPropagation(array $module, array &$props): void;
}
