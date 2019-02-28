<?php
namespace PoP\Engine;

class FieldProcessor_Manager_Factory
{
    protected static $instance;

    public static function setInstance(FieldProcessor_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?FieldProcessor_Manager
    {
        return self::$instance;
    }
}
