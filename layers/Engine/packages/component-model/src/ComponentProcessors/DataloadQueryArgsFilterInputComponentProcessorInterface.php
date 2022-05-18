<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface DataloadQueryArgsFilterInputComponentProcessorInterface extends FilterInputComponentProcessorInterface
{
    public function getFilterInput(array $componentVariation): ?array;
}
