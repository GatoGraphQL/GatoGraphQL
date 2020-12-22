<?php

class PoP_EmailTemplatesFactory
{
    protected static $instances = array();

    public static function setInstance($instance)
    {
        self::$instances[$instance->getName()] = $instance;
    }

    public static function getInstance($name = null)
    {
        if (!$name) {
            $name = POP_EMAILFRAME_DEFAULT;
        }
        return self::$instances[$name];
    }
}
