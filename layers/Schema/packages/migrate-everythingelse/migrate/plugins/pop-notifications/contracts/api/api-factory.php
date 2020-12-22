<?php

class PoP_Notifications_FunctionsAPIFactory
{
    protected static $instance;

    public static function setInstance(PoP_Notifications_FunctionsAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_Notifications_FunctionsAPI
    {
        return self::$instance;
    }
}
