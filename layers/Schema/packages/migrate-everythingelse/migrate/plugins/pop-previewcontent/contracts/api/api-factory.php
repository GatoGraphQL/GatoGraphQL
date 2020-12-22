<?php

class PoP_PreviewContent_FunctionsAPIFactory
{
    protected static $instance;

    public static function setInstance(PoP_PreviewContent_FunctionsAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_PreviewContent_FunctionsAPI
    {
        return self::$instance;
    }
}
