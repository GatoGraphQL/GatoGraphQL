<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFiltering;

use PoP\ComponentModel\ComponentFilters\ComponentFilterInterface;

interface ComponentFilterManagerInterface
{
    public function addComponentFilter(ComponentFilterInterface $componentFilter): void;
    public function getSelectedComponentFilterName(): ?string;
    public function setSelectedComponentFilterName(string $selectedComponentFilterName): void;
    public function getNotExcludedComponentSets(): ?array;
    public function setNeverExclude(bool $neverExclude): void;
    public function excludeSubcomponent(array $component, array &$props): bool;
    public function removeExcludedSubcomponents(array $component, array $subComponents): array;
    /**
     * The `prepare` function advances the componentPath one level down, when interating into the subcomponents, and then calling `restore` the value goes one level up again
     */
    public function prepareForPropagation(array $component, array &$props): void;
    public function restoreFromPropagation(array $component, array &$props): void;
}
