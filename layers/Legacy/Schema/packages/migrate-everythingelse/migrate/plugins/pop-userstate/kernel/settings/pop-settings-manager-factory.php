<?php
namespace PoPCMSSchema\UserState\Settings;

class SettingsManagerFactory
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
