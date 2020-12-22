<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentConfiguration;

/**
 * Helpers to convert environment values
 */
class EnvironmentValueHelpers
{
    /**
     * Convert the environment value from string to boolean.
     * A string is true if its lowercase value is either "true" or "on", or if it's "1"
     *
     * @param string $value environment value
     * @return boolean
     */
    public static function toBool(string $value): bool
    {
        return in_array(strtolower($value), ['true', 'on', '1']);
    }

    /**
     * Convert the environment value from string to int
     *
     * @param string $value environment value
     * @return boolean
     */
    public static function toInt(string $value): int
    {
        return (int) $value;
    }
}
