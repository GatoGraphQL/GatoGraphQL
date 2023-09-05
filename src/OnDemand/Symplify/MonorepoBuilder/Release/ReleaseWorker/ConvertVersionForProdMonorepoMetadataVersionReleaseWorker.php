<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoMetadata;

/**
 * Remove "-dev" from the MonorepoMetadata version
 */
final class ConvertVersionForProdMonorepoMetadataVersionReleaseWorker extends AbstractConvertVersionInMonorepoMetadataVersionReleaseWorker
{
    public function work(Version $version): void
    {
        // The MonorepoMetadata version currently contains "-dev" at the end
        $devVersion = MonorepoMetadata::VERSION;
        $prodVersion = substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev'));
        $replacements = [
            '/\'' . preg_quote($devVersion) . '\'/' => '\'' . $prodVersion . '\'',
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
