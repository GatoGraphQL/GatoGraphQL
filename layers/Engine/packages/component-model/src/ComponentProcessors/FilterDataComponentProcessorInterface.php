<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FilterDataComponentProcessorInterface
{
    public function getDataloadQueryArgsFilteringComponents(\PoP\ComponentModel\Component\Component $component): array;
    public function getActiveDataloadQueryArgsFilteringComponents(\PoP\ComponentModel\Component\Component $component, array $source = null): array;
    public function filterHeadcomponentDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$query, array $source = null): void;
}
