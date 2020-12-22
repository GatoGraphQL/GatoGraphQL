<?php
namespace PoPSchema\CommentMeta;

class FunctionAPIFactory
{
    protected static $instance;

    public static function setInstance(FunctionAPI $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): FunctionAPI
    {
        return self::$instance;
    }
}
