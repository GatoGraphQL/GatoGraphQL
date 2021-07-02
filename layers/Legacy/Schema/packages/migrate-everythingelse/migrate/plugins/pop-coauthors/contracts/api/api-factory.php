<?php

class PoP_Coauthors_APIFactory
{
    protected static $instance;

    public static function setInstance(PoP_Coauthors_API $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_Coauthors_API
    {
        return self::$instance;
    }
}
