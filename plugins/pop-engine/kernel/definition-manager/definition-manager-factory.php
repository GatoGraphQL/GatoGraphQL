<?php
namespace PoP\Engine\Server;

class DefinitionManager_Factory
{
    protected static $instance;

    public static function setInstance(DefinitionManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?DefinitionManager
    {
        return self::$instance;
    }
}
