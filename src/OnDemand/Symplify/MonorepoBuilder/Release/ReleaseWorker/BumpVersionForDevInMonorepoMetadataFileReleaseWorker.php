<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Update the MonorepoMetadata version to the new one, and add "-dev" again
 */
final class BumpVersionForDevInMonorepoMetadataFileReleaseWorker extends AbstractConvertVersionInMonorepoMetadataFileReleaseWorker
{
    public function work(Version $version): void
    {
        $nextDevVersion = $this->versionUtils->getNextDevVersion($version);
        // The file has already been replaced by a previous ReleaseWorker, so the current version is that for PROD
        $prodVersion = $this->monorepoMetadataVersionUtils->getProdVersion();
        $replacements = [
            '/\'' . preg_quote($prodVersion) . '\'/' => '\'' . $nextDevVersion . '\'',
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
