<?php

declare(strict_types=1);

namespace PoP\Routing;

use PoP\Root\App;

class RoutingUtils
{
    public static function getURLPath(): string
    {
        // Allow to remove the language information from qTranslate (https://domain.com/en/...)
        $route = App::applyFilters(
            '\PoP\Routing:uri-route',
            $_SERVER['REQUEST_URI'] ?? '' // When executing PHPUnit tests there'll be no URI
        );
        $params_pos = strpos($route, '?');
        if ($params_pos !== false) {
            $route = substr($route, 0, $params_pos);
        }
        return trim($route, '/');
    }
}
