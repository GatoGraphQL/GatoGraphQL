<?php

class PoP_UserStateModuleDecoratorProcessorManagerFactory
{
    protected static $instance;

    public static function setInstance(PoP_UserStateModuleDecoratorProcessorManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_UserStateModuleDecoratorProcessorManager
    {
        return self::$instance;
    }
}
