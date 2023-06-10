<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractCheckpoint implements CheckpointInterface
{
    use BasicServiceTrait;

    /**
     * By default there's no problem
     */
    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        return null;
    }
}
