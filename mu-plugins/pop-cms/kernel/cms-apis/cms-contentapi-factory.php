<?php
namespace PoP\CMS;

class ContentAPI_Factory
{
    protected static $instance;

    public static function setInstance(ContentAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?ContentAPI
    {
        return self::$instance;
    }
}
