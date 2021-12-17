<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
use PoP\BasicService\BasicServiceTrait;

class CheckpointProcessorManager implements CheckpointProcessorManagerInterface
{
    use ItemProcessorManagerTrait;
    use BasicServiceTrait;

    public function getProcessor(array $item): CheckpointProcessorInterface
    {
        return $this->getItemProcessor($item);
    }
}
