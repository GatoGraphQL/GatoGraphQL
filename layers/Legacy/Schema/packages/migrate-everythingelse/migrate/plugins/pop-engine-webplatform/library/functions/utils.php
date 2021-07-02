<?php

use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;

class PoP_WebPlatformEngine_Utils
{
    public static function addUniqueId($url)
    {
        return $url . '#' . ComponentModelComponentInfo::get('unique-id');
    }
}
