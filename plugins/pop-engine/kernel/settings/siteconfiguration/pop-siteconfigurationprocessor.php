<?php
namespace PoP\Engine\Settings;

class SiteConfigurationProcessorBase
{
    public function __construct()
    {
        SiteConfigurationProcessorManager_Factory::getInstance()->set($this);
    }

    public function getEntryModule()
    {
        return null;
    }
}
