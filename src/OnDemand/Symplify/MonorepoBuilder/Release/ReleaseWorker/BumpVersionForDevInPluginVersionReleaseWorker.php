<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoMetadata;

/**
 * Update the plugin version to the new one, and add "-dev" again
 */
final class BumpVersionForDevInPluginVersionReleaseWorker extends AbstractConvertVersionInPluginVersionReleaseWorker
{
    public function work(Version $version): void
    {
        $nextVersionInString = $this->versionUtils->getNextVersion($version);
        $replacements = [
            '/\b' . preg_quote(substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev'))) . '\b/' => $nextVersionInString . '-dev',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the plugin to the next version, addding again "-dev" in it, in the plugin main file';
    }
}
