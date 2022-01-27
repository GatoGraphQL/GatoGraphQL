<?php
namespace PoPCMSSchema\UserState\Settings;

class SettingsProcessorManager extends \PoP\ComponentModel\Settings\AbstractSettingsProcessorManager
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
