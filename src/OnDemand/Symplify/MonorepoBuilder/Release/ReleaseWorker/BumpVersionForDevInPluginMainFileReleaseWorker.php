<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Update the plugin version to the new one and add "-dev" again,
 * and bump the version constraint too
 */
class BumpVersionForDevInPluginMainFileReleaseWorker extends AbstractConvertVersionInPluginMainFileReleaseWorker
{
    public function work(Version $version): void
    {
        $nextDevVersion = $this->versionUtils->getNextDevVersion($version);
        $requiredNextDevVersion = $this->upstreamVersionUtils->getRequiredNextFormat($version);
        
        // The file has already been replaced by a previous ReleaseWorker, so the current version is that for PROD
        $prodVersion = $version->getVersionString();
        $replacements = [
            // WordPress plugin header
            '/\bVersion:\s+' . preg_quote($prodVersion) . '\b/' => 'Version: ' . $nextDevVersion,
            // Gato GraphQL plugin/extension version
            "/" . preg_quote('$pluginVersion') . " = '[a-z0-9.-]+';/" => "\$pluginVersion = '$nextDevVersion';",
            "/" . preg_quote('$extensionVersion') . " = '[a-z0-9.-]+';/" => "\$extensionVersion = '$nextDevVersion';",
            // Main Gato GraphQL plugin version constraint (in a variable)
            "/" . preg_quote('$mainPluginVersionConstraint') . " = '[0-9.^]+';/" => "\$mainPluginVersionConstraint = '$requiredNextDevVersion';",
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginMainFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the plugin to the next version, adding again "-dev" in it, in the plugin main file';
    }
}
