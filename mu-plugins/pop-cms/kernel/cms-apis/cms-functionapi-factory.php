<?php
namespace PoP\CMS;

class FunctionAPI_Factory
{
    protected static $instance;

    public static function setInstance(FunctionAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?FunctionAPI
    {
        return self::$instance;
    }
}
