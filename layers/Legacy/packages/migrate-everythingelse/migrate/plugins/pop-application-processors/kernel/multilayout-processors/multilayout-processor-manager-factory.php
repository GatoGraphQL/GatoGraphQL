<?php

class PoP_Application_MultilayoutManagerFactory
{
    protected static $instance;

    public static function setInstance(PoP_Application_MultilayoutManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_Application_MultilayoutManager
    {
        return self::$instance;
    }
}
