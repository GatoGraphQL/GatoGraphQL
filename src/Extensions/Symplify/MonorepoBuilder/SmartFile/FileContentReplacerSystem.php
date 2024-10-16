<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\SmartFile;

use Nette\Utils\Strings;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class FileContentReplacerSystem
{
    public function __construct(
        private SmartFileSystem $smartFileSystem,
    ) {
    }

    /**
     * @param string[] $files
     * @param array<string,string> $stringOrRegexPatternReplacements a string or regex pattern to search, and its replacement
     */
    public function replaceContentInFiles(
        array $files,
        array $stringOrRegexPatternReplacements,
        bool $useRegex
    ): void {
        foreach ($files as $file) {
            $fileContent = $this->smartFileSystem->readFile($file);
            foreach ($stringOrRegexPatternReplacements as $stringOrRegexPattern => $replacement) {
                if ($useRegex) {
                    $fileContent = Strings::replace(
                        $fileContent,
                        $stringOrRegexPattern,
                        $replacement
                    );
                } else {
                    $fileContent = str_replace(
                        $stringOrRegexPattern,
                        $replacement,
                        $fileContent
                    );
                }
            }
            $this->smartFileSystem->dumpFile($file, $fileContent);
        }
    }

    /**
     * @param array<string,string> $stringOrRegexPatternReplacements a string or regex pattern to search, and its replacement
     */
    public function replaceContent(
        string $fileContent,
        array $stringOrRegexPatternReplacements,
        bool $useRegex
    ): string {
        foreach ($stringOrRegexPatternReplacements as $stringOrRegexPattern => $replacement) {
            if ($useRegex) {
                $fileContent = Strings::replace(
                    $fileContent,
                    $stringOrRegexPattern,
                    $replacement
                );
            } else {
                $fileContent = str_replace(
                    $stringOrRegexPattern,
                    $replacement,
                    $fileContent
                );
            }
        }
        return $fileContent;
    }

    /**
     * @param SmartFileInfo[] $smartFileInfos
     * @param array<string,string> $stringOrRegexPatternReplacements a string or regex pattern to search, and its replacement
     */
    public function replaceContentInSmartFileInfos(
        array $smartFileInfos,
        array $stringOrRegexPatternReplacements,
        bool $useRegex
    ): void {
        $files = array_map(
            fn (SmartFileInfo $smartFileInfo) => $smartFileInfo->getRealPath(),
            $smartFileInfos
        );
        $this->replaceContentInFiles($files, $stringOrRegexPatternReplacements, $useRegex);
    }
}
