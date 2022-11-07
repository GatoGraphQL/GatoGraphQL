<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

class SemverHelpers
{
    /**
     * Remove the "-dev..." section from the version, required during development
     * and testing of a generated plugin as to regenerate the container,
     * but not needed for version constaints.
     *
     * Indeed, it must be removed as it is not supported by Composer Semver:
     * "-dev" cannot be followed by the commit hash:
     *
     *   > private static $modifierRegex = '[._-]?(?:(stable|beta|b|RC|alpha|a|patch|pl|p)((?:[.-]?\d+)*+)?)?([.-]?dev)?';
     *
     * @see https://github.com/composer/semver/blob/9b2d75f/src/VersionParser.php#L39
     */
    public static function removeDevMetadataFromPluginVersion(string $pluginVersion): string
    {
        $pos = strpos($pluginVersion, '-dev');
        if ($pos === false) {
            return $pluginVersion;
        }
        return substr($pluginVersion, 0, $pos);
    }
}
