<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

class PluginEnvironmentHelpers
{
    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     */
    public static function getWPConfigConstantValue(
        string $namespace,
        string $envVariable,
    ): mixed {
        return constant(self::getWPConfigConstantName($namespace, $envVariable));
    }

    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     */
    public static function isWPConfigConstantDefined(
        string $namespace,
        string $envVariable,
    ): bool {
        return defined(self::getWPConfigConstantName($namespace, $envVariable));
    }

    /**
     * Constants defined in wp-config.php must start with this prefix
     * to override Gato GraphQL environment variables
     */
    public static function getWPConfigConstantName(
        string $namespace,
        string $envVariable,
    ): string {
        return $namespace . '_' . $envVariable;
    }
}
