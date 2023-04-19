<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

class AppHelpers
{
    public static function isExternalGraphQLServerAppThread(): bool
    {
        return App::getAppThread()->getName() === PluginAppGraphQLServerNames::EXTERNAL;
    }

    /**
     * Provide the same method as above with a shorter name
     */
    public static function isMainAppThread(): bool
    {
        return static::isExternalGraphQLServerAppThread();
    }

    public static function isInternalGraphQLServerAppThread(): bool
    {
        return App::getAppThread()->getName() === PluginAppGraphQLServerNames::INTERNAL;
    }
}
