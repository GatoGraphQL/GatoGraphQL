<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

class PluginVersionHelpers
{
    public static function isDevelopmentVersion(string $pluginVersion): bool
    {
        return str_ends_with($pluginVersion, '-dev');
    }
}
