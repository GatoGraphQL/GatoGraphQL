<?php

declare(strict_types=1);

namespace PoP\ComponentModel\CheckpointProcessors;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;

interface CheckpointProcessorInterface
{
    /**
     * @return array<array>
     */
    public function getCheckpointsToProcess(): array;

    /**
     * @return FeedbackItemResolution|null `null` if successful, or FeedbackItemResolution with a descriptive error message and code otherwise
     */
    public function validateCheckpoint(array $checkpoint): ?FeedbackItemResolution;
}
