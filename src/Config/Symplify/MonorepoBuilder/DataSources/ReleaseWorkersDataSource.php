<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class ReleaseWorkersDataSource
{
    /**
     * @return string[]
     */
    public function getReleaseWorkerClasses(): array
    {
        return [
            UpdateReplaceReleaseWorker::class,
            SetCurrentMutualDependenciesReleaseWorker::class,
            AddTagToChangelogReleaseWorker::class,
            TagVersionReleaseWorker::class,
            PushTagReleaseWorker::class,
            SetNextMutualDependenciesReleaseWorker::class,
            UpdateBranchAliasReleaseWorker::class,
            PushNextDevReleaseWorker::class,
        ];
    }
}
