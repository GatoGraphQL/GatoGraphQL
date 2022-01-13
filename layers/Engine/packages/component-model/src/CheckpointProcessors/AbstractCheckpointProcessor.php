<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\Error\Error;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractCheckpointProcessor implements CheckpointProcessorInterface
{
    use BasicServiceTrait;

    /**
     * By default there's no problem
     */
    public function validateCheckpoint(array $checkpoint): ?Error
    {
        return null;
    }
}
