<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors\PluginDataSourceAccessor;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\NodeJSPackageJSONUpdater;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\MonorepoMetadataVersionUtils;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symfony\Component\Finder\Finder;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractConvertVersionInPluginNodeJSPackageJSONFilesReleaseWorker implements ReleaseWorkerInterface
{
    /** @var SmartFileInfo[] */
    protected array $pluginNodeJSPackageJSONSmartFileInfos;

    public function __construct(
        protected ProcessRunner $processRunner,
        protected FileContentReplacerSystem $fileContentReplacerSystem,
        protected VersionUtils $versionUtils,
        protected UpstreamVersionUtils $upstreamVersionUtils,
        protected MonorepoMetadataVersionUtils $monorepoMetadataVersionUtils,
        protected NodeJSPackageJSONUpdater $nodeJSPackageDependencyUpdater,
        private FinderSanitizer $finderSanitizer,
    ) {
        $pluginDataSource = new PluginDataSource(dirname(__DIR__, 6));
        $pluginDataSourceAccessor = new PluginDataSourceAccessor($pluginDataSource);
        $folders = $pluginDataSourceAccessor->getPluginNodeJSPackageDirectories();
        
        $finder = new Finder();
        $finder->in($folders)
            ->directories()
            ->depth(0)
            ->sortByName();

        $directorySmartFileInfos = $this->finderSanitizer->sanitize($finder);
        $this->pluginNodeJSPackageJSONSmartFileInfos = array_map(
            fn (SmartFileInfo $smartFileInfo) => new SmartFileInfo($smartFileInfo->getRealPath() . '/package.json'),
            $directorySmartFileInfos
        );
    }
}
