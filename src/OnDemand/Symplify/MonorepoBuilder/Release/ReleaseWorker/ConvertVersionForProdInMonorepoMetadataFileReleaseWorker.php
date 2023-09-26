<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Remove "-dev" from the MonorepoMetadata version
 */
class ConvertVersionForProdInMonorepoMetadataFileReleaseWorker extends AbstractConvertVersionInMonorepoMetadataFileReleaseWorker
{
    public function work(Version $version): void
    {
        // Use the incoming provided version, so it also works for a downstream monorepo
        $replacements = [
            "/(\s+)const(\s+)VERSION(\s+)?=(\s+)?['\"][a-z0-9.-]+['\"](\s+)?;/" => " const VERSION = '" . $version->getVersionString() . "';",
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
