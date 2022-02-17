<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\Error\Error;

class PoP_Domain_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_DOMAINVALID = 'checkpoint-domainvalid';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_DOMAINVALID],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Checkpoint\CheckpointError
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_DOMAINVALID:
                // Check if the domain passed in param 'domain' is allowed
                $domain = PoP_Domain_Utils::getDomainFromRequest();
                if (!$domain) {
                    return new Error('domainempty');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

