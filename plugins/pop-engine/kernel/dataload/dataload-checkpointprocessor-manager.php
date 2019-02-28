<?php
namespace PoP\Engine;

class CheckpointProcessor_Manager
{
    public $processors;
    
    public function __construct()
    {
        CheckpointProcessor_Manager_Factory::setInstance($this);
        $this->processors = array();
    }
    
    public function getProcessor($checkpoint)
    {
        return $this->processors[$checkpoint];
    }

    public function process($checkpoint/*, $module = null*/)
    {
        $processor = $this->getProcessor($checkpoint);
        return $processor->process($checkpoint, $module);
    }
    
    public function add($processor, $checkpoints_to_process)
    {
        foreach ($checkpoints_to_process as $checkpoint) {
            $this->processors[$checkpoint] = $processor;
        }
    }
}

/**
 * Initialization
 */
new CheckpointProcessor_Manager();
