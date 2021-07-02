<?php

class PoP_Multilingual_FunctionsAPIFactory
{
    protected static $instance;

    public static function setInstance(PoP_Multilingual_FunctionsAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_Multilingual_FunctionsAPI
    {
        return self::$instance;
    }
}
