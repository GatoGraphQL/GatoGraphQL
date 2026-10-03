<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use function filemtime;
use function is_file;

class AssetVersionHelpers
{
    /**
     * A development build keeps its version from one build to the next, so
     * a cache keyed on the asset's URL (the browser's, or a CDN's, which
     * keeps it for as long as the site says to) goes on serving the asset of
     * an earlier build. The time the file was written is added to its
     * version, which installing another build changes.
     *
     * A released version is another URL for every release, so it is
     * returned as it is.
     */
    public static function getAssetVersion(string $pluginVersion, string $assetFilePath): string
    {
        if (!PluginVersionHelpers::isDevelopmentVersion($pluginVersion) || !is_file($assetFilePath)) {
            return $pluginVersion;
        }
        $fileTime = filemtime($assetFilePath);
        if ($fileTime === false) {
            return $pluginVersion;
        }
        return $pluginVersion . '-' . $fileTime;
    }
}
