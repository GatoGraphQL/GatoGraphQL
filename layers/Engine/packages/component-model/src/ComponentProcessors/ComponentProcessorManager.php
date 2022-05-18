<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
use PoP\Root\Services\BasicServiceTrait;

class ComponentProcessorManager implements ComponentProcessorManagerInterface
{
    use ItemProcessorManagerTrait;
    use BasicServiceTrait;

    public function getProcessor(array $item): ComponentProcessorInterface
    {
        return $this->getItemProcessor($item);
    }
}
