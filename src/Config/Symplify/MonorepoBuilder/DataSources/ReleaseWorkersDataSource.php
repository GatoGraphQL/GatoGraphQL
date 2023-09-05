<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\BumpVersionForDevInMonorepoMetadataFileReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\BumpVersionForDevInPluginMainFileReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\BumpVersionForDevInPluginNodeJSPackageJSONFilesReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\GuardOnDefaultBranchReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\ConvertVersionForProdInMonorepoMetadataFileReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\ConvertVersionForProdInPluginBlockCompiledMarkdownFilesReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\ConvertVersionForProdInPluginMainFileReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\ConvertVersionForProdInPluginNodeJSPackageJSONFilesReleaseWorker;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker\RestoreVersionForDevInPluginBlockCompiledMarkdownFilesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualConflictsReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

class ReleaseWorkersDataSource
{
    /**
     * @return string[]
     */
    public function getReleaseWorkerClasses(): array
    {
        return [
            GuardOnDefaultBranchReleaseWorker::class,

            // Remove "-dev" from the version
            ConvertVersionForProdInPluginMainFileReleaseWorker::class,
            ConvertVersionForProdInPluginNodeJSPackageJSONFilesReleaseWorker::class,
            ConvertVersionForProdInPluginBlockCompiledMarkdownFilesReleaseWorker::class,
            ConvertVersionForProdInMonorepoMetadataFileReleaseWorker::class,
            
            // Default workers
            UpdateReplaceReleaseWorker::class,
            SetCurrentMutualConflictsReleaseWorker::class,
            SetCurrentMutualDependenciesReleaseWorker::class,
            TagVersionReleaseWorker::class,
            PushTagReleaseWorker::class,
            SetNextMutualDependenciesReleaseWorker::class,
            UpdateBranchAliasReleaseWorker::class,

            // Add "-dev" again to the version
            BumpVersionForDevInMonorepoMetadataFileReleaseWorker::class,
            RestoreVersionForDevInPluginBlockCompiledMarkdownFilesReleaseWorker::class,
            BumpVersionForDevInPluginNodeJSPackageJSONFilesReleaseWorker::class,
            BumpVersionForDevInPluginMainFileReleaseWorker::class,
            
            // Default workers
            PushNextDevReleaseWorker::class,
        ];
    }
}
