<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use function filemtime;
use function is_file;
use function is_string;
use function parse_str;
use function parse_url;
use function preg_quote;
use function preg_replace;
use function str_starts_with;
use function strlen;
use function strtok;
use function substr;

use const PHP_URL_QUERY;

class AssetVersionHelpers
{
    /**
     * A development build keeps its version from one build to the next, so
     * a cache keyed on the asset's URL (the browser's, or a CDN's, which
     * keeps it for as long as the site says to) goes on serving the asset of
     * an earlier build. The time the file was written is added to its
     * version, which installing another build changes.
     *
     * Only the plugin's own assets are versioned, and only those whose
     * version is a development one: an asset versioned by a hash of its
     * content is cache-proof already, and a released version is another URL
     * for every release.
     *
     * @param string $pluginURL The plugin's URL, ending in "/"
     * @param string $pluginFolder The plugin's folder, not ending in "/"
     */
    public static function addFileTimeToDevelopmentAssetVersion(string $src, string $pluginURL, string $pluginFolder): string
    {
        if ($pluginURL === '' || !str_starts_with($src, $pluginURL)) {
            return $src;
        }
        $queryArgs = [];
        parse_str((string) parse_url($src, PHP_URL_QUERY), $queryArgs);
        $version = $queryArgs['ver'] ?? null;
        if (!is_string($version) || !PluginVersionHelpers::isDevelopmentVersion($version)) {
            return $src;
        }
        $relativePath = strtok(substr($src, strlen($pluginURL)), '?#');
        if ($relativePath === false) {
            return $src;
        }
        $filePath = $pluginFolder . '/' . $relativePath;
        if (!is_file($filePath)) {
            return $src;
        }
        $fileTime = filemtime($filePath);
        if ($fileTime === false) {
            return $src;
        }
        return (string) preg_replace(
            '/([?&]ver=)' . preg_quote($version, '/') . '(?=&|#|$)/',
            '${1}' . $version . '-' . $fileTime,
            $src,
            1
        );
    }
}
