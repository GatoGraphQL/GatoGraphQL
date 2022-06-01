<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\Component\Component;

interface ComponentFilterInterface
{
    public function getName(): string;
    public function excludeSubcomponent(\PoP\ComponentModel\Component\Component $component, array &$props): bool;
    /**
     * @param Component[] $subcomponents
     * @return Component[]
     */
    public function removeExcludedSubcomponents(\PoP\ComponentModel\Component\Component $component, array $subcomponents): array;
    public function prepareForPropagation(\PoP\ComponentModel\Component\Component $component, array &$props): void;
    public function restoreFromPropagation(\PoP\ComponentModel\Component\Component $component, array &$props): void;
}
