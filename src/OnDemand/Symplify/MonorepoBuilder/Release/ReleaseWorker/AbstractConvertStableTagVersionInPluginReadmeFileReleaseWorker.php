<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors\PluginDataSourceAccessor;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;

abstract class AbstractConvertStableTagVersionInPluginReadmeFileReleaseWorker implements ReleaseWorkerInterface
{
    /** @var string[]|null */
    private ?array $pluginReadmeFiles = null;

    public function __construct(
        protected FileContentReplacerSystem $fileContentReplacerSystem,
        protected VersionUtils $versionUtils,
        protected UpstreamVersionUtils $upstreamVersionUtils,
    ) {
    }

    /**
     * @return string[]
     */
    protected function getPluginMainFiles(): array
    {
        if ($this->pluginReadmeFiles === null) {
            $pluginDataSource = $this->getPluginDataSource();
            $pluginDataSourceAccessor = new PluginDataSourceAccessor($pluginDataSource);
            $this->pluginReadmeFiles = $pluginDataSourceAccessor->getPluginMainFiles();
        }
        return $this->pluginReadmeFiles;
    }

    protected function getPluginDataSource(): PluginDataSource
    {
        return new PluginDataSource(dirname(__DIR__, 6));
    }
}
