<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FilterDataComponentProcessorInterface
{
    public function getDataloadQueryArgsFilteringComponents(array $component): array;
    public function getActiveDataloadQueryArgsFilteringComponents(array $component, array $source = null): array;
    public function filterHeadcomponentDataloadQueryArgs(array $component, array &$query, array $source = null): void;
}
