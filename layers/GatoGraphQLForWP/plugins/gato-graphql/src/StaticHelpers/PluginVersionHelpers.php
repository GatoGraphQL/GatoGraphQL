<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

class PluginVersionHelpers
{
    /**
     * The generated plugin for DEV will always have the "-dev" version
     * attached to it.
     *
     * This is useful because:
     *
     * - We can have different testing/production licenses for the
     * Marketplace, and validate that the license is validated against
     * the corresponding plugin only.
     * - We can have images be fetched from "master" in the GitHub
     * repo for the DEV plugin, and from {version_tag} for PROD.
     */
    public static function isDevelopmentVersion(string $pluginVersion): bool
    {
        return str_ends_with($pluginVersion, '-dev');
    }
}
