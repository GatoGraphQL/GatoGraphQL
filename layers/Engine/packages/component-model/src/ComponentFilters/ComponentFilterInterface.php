<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

interface ComponentFilterInterface
{
    public function getName(): string;
    public function excludeModule(array $module, array &$props): bool;
    public function removeExcludedSubmodules(array $module, array $submodules): array;
    public function prepareForPropagation(array $module, array &$props): void;
    public function restoreFromPropagation(array $module, array &$props): void;
}
