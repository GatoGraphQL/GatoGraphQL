<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractCheckpointProcessor implements CheckpointProcessorInterface
{
    use BasicServiceTrait;

    /**
     * By default there's no problem
     */
    public function validateCheckpoint(array $checkpoint): ?CheckpointError
    {
        return null;
    }
}
