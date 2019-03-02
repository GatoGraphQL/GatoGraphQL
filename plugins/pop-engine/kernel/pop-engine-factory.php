<?php
namespace PoP\Engine;

class Engine_Factory
{
    protected static $instance;

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function output()
    {
        self::$instance->output();
    }
}

// // For HTML Output: call output function on the footer (it won't get called for JSON output)
// \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('wp_footer', array(\PoP\Engine\Engine_Factory::class, 'output'));
