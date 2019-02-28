<?php
namespace PoP\Engine;

class ActionExecution_Manager_Factory
{
    protected static $instance;

    public static function setInstance(ActionExecution_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?ActionExecution_Manager
    {
        return self::$instance;
    }
}
