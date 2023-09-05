<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * Remove "-dev" from the plugin version
 */
final class ConvertVersionForProdInPluginNodeJSPackageJSONFilesReleaseWorker extends AbstractConvertVersionInPluginNodeJSPackageJSONFilesReleaseWorker
{
    public function work(Version $version): void
    {
        $prodVersion = $this->monorepoMetadataVersionUtils->getProdVersion();

        $smartFileInfos = array_map(
            fn (string $file) => new SmartFileInfo($file),
            $this->pluginNodeJSPackageJSONFiles
        );
        foreach ($smartFileInfos as $smartFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($smartFileInfo);
            $json['version'] = $prodVersion;
            $this->jsonFileManager->printJsonToFileInfo($json, $smartFileInfo);
        }
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the package.json files';
    }
}
