<?php

declare(strict_types=1);

namespace PoP\API\Configuration;

use PoP\API\Constants\Params;

class Request
{
    public static function mustNamespaceTypes(): ?bool
    {
        if (isset($_REQUEST[Params::USE_NAMESPACE])) {
            return in_array(
                strtolower($_REQUEST[Params::USE_NAMESPACE]),
                [
                    "true",
                    "on",
                    "1"
                ]
            );
        }
        return null;
    }
}
