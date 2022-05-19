<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

interface ComponentFilterInterface
{
    public function getName(): string;
    public function excludeSubcomponent(array $component, array &$props): bool;
    public function removeExcludedSubcomponents(array $component, array $subComponents): array;
    public function prepareForPropagation(array $component, array &$props): void;
    public function restoreFromPropagation(array $component, array &$props): void;
}
