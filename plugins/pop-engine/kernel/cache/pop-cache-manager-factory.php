<?php
namespace PoP\Engine;

class CacheManager_Factory
{
    protected static $instance;

    public static function setInstance(CacheManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?CacheManager
    {
        return self::$instance;
    }
}
