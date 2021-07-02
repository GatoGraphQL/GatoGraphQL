<?php

class PoP_SSR_CLI_HelperRegistration
{
    private static $helper_methods = array();

    public static function register($class_name)
    {

        // List down all methods from the provided class, creating entries like this:
        // 'showmore' => 'PoP_ServerSide_HelperCallers::showmore',
        foreach (get_class_methods($class_name) as $class_method) {
            self::$helper_methods[$class_method] = $class_name.'::'.$class_method;
        }
    }

    public static function getHelperMethods()
    {
        return self::$helper_methods;
    }
}
