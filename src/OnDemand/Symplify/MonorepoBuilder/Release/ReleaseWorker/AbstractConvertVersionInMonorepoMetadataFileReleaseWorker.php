<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

abstract class AbstractConvertVersionInMonorepoMetadataFileReleaseWorker implements ReleaseWorkerInterface
{
    protected string $monorepoMetadataFile;

    public function __construct(
        protected FileContentReplacerSystem $fileContentReplacerSystem,
        protected VersionUtils $versionUtils,
    ) {
        $this->monorepoMetadataFile = $this->getMonorepoMetadataFile();
    }

    protected function getMonorepoMetadataFile(): string
    {
        return dirname(__DIR__, 6) . '/src/Monorepo/MonorepoMetadata.php';
    }
}
