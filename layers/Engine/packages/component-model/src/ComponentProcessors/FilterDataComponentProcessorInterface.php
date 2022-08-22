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
     * @param array<string,mixed>|null $source
     */
    public function getActiveDataloadQueryArgsFilteringComponents(Component $component, ?array $source = null): array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed>|null $source
     */
    public function filterHeadcomponentDataloadQueryArgs(Component $component, array &$query, ?array $source = null): void;
}
