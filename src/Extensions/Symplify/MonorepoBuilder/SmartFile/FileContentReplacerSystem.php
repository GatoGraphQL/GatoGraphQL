<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile;

use Nette\Utils\Strings;
use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\Finder\SmartFinder;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class FileContentReplacerSystem
{
    public function __construct(
        private SmartFileSystem $smartFileSystem,
        private FileSystemGuard $fileSystemGuard,
        private SmartFinder $smartFinder,
    ) {
    }

    /**
     * @param string[] $files
     * @param array<string,string> $patternReplacements a regex pattern to search, and its replacement
     */
    public function replaceContentInFiles(
        array $files,
        array $patternReplacements
    ): void {
        $smartFileInfos = array_map(
            fn (string $file) => new SmartFileInfo($file),
            $files
        );
        $this->replaceContentInSmartFileInfos($smartFileInfos, $patternReplacements);
    }

    /**
     * @param SmartFileInfo[] $smartFileInfos
     * @param array<string,string> $patternReplacements a regex pattern to search, and its replacement
     */
    public function replaceContentInSmartFileInfos(
        array $smartFileInfos,
        array $patternReplacements
    ): void {
        foreach ($smartFileInfos as $smartFileInfo) {
            $file = $smartFileInfo->getRealPath();
            $fileContent = $this->smartFileSystem->readFile($file);
            foreach ($patternReplacements as $pattern => $replacement) {
                $fileContent = Strings::replace(
                    $fileContent,
                    $pattern,
                    $replacement
                );
            }
            $this->smartFileSystem->dumpFile($file, $fileContent);
        }
    }
}
