<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors\PluginDataSourceAccessor;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\BlockCompiledMarkdownFileFinder;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use PoP\PoP\Monorepo\MonorepoStaticHelpers;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractConvertVersionInPluginBlockCompiledMarkdownFilesReleaseWorker implements ReleaseWorkerInterface
{
    /** @var string[]|null */
    private ?array $pluginBlockCompiledMarkdownFiles = null;
    protected string $branchName;

    public function __construct(
        protected FileContentReplacerSystem $fileContentReplacerSystem,
        protected VersionUtils $versionUtils,
        protected UpstreamVersionUtils $upstreamVersionUtils,
        protected BlockCompiledMarkdownFileFinder $blockCompiledMarkdownFileFinder,
        ParameterProvider $parameterProvider
    ) {
        $this->branchName = $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
    }

    /**
     * @return string[]
     */
    protected function getPluginBlockCompiledMarkdownFiles(): array
    {
        if ($this->pluginBlockCompiledMarkdownFiles === null) {
            $pluginDataSource = $this->getPluginDataSource();
            $pluginDataSourceAccessor = new PluginDataSourceAccessor($pluginDataSource);
            $folders = $pluginDataSourceAccessor->getPluginNodeJSPackageDirectories();
            $compiledMarkdownFileInfos = $this->blockCompiledMarkdownFileFinder->findCompiledMarkdownFileInfos($folders);
            $this->pluginBlockCompiledMarkdownFiles = array_map(
                fn (SmartFileInfo $smartFileInfo) => $smartFileInfo->getRealPath(),
                $compiledMarkdownFileInfos
            );
        }
        return $this->pluginBlockCompiledMarkdownFiles;
    }

    protected function getPluginDataSource(): PluginDataSource
    {
        return new PluginDataSource(dirname(__DIR__, 6));
    }

    protected function getGitHubRepoDocsRootURL(): string
    {
        return MonorepoStaticHelpers::getGitHubRepoDocsRootURL();
    }
}
