<?php

class PoP_WebPlatformEngine_Utils
{
    public static function addUniqueId($url)
    {
        return $url.'#'.POP_CONSTANT_UNIQUE_ID;
    }
}
