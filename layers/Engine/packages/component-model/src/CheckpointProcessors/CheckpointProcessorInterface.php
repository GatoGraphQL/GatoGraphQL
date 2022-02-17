<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\Error\Error;

interface CheckpointProcessorInterface
{
    /**
     * @return array<array>
     */
    public function getCheckpointsToProcess(): array;

    /**
     * @return Error|null `null` if successful, or Error with a descriptive error message otherwise
     */
    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Checkpoint\CheckpointError;
}
