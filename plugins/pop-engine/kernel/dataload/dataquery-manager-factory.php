<?php
namespace PoP\Engine;

class DataQuery_Manager_Factory
{
    protected static $instance;

    public static function setInstance(DataQuery_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?DataQuery_Manager
    {
        return self::$instance;
    }
}
