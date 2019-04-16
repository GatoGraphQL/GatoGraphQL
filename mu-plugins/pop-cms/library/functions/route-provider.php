<?php
namespace PoP\CMS;

class RouteProvider {

    private static $routes;

    public static function getRoutes() {

        if (is_null(self::$routes)) {
            self::$routes = array_filter(
                \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
                    'routes', 
                    []
                )
            );
        }

        return self::$routes;
    }
}