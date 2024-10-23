<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\PluginApp;

class PluginEnvironmentHelpers
{
    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     */
    public static function getWPConfigConstantValue(
        string $envVariable,
        ?string $namespace = null,
    ): mixed {
        return constant(self::getWPConfigConstantName($envVariable, $namespace));
    }

    /**
     * Determine if the environment variable was defined
     * as a constant in wp-config.php
     */
    public static function isWPConfigConstantDefined(
        string $envVariable,
        ?string $namespace = null,
    ): bool {
        return defined(self::getWPConfigConstantName($envVariable, $namespace));
    }

    /**
     * Constants defined in wp-config.php must start with this prefix
     * to override Gato GraphQL environment variables
     */
    public static function getWPConfigConstantName(
        string $envVariable,
        ?string $namespace = null,
    ): string {
        $namespace ??= PluginApp::getMainPlugin()->getPluginWPConfigConstantNamespace();
        return $namespace . '_' . $envVariable;
    }
}
