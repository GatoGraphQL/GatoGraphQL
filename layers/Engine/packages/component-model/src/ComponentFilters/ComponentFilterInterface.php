<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

interface ComponentFilterInterface
{
    public function getName(): string;
    public function excludeModule(array $componentVariation, array &$props): bool;
    public function removeExcludedSubmodules(array $componentVariation, array $submodules): array;
    public function prepareForPropagation(array $componentVariation, array &$props): void;
    public function restoreFromPropagation(array $componentVariation, array &$props): void;
}
