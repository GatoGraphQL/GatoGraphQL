<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;

class ModuleProcessorManager implements ModuleProcessorManagerInterface
{
    use ItemProcessorManagerTrait;

    public function getProcessor(array $item): ModuleProcessorInterface
    {
        return $this->getItemProcessor($item);
    }
}
