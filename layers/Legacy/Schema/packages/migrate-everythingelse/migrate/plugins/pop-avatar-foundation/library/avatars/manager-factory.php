<?php

class PoP_AvatarFoundationManagerFactory
{
    protected static $instance;

    public static function setInstance(PoP_AvatarFoundationManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_AvatarFoundationManager
    {
        return self::$instance;
    }
}
