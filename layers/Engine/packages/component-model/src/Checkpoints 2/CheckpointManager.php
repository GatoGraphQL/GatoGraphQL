<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
use PoP\Root\Services\BasicServiceTrait;

class CheckpointManager implements CheckpointManagerInterface
{
    use ItemProcessorManagerTrait;
    use BasicServiceTrait;

    public function getProcessor(array $item): CheckpointInterface
    {
        return $this->getItemProcessor($item);
    }
}
