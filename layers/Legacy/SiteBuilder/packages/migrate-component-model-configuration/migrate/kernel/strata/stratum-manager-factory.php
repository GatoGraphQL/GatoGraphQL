<?php
namespace PoP\ComponentModel;

class StratumManagerFactory
{
    protected static $instance;

    public static function setInstance(StratumManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): StratumManager
    {
        return self::$instance;
    }
}
