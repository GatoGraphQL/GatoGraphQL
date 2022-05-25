<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;

interface DataloadQueryArgsFilterInputComponentProcessorInterface extends FilterInputComponentProcessorInterface
{
    public function getFilterInput(array $component): ?FilterInputProcessorInterface;
}
