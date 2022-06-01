<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\Component\Component;

interface ComponentFilterInterface
{
    public function getName(): string;
    public function excludeSubcomponent(Component $component, array &$props): bool;
    /**
     * @param Component[] $subcomponents
     * @return Component[]
     */
    public function removeExcludedSubcomponents(Component $component, array $subcomponents): array;
    public function prepareForPropagation(Component $component, array &$props): void;
    public function restoreFromPropagation(Component $component, array &$props): void;
}
