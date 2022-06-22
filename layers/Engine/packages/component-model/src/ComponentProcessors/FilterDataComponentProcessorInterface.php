<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;

interface FilterDataComponentProcessorInterface
{
    /**
     * @return Component[]
     */
    public function getDataloadQueryArgsFilteringComponents(Component $component): array;
    /**
     * @return Component[]
     */
    public function getActiveDataloadQueryArgsFilteringComponents(Component $component, array $source = null): array;
    public function filterHeadcomponentDataloadQueryArgs(Component $component, array &$query, array $source = null): void;
}
