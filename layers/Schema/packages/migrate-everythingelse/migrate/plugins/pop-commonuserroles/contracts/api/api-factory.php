<?php

class PoP_CommonUserRoles_APIFactory
{
    protected static $instance;

    public static function setInstance(PoP_CommonUserRoles_API $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_CommonUserRoles_API
    {
        return self::$instance;
    }
}
