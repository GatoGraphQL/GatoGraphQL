<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoMetadata;

/**
 * Update the plugin version to the new one and add "-dev" again,
 * and bump the version constraint too
 */
final class BumpVersionForDevInPluginMainFileReleaseWorker extends AbstractConvertVersionInPluginMainFileReleaseWorker
{
    public function work(Version $version): void
    {
        $nextDevVersion = $this->versionUtils->getNextVersion($version) . '-dev';
        // The file has already been replaced by a previous ReleaseWorker, so the current version is that for PROD
        $prodVersion = $this->monorepoMetadataVersionUtils->getProdVersion();
        $replacements = [
            // WordPress plugin header
            '/\bVersion:\s+' . preg_quote($prodVersion) . '\b/' => 'Version: ' . $nextDevVersion,
            // Gato GraphQL plugin version (in a variable)
            '/\'' . preg_quote($prodVersion) . '\'/' => '\'' . $nextDevVersion . '\'',
            // Main Gato GraphQL plugin version constraint (in a variable)
            '/\'' . preg_quote($this->upstreamVersionUtils->getRequiredFormat($version)) . '\'/' => '\'' . $this->upstreamVersionUtils->getRequiredNextFormat($version) . '\'',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginMainFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the plugin to the next version, addding again "-dev" in it, in the plugin main file';
    }
}
