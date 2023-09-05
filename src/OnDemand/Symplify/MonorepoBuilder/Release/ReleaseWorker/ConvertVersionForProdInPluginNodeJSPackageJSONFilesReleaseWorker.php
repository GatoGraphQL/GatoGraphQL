<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Remove "-dev" from the plugin version
 */
final class ConvertVersionForProdInPluginNodeJSPackageJSONFilesReleaseWorker extends AbstractConvertVersionInPluginNodeJSPackageJSONFilesReleaseWorker
{
    public function work(Version $version): void
    {
        $devVersion = $this->monorepoMetadataVersionUtils->getDevVersion();
        $prodVersion = $this->monorepoMetadataVersionUtils->getProdVersion();
        $replacements = [
            // package.json "version"
            '/"version":\s+"' . preg_quote($devVersion) . '"/' => '"version": "' . $prodVersion . '"'
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginNodeJSPackageJSONFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the package.json files';
    }
}
