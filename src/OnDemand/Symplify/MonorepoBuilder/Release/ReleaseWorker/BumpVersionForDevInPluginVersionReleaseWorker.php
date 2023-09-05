<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors\PluginDataSourceAccessor;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use PoP\PoP\Monorepo\MonorepoMetadata;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

/**
 * Update the plugin version to the new one, and add "-dev" again
 */
final class BumpVersionForDevInPluginVersionReleaseWorker implements ReleaseWorkerInterface
{
    /** @var string[] */
    private array $pluginFiles;

    public function __construct(
        private ProcessRunner $processRunner,
        private FileContentReplacerSystem $fileContentReplacerSystem,
        private VersionUtils $versionUtils
    ) {
        $pluginDataSource = new PluginDataSource(dirname(__DIR__, 6));
        $pluginDataSourceAccessor = new PluginDataSourceAccessor($pluginDataSource);
        $this->pluginFiles = $pluginDataSourceAccessor->getPluginFiles();
    }

    public function work(Version $version): void
    {
        $nextVersionInString = $this->versionUtils->getNextVersion($version);
        $replacements = [
            '/\b' . preg_quote(substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev'))) . '\b/' => $nextVersionInString . '-dev',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the plugin to the next version, addding again "-dev" in it, in the plugin main file';
    }
}
