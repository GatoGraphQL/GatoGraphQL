<?php

declare(strict_types=1);

namespace PoP\API\Configuration;

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
}
