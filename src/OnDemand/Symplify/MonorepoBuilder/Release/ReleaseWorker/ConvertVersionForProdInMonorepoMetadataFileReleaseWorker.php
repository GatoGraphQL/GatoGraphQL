<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Set the PROD version (release tag) on the MonorepoMetadata (and remove "-dev")
 */
class ConvertVersionForProdInMonorepoMetadataFileReleaseWorker extends AbstractConvertVersionInMonorepoMetadataFileReleaseWorker
{
    public function work(Version $version): void
    {
        // Use the incoming provided version, so it also works for a downstream monorepo
        $replacements = [
            "/(\s+)const(\s+)VERSION(\s+)?=(\s+)?['\"][a-z0-9.-]+['\"](\s+)?;/" => " const VERSION = '" . $version->getVersionString() . "';",
            "/(\s+)const(\s+)LATEST_PROD_VERSION(\s+)?=(\s+)?['\"].*['\"](\s+)?;/" => " const LATEST_PROD_VERSION = '" . $version->getVersionString() . "';",
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles(
            [
                $this->monorepoMetadataFile,
            ],
            $replacements,
            true,
        );
    }

    public function getDescription(Version $version): string
    {
        return 'Set the PROD version (release tag) on the MonorepoMetadata (and remove "-dev")';
    }
}
