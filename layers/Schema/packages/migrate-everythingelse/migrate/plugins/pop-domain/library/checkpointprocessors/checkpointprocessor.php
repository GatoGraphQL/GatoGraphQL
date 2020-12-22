<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class PoP_Domain_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_DOMAINVALID = 'checkpoint-domainvalid';

    public function getCheckpointsToProcess()
    {
        return array(
            [self::class, self::CHECKPOINT_DOMAINVALID],
        );
    }

    public function process(array $checkpoint)
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_DOMAINVALID:
                // Check if the domain passed in param 'domain' is allowed
                $domain = PoP_Domain_Utils::getDomainFromRequest();
                if (!$domain) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('domainempty');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}

