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
        $devVersion = $this->monorepoMetadataVersionUtils->getDevVersion();
        $prodVersion = $this->monorepoMetadataVersionUtils->getProdVersion();
        $replacements = [
            // WordPress plugin header
            '/Version:\s+' . preg_quote($devVersion) . '/' => 'Version: ' . $prodVersion,
            // Gato GraphQL plugin version in a variable
            '/\'' . preg_quote($devVersion) . '\'/' => '\'' . $prodVersion . '\'',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the plugin main file';
    }
}
