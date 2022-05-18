<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FilterDataComponentProcessorInterface
{
    public function getDataloadQueryArgsFilteringModules(array $componentVariation): array;
    public function getActiveDataloadQueryArgsFilteringModules(array $componentVariation, array $source = null): array;
    public function filterHeadmoduleDataloadQueryArgs(array $componentVariation, array &$query, array $source = null): void;
}
