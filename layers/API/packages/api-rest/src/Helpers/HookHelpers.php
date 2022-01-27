<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\Helpers;

class HookHelpers
{
    public static function getHookName(string $class): string
    {
        return sprintf(
            '%s:RESTFields',
            $class
        );
    }
}
