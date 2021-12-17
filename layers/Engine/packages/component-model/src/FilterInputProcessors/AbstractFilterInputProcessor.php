<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\BasicService\BasicServiceTrait;

abstract class AbstractFilterInputProcessor implements FilterInputProcessorInterface
{
    use BasicServiceTrait;

    public function getFilterInputsToProcess(): array
    {
        return [];
    }
}
