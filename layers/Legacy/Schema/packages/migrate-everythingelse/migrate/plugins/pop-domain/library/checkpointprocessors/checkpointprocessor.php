<?php
use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class PoP_Domain_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_DOMAINVALID = 'checkpoint-domainvalid';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_DOMAINVALID],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Feedback\FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_DOMAINVALID:
                // Check if the domain passed in param 'domain' is allowed
                $domain = PoP_Domain_Utils::getDomainFromRequest();
                if (!$domain) {
                    return new CheckpointError('domainempty', 'domainempty');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

