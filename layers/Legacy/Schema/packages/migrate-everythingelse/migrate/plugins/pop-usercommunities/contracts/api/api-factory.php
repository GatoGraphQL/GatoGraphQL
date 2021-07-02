<?php

class PoP_UserCommunities_APIFactory
{
    protected static $instance;

    public static function setInstance(PoP_UserCommunities_API $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_UserCommunities_API
    {
        return self::$instance;
    }
}
