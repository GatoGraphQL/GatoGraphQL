<?php
namespace PoP\ComponentModel;
use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;

class CheckpointProcessorManager
{
    use ItemProcessorManagerTrait;

    public function __construct()
    {
        CheckpointProcessorManagerFactory::setInstance($this);
    }
}

/**
 * Initialization
 */
new CheckpointProcessorManager();
