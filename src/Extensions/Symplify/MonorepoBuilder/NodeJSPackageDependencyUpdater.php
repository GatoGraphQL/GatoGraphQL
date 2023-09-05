<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\SmartFileSystem\SmartFileInfo;

final class NodeJSPackageDependencyUpdater
{
    public function __construct(private JsonFileManager $jsonFileManager)
    {
    }

    /**
     * @param SmartFileInfo[] $smartFileInfos
     * @param string[] $packageNames
     */
    public function updatePackageJSONFileInfosWithVersion(
        array $smartFileInfos,
        string $version
    ): void {
        foreach ($smartFileInfos as $smartFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($smartFileInfo);
            $json['version'] = $version;
            $this->jsonFileManager->printJsonToFileInfo($json, $smartFileInfo);
        }
    }
}
