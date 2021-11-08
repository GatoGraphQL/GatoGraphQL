<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\Services\BasicServiceTrait;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    use BasicServiceTrait;

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
