<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoMetadata;

/**
 * Update the MonorepoMetadata version to the new one, and add "-dev" again
 */
final class BumpVersionForDevInMonorepoMetadataVersionReleaseWorker extends AbstractConvertVersionInMonorepoMetadataVersionReleaseWorker
{
    public function work(Version $version): void
    {
        $nextDevVersion = $this->versionUtils->getNextVersion($version) . '-dev';
        // The file has already been replaced by a previous ReleaseWorker, so the current version is that for PROD
        $prodVersion = substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev'));
        $replacements = [
            '/\'' . preg_quote($prodVersion) . '\'/' => $nextDevVersion,
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles(
            [
                $this->monorepoMetadataFile,
            ],
            $replacements,
        );
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the plugin to the next version, addding again "-dev" in it, in the MonorepoMetadata version';
    }
}
