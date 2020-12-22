<?php

class PoP_Avatar_FunctionsAPIFactory
{
    protected static $instance;

    public static function setInstance(PoP_Avatar_FunctionsAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_Avatar_FunctionsAPI
    {
        return self::$instance;
    }
}
