<?php
namespace PoP\Engine;

class QueryHandler_Manager_Factory
{
    protected static $instance;

    public static function setInstance(QueryHandler_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?QueryHandler_Manager
    {
        return self::$instance;
    }
}
