<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Config\PluginConfigurationHelpers;

class PluginEnvironment
{
    public const PLUGIN_ENVIRONMENT = 'PLUGIN_ENVIRONMENT';

    /**
     * The plugins runs in PROD
     */
    public const PLUGIN_ENVIRONMENT_PROD = 'production';
    /**
     * The plugins runs in DEV
     */
    public const PLUGIN_ENVIRONMENT_DEV = 'development';

    /**
     * Return a value for a variable, checking if it is defined in the environment
     * first, and in the wp-config.php second
     *
     * @return mixed
     */
    protected static function getValueFromEnvironmentOrWPConfig(string $envVariable)
    {
        if (getenv($envVariable) !== false) {
            return getenv($envVariable);
        }

        if (PluginConfigurationHelpers::isWPConfigConstantDefined($envVariable)) {
            return PluginConfigurationHelpers::getWPConfigConstantValue($envVariable);
        };

        return null;
    }

    /**
     * The label to show when the value is empty
     */
    public static function getPluginEnvironment(): string
    {
        $environments = [
            self::PLUGIN_ENVIRONMENT_PROD,
            self::PLUGIN_ENVIRONMENT_DEV,
        ];
        $value = self::getValueFromEnvironmentOrWPConfig(self::PLUGIN_ENVIRONMENT);
        if (!is_null($value) && in_array($value, $environments)) {
            return $value;
        }
        // Default value
        return self::PLUGIN_ENVIRONMENT_PROD;
    }

    public static function isPluginEnvironmentProd(): bool
    {
        return self::getPluginEnvironment() == self::PLUGIN_ENVIRONMENT_PROD;
    }

    public static function isPluginEnvironmentDev(): bool
    {
        return self::getPluginEnvironment() == self::PLUGIN_ENVIRONMENT_DEV;
    }
}
