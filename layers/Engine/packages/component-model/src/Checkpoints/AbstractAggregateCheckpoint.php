<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Checkpoints;

use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractAggregateCheckpoint extends AbstractCheckpoint
{
    final public function validateCheckpoint(): ?FeedbackItemResolution
    {
        /**
         * Iterate through the list of all checkpoints, process all of them,
         * if any produces an error, already return it
         */
        foreach ($this->getCheckpoints() as $checkpoint) {
            $feedbackItemResolution = $checkpoint->validateCheckpoint();
            if ($feedbackItemResolution !== null) {
                return $feedbackItemResolution;
            }
        }

        return null;
    }

    /**
     * @return CheckpointInterface[]
     */
    abstract protected function getCheckpoints(): array;
}
