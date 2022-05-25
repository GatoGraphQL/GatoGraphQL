<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

interface FilterInputProcessorInterface
{
    public function filterDataloadQueryArgs(array &$query, mixed $value): void;
}
