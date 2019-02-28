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

    public function getPages()
    {

        // Filter out $page with no value, since the ID might've not been defined for that page
        return array_filter(array_keys($this->processors));
    }
    
    public function __construct()
    {
        SettingsProcessorManager_Factory::setInstance($this);
        $this->processors = array();
    }
    
    public function getProcessor($page_id)
    {
        if ($this->processors[$page_id]) {
            return $this->processors[$page_id];
        }

        if ($this->default_processor) {
            return $this->default_processor;
        }

        throw new \Exception(sprintf('No Settings Processor for $page_id \'%s\' (%s)', $page_id, fullUrl()));
    }
    
    public function add($processor)
    {
        foreach ($processor->pagesToProcess() as $page_id) {
            $this->processors[$page_id] = $processor;
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
