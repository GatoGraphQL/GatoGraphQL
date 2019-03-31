<?php
namespace PoP\CMS;

class CMSLooseContract_Manager_Factory
{
    protected static $instance;

    public static function setInstance(CMSLooseContract_Manager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ?CMSLooseContract_Manager
    {
        return self::$instance;
    }
}
