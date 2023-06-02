<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ComposerReplaceEntriesRelocator
{
    public function __construct(private JsonFileManager $jsonFileManager)
    {
    }

    /**
     * @param SmartFileInfo[] $smartFileInfos
     */
    public function moveReplaceEntriesFromPluginsToBundle(
        array $smartFileInfos,
        string $bundleComposerPath
    ): void {
        // From the bundle composer.json, retrieve its dependencies
        $bundleComposerJSON = $this->jsonFileManager->loadFromFilePath($bundleComposerPath);
        $bundleComposerRequirePackageNames = array_keys($bundleComposerJSON[ComposerJsonSection::REQUIRE] ?? []);
        $pluginComposerReplacePackageNames = $bundleComposerJSON[ComposerJsonSection::REPLACE] ?? [];
        foreach ($smartFileInfos as $packageComposerFileInfo) {
            $packageComposerJSON = $this->jsonFileManager->loadFromFileInfo($packageComposerFileInfo);
            /** @var string */
            $packageComposerName = $packageComposerJSON[ComposerJsonSection::NAME];
            
            // Check the composer.json is from a contained plugin in the bundle
            if (!in_array($packageComposerName, $bundleComposerRequirePackageNames)) {
                continue;
            }

            if (!isset($packageComposerJSON[ComposerJsonSection::REPLACE])) {
                continue;
            }

            // Transfer the "replace" entries, and remove them from the plugin composer.json
            $pluginComposerReplacePackageNames = array_merge(
                $pluginComposerReplacePackageNames,
                $packageComposerJSON[ComposerJsonSection::REPLACE]
            );
            unset($packageComposerJSON[ComposerJsonSection::REPLACE]);
            $this->jsonFileManager->printJsonToFileInfo($packageComposerJSON, $packageComposerFileInfo);
        }

        // Because this array is `key => value`, no need to do `array_unique`
        $bundleComposerJSON[ComposerJsonSection::REPLACE] = $pluginComposerReplacePackageNames;
        $this->jsonFileManager->printJsonToFileInfo($bundleComposerJSON, new SmartFileInfo($bundleComposerPath));
    }
}
