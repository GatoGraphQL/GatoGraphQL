<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Bump to the next plugin version adding "-dev"
 */
final class BumpVersionForDevInPluginNodeJSPackageJSONFilesReleaseWorker extends AbstractConvertVersionInPluginNodeJSPackageJSONFilesReleaseWorker
{
    public function work(Version $version): void
    {
        $nextDevVersion = $this->versionUtils->getNextVersion($version) . '-dev';
        $this->nodeJSPackageDependencyUpdater->updatePackageJSONFileInfosWithVersion(
            $this->pluginNodeJSPackageJSONSmartFileInfos,
            $nextDevVersion
        );
    }

    public function getDescription(Version $version): string
    {
        return 'Bump the package.json file to the next version, addding again "-dev" in it';
    }
}
