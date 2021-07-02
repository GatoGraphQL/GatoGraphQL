<?php

class PoP_SocialLogin_APIFactory
{
    protected static $instance;

    public static function setInstance(PoP_SocialLogin_API $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_SocialLogin_API
    {
        return self::$instance;
    }
}
