<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ComposerReplaceEntriesRemover
{
    public function __construct(private JsonFileManager $jsonFileManager)
    {
    }

    /**
     * @param string[] $excludeReplacePackageNames
     */
    public function removeReplaceEntries(
        string $composerPath,
        array $excludeReplacePackageNames
    ): void {
        $composerJSON = $this->jsonFileManager->loadFromFilePath($composerPath);

        /** @var array<string,string> */
        $composerReplaceEntries = $composerJSON[ComposerJsonSection::REPLACE] ?? [];

        // Skip removing "exclude_replace" entries
        $composerReplaceEntries = array_values(array_filter(
            $composerReplaceEntries,
            fn (string $packageName) => in_array($packageName, $excludeReplacePackageNames),
            ARRAY_FILTER_USE_KEY
        ));

        $composerJSON[ComposerJsonSection::REPLACE] = $composerReplaceEntries;
        $this->jsonFileManager->printJsonToFileInfo($composerJSON, new SmartFileInfo($composerPath));
    }
}
