<?php

declare(strict_types=1);

namespace PoP\API\Configuration;

use PoP\API\Schema\QueryInputs;

class Request
{
    public const URLPARAM_USE_NAMESPACE = 'use_namespace';

    public static function mustNamespaceTypes(): ?bool
    {
        if (isset($_REQUEST[self::URLPARAM_USE_NAMESPACE])) {
            return in_array(
                strtolower($_REQUEST[self::URLPARAM_USE_NAMESPACE]),
                [
                    "true",
                    "on",
                    "1"
                ]
            );
        }
        return null;
    }

    public static function getQuery(): ?string
    {
        return $_REQUEST[QueryInputs::QUERY] ?? null;
    }
}
