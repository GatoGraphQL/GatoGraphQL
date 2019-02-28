<?php
namespace PoP\Engine;

class CheckpointProcessor_Manager_Factory
{
    protected static $instance;

    public static function setInstance(CheckpointProcessor_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?CheckpointProcessor_Manager
    {
        return self::$instance;
    }
}
