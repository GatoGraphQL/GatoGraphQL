<?php
namespace PoP\Engine;

class ModuleProcessor_Manager_Factory
{
    protected static $instance;

    public static function setInstance(ModuleProcessor_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?ModuleProcessor_Manager
    {
        return self::$instance;
    }
}
