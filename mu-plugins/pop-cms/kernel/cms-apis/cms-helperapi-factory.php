<?php
namespace PoP\CMS;

class HelperAPI_Factory
{
    protected static $instance;

    public static function setInstance(HelperAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?HelperAPI
    {
        return self::$instance;
    }
}
