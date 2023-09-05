<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors\PluginDataSourceAccessor;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\BlockCompiledMarkdownFileFinder;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\MonorepoMetadataVersionUtils;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractConvertVersionInPluginBlockCompiledMarkdownFilesReleaseWorker implements ReleaseWorkerInterface
{
    /** @var string[] */
    protected array $pluginBlockCompiledMarkdownFiles;

    public function __construct(
        protected FileContentReplacerSystem $fileContentReplacerSystem,
        protected VersionUtils $versionUtils,
        protected UpstreamVersionUtils $upstreamVersionUtils,
        protected MonorepoMetadataVersionUtils $monorepoMetadataVersionUtils,
        protected BlockCompiledMarkdownFileFinder $blockCompiledMarkdownFileFinder,
    ) {
        $pluginDataSource = new PluginDataSource(dirname(__DIR__, 6));
        $pluginDataSourceAccessor = new PluginDataSourceAccessor($pluginDataSource);
        $folders = $pluginDataSourceAccessor->getPluginNodeJSPackageDirectories();
        $compiledMarkdownFileInfos = $this->blockCompiledMarkdownFileFinder->findCompiledMarkdownFileInfos($folders);
        $this->pluginBlockCompiledMarkdownFiles = array_map(
            fn (SmartFileInfo $smartFileInfo) => $smartFileInfo->getRealPath(),
            $compiledMarkdownFileInfos
        );
    }
}
