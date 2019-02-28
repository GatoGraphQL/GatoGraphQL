<?php
namespace PoP\Engine;

class ModuleProcessor_Manager
{
    public $processors;
    
    public function __construct()
    {
        ModuleProcessor_Manager_Factory::setInstance($this);
        $this->processors = array();
    }
    
    public function getProcessor($module)
    {

        // If it is a Virtual Module, then remove the atts
        list($module) = \PoP\Engine\VirtualModuleUtils::extractVirtualmodule($module);
    
        $processor = $this->processors[$module];
        if (!$processor) {
            throw new \Exception(sprintf('No Processor for $module \'%s\' (%s)', $module, fullUrl()));
        }

        return $processor;
    }
    
    public function add($processor, $modules_to_process)
    {
        foreach ($modules_to_process as $module) {
            $this->processors[$module] = $processor;
        }
    }
}

/**
 * Initialization
 */
new ModuleProcessor_Manager();
