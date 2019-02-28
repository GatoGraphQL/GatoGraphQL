<?php
namespace PoP\Engine\FileStorage;

class FileJSONStorage_Factory
{
    protected static $instance;

    public static function setInstance(FileJSONStorage $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?FileJSONStorage
    {
        return self::$instance;
    }
}
