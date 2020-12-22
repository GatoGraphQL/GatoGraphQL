<?php

$checkpointprocessor_manager = \PoP\ComponentModel\CheckpointProcessorManagerFactory::getInstance();
$checkpointprocessor_manager->overrideProcessorClass(
    PoP_Domain_Dataload_CheckpointProcessor::class,
    PoP_MultiDomain_Dataload_CheckpointProcessor::class,
    [
        PoP_Domain_Dataload_CheckpointProcessor::CHECKPOINT_DOMAINVALID,
    ]
);
