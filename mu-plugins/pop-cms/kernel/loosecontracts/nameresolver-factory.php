<?php
namespace PoP\CMS;

class NameResolver_Factory
{
    protected static $instance;

    public static function setInstance(NameResolver $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?NameResolver
    {
        return self::$instance;
    }
}
