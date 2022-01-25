<?php

declare(strict_types=1);

namespace PoP\Root\Configuration;

use PoP\Root\Constants\Params;

class Request
{
    public static function getRoute(): ?string
    {
        $route = \PoP\Root\App::request(Params::ROUTE) ?? \PoP\Root\App::query(Params::ROUTE);
        if ($route === null) {
            return null;
        }
        return trim(strtolower($route), '/');
    }
}
