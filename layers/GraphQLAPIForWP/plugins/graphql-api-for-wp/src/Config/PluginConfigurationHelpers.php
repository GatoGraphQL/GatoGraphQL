<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Config;

/**
 * Helpers for the Plugin configuration
 */
class PluginConfigurationHelpers
{
    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     *
     * @return mixed
     */
    public static function getWPConfigConstantValue(string $envVariable)
    {
        return constant(self::getWPConfigConstantName($envVariable));
    }

    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     */
    public static function isWPConfigConstantDefined(string $envVariable): bool
    {
        return defined(self::getWPConfigConstantName($envVariable));
    }

    /**
     * Constants defined in wp-config.php must start with this prefix
     * to override GraphQL API environment variables
     */
    public static function getWPConfigConstantName(string $envVariable): string
    {
        return 'GRAPHQL_API_' . $envVariable;
    }
}
