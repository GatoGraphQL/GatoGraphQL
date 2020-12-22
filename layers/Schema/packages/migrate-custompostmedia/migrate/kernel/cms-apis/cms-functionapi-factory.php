<?php
namespace PoPSchema\Media;

class PostsFunctionAPIFactory
{
    protected static $instance;

    public static function setInstance(PostsFunctionAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PostsFunctionAPI
    {
        return self::$instance;
    }
}
