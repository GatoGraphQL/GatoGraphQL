<?php
namespace PoP\ComponentModel\Settings;

class SettingsProcessorManager extends AbstractSettingsProcessorManager
{
    public function __construct()
    {
        parent::__construct();
        SettingsProcessorManagerFactory::setInstance($this);
    }
}

/**
 * Initialization
 */
new SettingsProcessorManager();
