<?php

declare(strict_types=1);

namespace PoP\API\Configuration;

use PoP\API\Constants\Params;
use PoP\API\Environment;

class Request
{
    public static function mustNamespaceTypes(): ?bool
    {
        if (!Environment::enableSettingNamespacingByURLParam()) {
            return null;
        }
        
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
