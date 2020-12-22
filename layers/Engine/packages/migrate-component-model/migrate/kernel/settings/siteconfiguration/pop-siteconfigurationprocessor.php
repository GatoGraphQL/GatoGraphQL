<?php
namespace PoP\ComponentModel\Settings;

class SiteConfigurationProcessorBase
{
    public function __construct()
    {
        SiteConfigurationProcessorManagerFactory::getInstance()->set($this);
    }

    public function getEntryModule(): ?array
    {
        return null;
    }
}
