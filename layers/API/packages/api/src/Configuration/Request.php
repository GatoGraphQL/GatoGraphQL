<?php

declare(strict_types=1);

namespace PoPAPI\API\Configuration;

use PoPAPI\API\Constants\Params;
use PoPAPI\API\Environment;

class Request
{
    public static function mustNamespaceTypes(): ?bool
    {
        if (!Environment::enableSettingNamespacingByURLParam()) {
            return null;
        }

        $useNamespace = \PoP\Root\App::query(Params::USE_NAMESPACE);
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
