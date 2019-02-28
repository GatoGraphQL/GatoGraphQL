<?php
namespace PoP\Engine;

abstract class CheckpointProcessorBase
{
    public function __construct()
    {
        CheckpointProcessor_Manager_Factory::getInstance()->add($this, $this->getCheckpointsToProcess());
    }

    abstract public function getCheckpointsToProcess();

    public function process($checkpoint/*, $module = null*/)
    {
    
        // By default, no problem at all, so always return true
        return true;
    }
}
