<?php

class PoP_Locations_APIFactory
{
    protected static $instance;

    public static function setInstance(PoP_Locations_API $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_Locations_API
    {
        return self::$instance;
    }
}
