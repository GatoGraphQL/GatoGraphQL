<?php

declare(strict_types=1);

namespace PoP\Routing;

class Routes
{
    use RoutesTrait;

    public static string $MAIN = '';
    /**
     * @return array<string, string>
     */
    protected static function getRouteNameAndVariableRefs(): array
    {
        return [
            'main' => &self::$MAIN,
        ];
    }
}
