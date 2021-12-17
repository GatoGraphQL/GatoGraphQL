<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputProcessors;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
use PoP\BasicService\BasicServiceTrait;

class FilterInputProcessorManager implements FilterInputProcessorManagerInterface
{
    use ItemProcessorManagerTrait;
    use BasicServiceTrait;

    public function getProcessor(array $item): FilterInputProcessorInterface
    {
        return $this->getItemProcessor($item);
    }
}
