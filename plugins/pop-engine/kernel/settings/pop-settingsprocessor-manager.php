<?php
namespace PoP\Engine\Settings;

class SettingsProcessor_Manager
{
    public $processors;
    public $default_processor;

    public function getProcessors()
    {

        // Needed for the Cache Generator
        return $this->processors;
    }

    public function getRoutes()
    {

        // Filter out $page with no value, since the ID might've not been defined for that page
        return array_filter(array_keys($this->processors));
    }
    
    public function __construct()
    {
        SettingsProcessorManager_Factory::setInstance($this);
        $this->processors = array();
    }
    
    public function getProcessor($route)
    {
        if ($this->processors[$route]) {
            return $this->processors[$route];
        }

        if ($this->default_processor) {
            return $this->default_processor;
        }

        throw new \Exception(sprintf('No Settings Processor for $route \'%s\' (%s)', $route, fullUrl()));
    }
    
    public function add($processor)
    {
        foreach ($processor->routesToProcess() as $route) {
            $this->processors[$route] = $processor;
        }
    }
    
    public function setDefault($processor)
    {
        $this->default_processor = $processor;
    }
}

/**
 * Initialization
 */
new SettingsProcessor_Manager();
