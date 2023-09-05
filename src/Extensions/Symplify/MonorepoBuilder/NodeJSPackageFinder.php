<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Symfony\Component\Finder\Finder;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

final class NodeJSPackageFinder
{
    public function __construct(
        private FinderSanitizer $finderSanitizer,
    ) {
    }

    /**
     * @param string[] $folders
     * @return SmartFileInfo[] $smartFileInfos
     */
    public function findPackageJSONFileInfos(
        array $folders,
    ): array {
        $finder = new Finder();
        $finder->in($folders)
            ->directories()
            ->depth(0)
            ->sortByName();

        $directorySmartFileInfos = $this->finderSanitizer->sanitize($finder);
        return array_map(
            fn (SmartFileInfo $smartFileInfo) => new SmartFileInfo($smartFileInfo->getRealPath() . '/package.json'),
            $directorySmartFileInfos
        );
    }
}
