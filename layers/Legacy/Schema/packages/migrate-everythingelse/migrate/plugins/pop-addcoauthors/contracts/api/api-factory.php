<?php

class PoP_AddCoauthors_APIFactory
{
    protected static $instance;

    public static function setInstance(PoP_AddCoauthors_API $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_AddCoauthors_API
    {
        return self::$instance;
    }
}
