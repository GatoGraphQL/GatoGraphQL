<?php
namespace PoPSchema\QueriedObject;

class CMSRoutingStateFactory
{
    protected static $instance;

    public static function setInstance(CMSRoutingStateInterface $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): CMSRoutingStateInterface
    {
        return self::$instance;
    }
}
