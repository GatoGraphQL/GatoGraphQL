<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoMetadata;

/**
 * Update the plugin version to the new one and add "-dev" again,
 * and bump the version constraint too
 */
final class BumpVersionForDevInPluginVersionReleaseWorker extends AbstractConvertVersionInPluginVersionReleaseWorker
{
    public function work(Version $version): void
    {
        $replacements = [
            '/\b' . preg_quote(substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev'))) . '\b/' => $this->versionUtils->getNextVersion($version) . '-dev',
            // Bump the version constraint
            '/\'' . preg_quote($this->upstreamVersionUtils->getRequiredFormat($version)) . '\'/' => '\'' . $this->upstreamVersionUtils->getRequiredNextFormat($version) . '\'',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the plugin to the next version, addding again "-dev" in it, in the plugin main file';
    }
}
