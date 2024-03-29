<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors\PluginDataSourceAccessor;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\NodeJSPackageFinder;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\NodeJSPackageJSONUpdater;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractConvertVersionInPluginNodeJSPackageJSONFilesReleaseWorker implements ReleaseWorkerInterface
{
    /** @var SmartFileInfo[]|null */
    private ?array $pluginNodeJSPackageJSONSmartFileInfos = null;

    public function __construct(
        protected VersionUtils $versionUtils,
        protected UpstreamVersionUtils $upstreamVersionUtils,
        protected NodeJSPackageJSONUpdater $nodeJSPackageDependencyUpdater,
        private NodeJSPackageFinder $nodeJSPackageFinder,
    ) {
    }

    /**
     * @return SmartFileInfo[]
     */
    protected function getPluginNodeJSPackageJSONSmartFileInfos(): array
    {
        if ($this->pluginNodeJSPackageJSONSmartFileInfos === null) {
            $pluginDataSource = $this->getPluginDataSource();
            $pluginDataSourceAccessor = new PluginDataSourceAccessor($pluginDataSource);
            $folders = $pluginDataSourceAccessor->getPluginNodeJSPackageDirectories();
            $this->pluginNodeJSPackageJSONSmartFileInfos = $this->nodeJSPackageFinder->findPackageJSONFileInfos($folders);
        }
        return $this->pluginNodeJSPackageJSONSmartFileInfos;
    }

    protected function getPluginDataSource(): PluginDataSource
    {
        return new PluginDataSource(dirname(__DIR__, 6));
    }
}
