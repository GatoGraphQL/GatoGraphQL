<?php

declare(strict_types=1);

namespace PoP\Root\Configuration;

use PoP\Root\Constants\Params;

class Request
{
    public static function getRoute(): ?string
    {
        if (isset($_REQUEST[Params::ROUTE])) {
            return trim(strtolower($_REQUEST[Params::ROUTE]), '/');
        }
        return null;
    }
}
