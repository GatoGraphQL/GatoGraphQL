<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use PoP\PoP\Monorepo\MonorepoMetadata;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

/**
 * Update the MonorepoMetadata version to the new one, and add "-dev" again
 */
final class BumpVersionForDevInMonorepoMetadataVersionReleaseWorker implements ReleaseWorkerInterface
{
    public function __construct(
        private FileContentReplacerSystem $fileContentReplacerSystem,
        private VersionUtils $versionUtils
    ) {
    }

    public function work(Version $version): void
    {
        $nextVersionInString = $this->versionUtils->getNextVersion($version);
        $file = dirname(__DIR__, 6) . '/src/Monorepo/MonorepoMetadata.php';
        $replacements = [
            '/\b' . preg_quote(substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev'))) . '\b/' => $nextVersionInString . '-dev',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles(
            [
                $file,
            ],
            $replacements,
        );
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the plugin to the next version, addding again "-dev" in it, in the MonorepoMetadata version';
    }
}
