<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors\PluginDataSourceAccessor;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\MonorepoMetadataVersionUtils;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;

abstract class AbstractConvertVersionInPluginVersionReleaseWorker implements ReleaseWorkerInterface
{
    /** @var string[] */
    protected array $pluginFiles;

    public function __construct(
        protected ProcessRunner $processRunner,
        protected FileContentReplacerSystem $fileContentReplacerSystem,
        protected VersionUtils $versionUtils,
        protected UpstreamVersionUtils $upstreamVersionUtils,
        protected MonorepoMetadataVersionUtils $monorepoMetadataVersionUtils,
    ) {
        $pluginDataSource = new PluginDataSource(dirname(__DIR__, 6));
        $pluginDataSourceAccessor = new PluginDataSourceAccessor($pluginDataSource);
        $this->pluginFiles = $pluginDataSourceAccessor->getPluginFiles();
    }
}
