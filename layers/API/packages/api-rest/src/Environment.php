<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI;

class Environment
{
    public static function disableRESTAPI(): bool
    {
        $envValue = getenv('DISABLE_REST_API');
        return $envValue !== false ? strtolower($envValue) === "true" : false;
    }
}
