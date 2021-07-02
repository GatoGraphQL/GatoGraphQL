<?php

class PoP_ResourceLoader_ServerSide_LibrariesFactory
{
    protected static $resourceloader;

    public static function setResourceloaderInstance($resourceloader)
    {
        self::$resourceloader = $resourceloader;
    }

    public static function getResourceloaderInstance()
    {
        return self::$resourceloader;
    }
}
