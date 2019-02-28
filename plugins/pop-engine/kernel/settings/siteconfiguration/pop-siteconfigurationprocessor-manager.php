<?php
namespace PoP\Engine\Settings;

class SiteConfigurationProcessor_Manager
{
    public $processor;
    
    public function __construct()
    {
        SiteConfigurationProcessorManager_Factory::setInstance($this);
    }

    public function getProcessor()
    {
        return $this->processor;
    }
    
    public function set($processor)
    {
        $this->processor = $processor;
    }
}

/**
 * Initialization
 */
new SiteConfigurationProcessor_Manager();
