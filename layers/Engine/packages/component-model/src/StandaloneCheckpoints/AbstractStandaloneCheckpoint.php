<?php

declare(strict_types=1);

namespace PoP\ComponentModel\StandaloneCheckpoints;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

abstract class AbstractStandaloneCheckpoint implements CheckpointInterface
{
    use StandaloneServiceTrait;

    /**
     * By default there's no problem
     */
    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        return null;
    }
}
