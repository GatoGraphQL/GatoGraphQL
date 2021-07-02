<?php

class PoP_GoogleAnalytics_APIFactory
{
    protected static $instance;

    public static function setInstance(PoP_GoogleAnalytics_API $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_GoogleAnalytics_API
    {
        return self::$instance;
    }
}
