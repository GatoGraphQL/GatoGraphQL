<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FilterInputs;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
use PoP\Root\Services\BasicServiceTrait;

class FilterInputManager implements FilterInputManagerInterface
{
    use ItemProcessorManagerTrait;
    use BasicServiceTrait;

    public function getProcessor(array $item): FilterInputInterface
    {
        return $this->getItemProcessor($item);
    }
}
