<?php

declare(strict_types=1);

namespace PoP\Root\Configuration;

use PoP\Root\Constants\Params;

class Request
{
    public static function getRoute(): ?string
    {
        $route = $_POST[Params::ROUTE] ?? $_GET[Params::ROUTE] ?? null;
        if ($route === null) {
            return null;
        }
        return trim(strtolower($route), '/');
    }
}
