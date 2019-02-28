<?php
namespace PoP\Engine;

class Dataloader_Manager_Factory
{
    protected static $instance;

    public static function setInstance(Dataloader_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?Dataloader_Manager
    {
        return self::$instance;
    }
}
