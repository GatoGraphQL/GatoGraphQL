<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

class AppHelpers
{
    /**
     * Indicate if the current AppThread is the one used for
     * the External GraphQL Server. This is the default state.
     */
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

    /**
     * Indicate if the current AppThread is the one used for
     * the Internal GraphQL Server. This happens within the
     * invocation of `InternalGraphQLServer->execute`
     */
    public static function isInternalGraphQLServerAppThread(): bool
    {
        return App::getAppThread()->getName() === PluginAppGraphQLServerNames::INTERNAL;
    }
}
