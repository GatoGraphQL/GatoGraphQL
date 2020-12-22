<?php
namespace PoP\ComponentModel\Settings;

class SiteConfigurationProcessorManager
{
    public $processor;
    
    public function __construct()
    {
        SiteConfigurationProcessorManagerFactory::setInstance($this);
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
new SiteConfigurationProcessorManager();
