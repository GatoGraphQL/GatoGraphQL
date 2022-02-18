<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Checkpoint\CheckpointError;

interface CheckpointProcessorInterface
{
    /**
     * @return array<array>
     */
    public function getCheckpointsToProcess(): array;

    /**
     * @return CheckpointError|null `null` if successful, or CheckpointError with a descriptive error message and code otherwise
     */
    public function validateCheckpoint(array $checkpoint): ?FeedbackItemResolution;
}
