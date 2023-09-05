<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Remove "-dev" from the plugin version
 */
final class ConvertVersionForProdInPluginMainFileReleaseWorker extends AbstractConvertVersionInPluginMainFileReleaseWorker
{
    public function work(Version $version): void
    {
        $devVersion = $this->monorepoMetadataVersionUtils->getDevVersion();
        $prodVersion = $version->getVersionString();
        $replacements = [
            // WordPress plugin header
            '/Version:\s+[a-z0-9.-]+/' => 'Version: ' . $prodVersion,
            // Gato GraphQL plugin version in a variable
            '/\'' . preg_quote($devVersion) . '\'/' => '\'' . $prodVersion . '\'',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginMainFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the plugin main file';
    }
}
