<?php
namespace PoP\ComponentModel\Settings;

class SiteConfigurationProcessorManagerFactory
{
    protected static $instance;

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance()
    {
        return self::$instance;
    }
}
