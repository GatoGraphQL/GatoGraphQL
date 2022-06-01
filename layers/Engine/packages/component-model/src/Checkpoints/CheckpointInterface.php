<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

use PoP\Root\Feedback\FeedbackItemResolution;

interface CheckpointInterface
{
    /**
     * @return FeedbackItemResolution|null `null` if successful, or FeedbackItemResolution with a descriptive error message and code otherwise
     */
    public function validateCheckpoint(): ?FeedbackItemResolution;
}
