<?php
namespace PoP\CMS;

class HooksAPI_Factory
{
    protected static $instance;

    public static function setInstance(HooksAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?HooksAPI
    {
        return self::$instance;
    }
}
