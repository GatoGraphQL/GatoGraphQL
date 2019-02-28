<?php
namespace PoP\Engine\FileStorage;

class FileStorage_Factory
{
    protected static $instance;

    public static function setInstance(FileStorage $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?FileStorage
    {
        return self::$instance;
    }
}
