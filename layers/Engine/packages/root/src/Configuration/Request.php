<?php

declare(strict_types=1);

namespace PoP\Root\Configuration;

use PoP\Root\App;
use PoP\Root\Constants\Params;

class Request
{
    public static function getRoute(): ?string
    {
        $route = App::request(Params::ROUTE) ?? App::query(Params::ROUTE);
        if ($route === null) {
            return null;
        }
        return trim(strtolower($route), '/');
    }
}
