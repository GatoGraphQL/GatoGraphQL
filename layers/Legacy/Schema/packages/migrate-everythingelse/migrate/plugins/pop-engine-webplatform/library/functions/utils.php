<?php

use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;

class PoP_WebPlatformEngine_Utils
{
    public static function addUniqueId($url)
    {
        return $url . '#' . ComponentModelModuleInfo::get('unique-id');
    }
}
