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
     * @param string[] $excludeReplacePackageNames
     */
    public function moveReplaceEntriesFromPluginsToBundle(
        array $smartFileInfos,
        string $bundleComposerPath,
        array $excludeReplacePackageNames
    ): void {
        $bundleComposerJSON = $this->jsonFileManager->loadFromFilePath($bundleComposerPath);

        // From the bundle composer.json, retrieve its dependencies
        $bundleComposerRequirePackageNames = array_keys($bundleComposerJSON[ComposerJsonSection::REQUIRE] ?? []);

        // If it has any "replace" already, keep them
        $bundleComposerReplaceEntries = $bundleComposerJSON[ComposerJsonSection::REPLACE] ?? [];

        // Iterate all the packages and process those which are dependencies of the bundle
        foreach ($smartFileInfos as $packageComposerFileInfo) {
            $packageComposerJSON = $this->jsonFileManager->loadFromFileInfo($packageComposerFileInfo);
            /** @var string */
            $packageComposerName = $packageComposerJSON[ComposerJsonSection::NAME];

            // Check the composer.json is from a contained plugin in the bundle
            if (!in_array($packageComposerName, $bundleComposerRequirePackageNames)) {
                continue;
            }

            // Check it has any "replace" entry
            if (!isset($packageComposerJSON[ComposerJsonSection::REPLACE])) {
                continue;
            }

            // Transfer the "replace" entries, and remove them from the plugin composer.json
            // Because this array is `key => value`, no need to do `array_unique`
            $bundleComposerReplaceEntries = array_merge(
                $bundleComposerReplaceEntries,
                // Skip adding "exclude_replace" entries
                array_filter(
                    $packageComposerJSON[ComposerJsonSection::REPLACE],
                    fn (string $packageName) => !in_array($packageName, $excludeReplacePackageNames),
                    ARRAY_FILTER_USE_KEY
                )
            );
            unset($packageComposerJSON[ComposerJsonSection::REPLACE]);
            $this->jsonFileManager->printJsonToFileInfo($packageComposerJSON, $packageComposerFileInfo);
        }

        $bundleComposerJSON[ComposerJsonSection::REPLACE] = $bundleComposerReplaceEntries;
        $this->jsonFileManager->printJsonToFileInfo($bundleComposerJSON, new SmartFileInfo($bundleComposerPath));
    }
}
