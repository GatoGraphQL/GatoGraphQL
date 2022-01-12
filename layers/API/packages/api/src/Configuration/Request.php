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

        $useNamespace = $_REQUEST[Params::USE_NAMESPACE] ?? null;
        if ($useNamespace === null) {
            return null;
        }

        return in_array(
            strtolower($useNamespace),
            [
                "true",
                "on",
                "1"
            ]
        );
    }
}
