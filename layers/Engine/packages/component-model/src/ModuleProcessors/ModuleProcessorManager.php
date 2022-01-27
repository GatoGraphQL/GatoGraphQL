<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
use PoP\Root\Services\BasicServiceTrait;

class ModuleProcessorManager implements ModuleProcessorManagerInterface
{
    use ItemProcessorManagerTrait;
    use BasicServiceTrait;

    public function getProcessor(array $item): ModuleProcessorInterface
    {
        return $this->getItemProcessor($item);
    }
}
