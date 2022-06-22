<?php
use PoP\ComponentModel\Checkpoints\AbstractCheckpoint;
use PoP\Root\Feedback\FeedbackItemResolution;

class PoP_Domain_Dataload_Checkpoint extends AbstractCheckpoint
{
    public final const CHECKPOINT_DOMAINVALID = 'checkpoint-domainvalid';

    /**
     * @todo Migrate to not using $checkpoint
     */
    public function validateCheckpoint(): ?FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_DOMAINVALID:
                // Check if the domain passed in param 'domain' is allowed
                $domain = PoP_Domain_Utils::getDomainFromRequest();
                if (!$domain) {
                    return new FeedbackItemResolution('domainempty', 'domainempty');
                }
                break;
        }

        return parent::validateCheckpoint();
    }
}

