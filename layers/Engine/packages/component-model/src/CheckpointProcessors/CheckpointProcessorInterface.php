<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\ErrorHandling\Error;

interface CheckpointProcessorInterface
{
    /**
     * @return array<array>
     */
    public function getCheckpointsToProcess(): array;

    /**
     * @return boolean `true` if successful, `false` otherwise
     */
    public function validateCheckpoint(array $checkpoint): ?Error;
}
