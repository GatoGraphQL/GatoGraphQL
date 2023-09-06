<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Remove "-dev" from the plugin version
 */
class ConvertVersionForProdInPluginNodeJSPackageJSONFilesReleaseWorker extends AbstractConvertVersionInPluginNodeJSPackageJSONFilesReleaseWorker
{
    public function work(Version $version): void
    {
        $prodVersion = $version->getVersionString();
        $this->nodeJSPackageDependencyUpdater->updatePackageJSONFileInfosWithVersion(
            $this->getPluginNodeJSPackageJSONSmartFileInfos(),
            $prodVersion
        );
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the package.json files';
    }
}
