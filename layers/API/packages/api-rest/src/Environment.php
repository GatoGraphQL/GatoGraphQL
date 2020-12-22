<?php

declare(strict_types=1);

namespace PoP\RESTAPI;

class Environment
{
    public static function disableRESTAPI(): bool
    {
        return getenv('DISABLE_REST_API') !== false ? strtolower(getenv('DISABLE_REST_API')) == "true" : false;
    }
}
