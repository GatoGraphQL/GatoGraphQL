<?php
namespace PoP\Theme\Themes;

class ThemeManagerFactory
{
    protected static $instance;

    public static function setInstance(ThemeManager $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): ThemeManager
    {
        return self::$instance;
    }
}
