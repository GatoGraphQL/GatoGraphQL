<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Symfony\Component\Finder\Finder;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

final class BlockCompiledMarkdownFileFinder
{
    public function __construct(
        private FinderSanitizer $finderSanitizer,
    ) {
    }

    /**
     * Search for all files ending in "-md.js"
     *
     * @param string[] $folders
     * @return SmartFileInfo[] $smartFileInfos
     */
    public function findCompiledMarkdownFileInfos(
        array $folders,
    ): array {
        // First find all the build folders
        $finder = new Finder();
        $finder->in($folders)
            ->directories()
            ->depth(0)
            ->sortByName();
        $directorySmartFileInfos = $this->finderSanitizer->sanitize($finder);
        $buildDirectories = array_map(
            fn (SmartFileInfo $smartFileInfo) => $smartFileInfo->getRealPath() . '/build',
            $directorySmartFileInfos
        );

        // Then find all the "*-md.js" files in the build folders
        $finder = new Finder();
        $finder->in($buildDirectories)
            ->files()
            ->name('*-md.js');

        return $this->finderSanitizer->sanitize($finder);
    }
}
