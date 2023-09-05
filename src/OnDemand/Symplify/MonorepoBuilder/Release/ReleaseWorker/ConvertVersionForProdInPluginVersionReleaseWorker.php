<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoMetadata;

/**
 * Remove "-dev" from the plugin version
 */
final class ConvertVersionForProdInPluginVersionReleaseWorker extends AbstractConvertVersionInPluginVersionReleaseWorker
{
    public function work(Version $version): void
    {
        $replacements = [
            '/\b' . preg_quote(MonorepoMetadata::VERSION) . '\b/' => substr(MonorepoMetadata::VERSION, 0, strlen(MonorepoMetadata::VERSION) - strlen('-dev')),
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the plugin main file';
    }
}
