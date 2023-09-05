<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Monorepo\MonorepoMetadata;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

/**
 * Remove "-dev" from the MonorepoMetadata version
 */
final class RemoveDevStringInMonorepoMetadataVersionReleaseWorker implements ReleaseWorkerInterface
{
    public function __construct(
        private ProcessRunner $processRunner,
        private FileContentReplacerSystem $fileContentReplacerSystem,
    ) {
    }

    public function work(Version $version): void
    {
        $file = dirname(__DIR__, 6) . '/src/Monorepo/MonorepoMetadata.php';
        $replacements = [
            '/\b' . preg_quote(MonorepoMetadata::VERSION) . '\b/' => substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev')),
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
        return 'Remove "-dev" from the MonorepoMetadata version';
    }
}
