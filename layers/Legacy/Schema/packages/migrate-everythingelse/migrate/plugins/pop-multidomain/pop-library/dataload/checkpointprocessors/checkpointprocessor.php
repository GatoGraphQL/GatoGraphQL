<?php
use PoP\ComponentModel\Checkpoint\CheckpointError;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class PoP_MultiDomain_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public function getCheckpointsToProcess(): array
    {
        return array(
            [PoP_Domain_Dataload_CheckpointProcessor::class, PoP_Domain_Dataload_CheckpointProcessor::CHECKPOINT_DOMAINVALID],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Feedback\FeedbackItemResolution
    {
        switch ($checkpoint[1]) {
            case PoP_Domain_Dataload_CheckpointProcessor::CHECKPOINT_DOMAINVALID:
                // Check if the domain passed in param 'domain' is allowed
                $domain = PoP_Domain_Utils::getDomainFromRequest();
                if (!$domain) {
                    return new CheckpointError('domainempty', 'domainempty');
                }
                $allowed_domains = PoP_WebPlatform_ConfigurationUtils::getAllowedDomains();
                if (!in_array($domain, $allowed_domains)) {
                    return new CheckpointError('domainnotvalid', 'domainnotvalid');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}
