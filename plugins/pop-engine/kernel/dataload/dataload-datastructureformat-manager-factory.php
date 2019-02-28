<?php
namespace PoP\Engine;

class DataStructureFormat_Manager_Factory
{
    protected static $instance;

    public static function setInstance(DataStructureFormat_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?DataStructureFormat_Manager
    {
        return self::$instance;
    }
}
