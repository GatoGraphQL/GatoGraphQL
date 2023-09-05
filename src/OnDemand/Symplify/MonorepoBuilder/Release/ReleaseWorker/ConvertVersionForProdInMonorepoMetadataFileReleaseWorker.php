<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Remove "-dev" from the MonorepoMetadata version
 */
final class ConvertVersionForProdInMonorepoMetadataFileReleaseWorker extends AbstractConvertVersionInMonorepoMetadataFileReleaseWorker
{
    public function work(Version $version): void
    {
        // Use the incoming provided version, so it also works for a downstream monorepo
        $devVersion = $this->monorepoMetadataVersionUtils->getDevVersion();
        $replacements = [
            '/\'' . preg_quote($devVersion) . '\'/' => '\'' . $version->getVersionString() . '\'',
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
        return 'Remove "-dev" from the MonorepoMetadata version';
    }
}
