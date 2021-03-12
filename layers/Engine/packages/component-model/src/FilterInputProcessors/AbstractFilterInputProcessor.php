<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
