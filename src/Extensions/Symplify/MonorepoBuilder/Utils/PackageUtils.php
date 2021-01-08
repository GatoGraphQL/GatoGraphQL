<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils;

final class PackageUtils
{
    /**
     * @var string[] $fileListFilter
     * @return array<array<string,string>>
     */
    public function isPackageInFileList(string $package, array $fileListFilter): bool
    {
        $matchingPackages = array_filter(
            $fileListFilter,
            fn (string $file) => str_starts_with($file, $package)
        );
        return count($matchingPackages) > 0;
    }
}
