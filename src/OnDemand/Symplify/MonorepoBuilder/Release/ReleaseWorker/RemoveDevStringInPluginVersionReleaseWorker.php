<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile\FileContentReplacerSystem;
use PoP\PoP\Monorepo\MonorepoMetadata;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class RemoveDevStringInPluginVersionReleaseWorker implements ReleaseWorkerInterface
{
    /** @var string[] */
    private array $pluginFiles;

    public function __construct(
        private ProcessRunner $processRunner,
        private FileContentReplacerSystem $fileContentReplacerSystem,
    ) {
        $pluginDataSource = new PluginDataSource(dirname(__DIR__, 6));
        $this->pluginFiles = $pluginDataSource->getPluginFiles();
    }

    /**
     * Remove "-dev" from the plugin version
     */
    public function work(Version $version): void
    {
        $replacements = [
            '/' . preg_quote(MonorepoMetadata::VERSION) . '/' => substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev')),
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the plugin main file';
    }
}
