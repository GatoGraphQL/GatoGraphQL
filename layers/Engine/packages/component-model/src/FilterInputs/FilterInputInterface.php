<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputs;

interface FilterInputInterface
{
    public function filterDataloadQueryArgs(array &$query, mixed $value): void;
}
