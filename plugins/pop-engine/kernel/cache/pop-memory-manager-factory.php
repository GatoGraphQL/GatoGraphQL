<?php
namespace PoP\Engine;

class MemoryManager_Factory
{
    protected static $instance;

    public static function setInstance(MemoryManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?MemoryManager
    {
        return self::$instance;
    }
}
