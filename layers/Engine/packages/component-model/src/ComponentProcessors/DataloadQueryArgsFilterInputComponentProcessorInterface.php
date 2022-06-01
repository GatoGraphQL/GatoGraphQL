<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;

interface DataloadQueryArgsFilterInputComponentProcessorInterface extends FilterInputComponentProcessorInterface
{
    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface;
}
