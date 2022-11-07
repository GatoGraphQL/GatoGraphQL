<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StaticHelpers;

class SemverHelpers
{
    /**
     * Remove the "-dev..." section from the version, required during development
     * and testing of a generated plugin as to regenerate the container,
     * but not needed for version constaints (and not supported by Composer Semver)
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
