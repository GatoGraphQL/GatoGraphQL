<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;

class FilterInputProcessorManager implements FilterInputProcessorManagerInterface
{
    use ItemProcessorManagerTrait;

    public function getProcessor(array $item): FilterInputProcessorInterface
    {
        return $this->getItemProcessor($item);
    }
}
