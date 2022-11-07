<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

class SemverHelpers
{
    /**
     * The "#..." section in the version is the commit hash
     * added by CI when merging the PR. It is required
     * to regenerate the container when testing a generated
     * .zip plugin without modifying the plugin version.
     * (Otherwise, we'd have to @purge-cache.)
     *
     * The commit hash must be removed to check the plugin version
     * constraint as it is not supported by Composer Semver:
     *
     *   > private static $modifierRegex = '[._-]?(?:(stable|beta|b|RC|alpha|a|patch|pl|p)((?:[.-]?\d+)*+)?)?([.-]?dev)?';
     *
     * @see https://github.com/composer/semver/blob/9b2d75f/src/VersionParser.php#L39
     */
    public static function removeCommitHashFromPluginVersion(string $pluginVersion): string
    {
        $pos = strpos($pluginVersion, '#');
        if ($pos === false) {
            return $pluginVersion;
        }
        return substr($pluginVersion, 0, $pos);
    }
}
