<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\ErrorHandling\Error;

class PoP_MultiDomain_Dataload_CheckpointProcessor extends AbstractCheckpointProcessor
{
    public function getCheckpointsToProcess()
    {
        return array(
            [PoP_Domain_Dataload_CheckpointProcessor::class, PoP_Domain_Dataload_CheckpointProcessor::CHECKPOINT_DOMAINVALID],
        );
    }

    public function process(array $checkpoint)
    {
        switch ($checkpoint[1]) {
            case PoP_Domain_Dataload_CheckpointProcessor::GD_DATALOAD_CHECKPOINT_DOMAINVALID:
                // Check if the domain passed in param 'domain' is allowed
                $domain = PoP_Domain_Utils::getDomainFromRequest();
                if (!$domain) {
                    return new Error('domainempty');
                }
                $allowed_domains = PoP_WebPlatform_ConfigurationUtils::getAllowedDomains();
                if (!in_array($domain, $allowed_domains)) {
                    return new Error('domainnotvalid');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}
