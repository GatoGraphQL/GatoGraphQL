<?php
namespace PoP\QueriedObject;

class CMSRoutingState_Factory
{
    protected static $instance;

    public static function setInstance(CMSRoutingState $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?CMSRoutingState
    {
        return self::$instance;
    }
}
