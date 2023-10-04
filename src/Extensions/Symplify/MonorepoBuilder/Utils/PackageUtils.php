<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils;

final class PackageUtils
{
    /**
     * @param string[] $fileListFilter
     */
    public function isPackageInFileList(string $package, array $fileListFilter): bool
    {
        // Make sure the package ends with "/". Otherwise,
        // file `api-clients/README.md` produces not just `api-clients`
        // but also `api`
        $package .= str_ends_with($package, '/') ? '' : '/';
        $matchingFiles = array_filter(
            $fileListFilter,
            fn (string $file) => str_starts_with($file, $package)
        );
        return count($matchingFiles) > 0;
    }

    /**
     * @param string[] $pathListFilter
     */
    public function doesPackageContainAnyPath(string $package, array $pathListFilter): bool
    {
        $matchingPaths = array_filter(
            $pathListFilter,
            fn (string $path) => str_starts_with($package, $path)
        );
        return count($matchingPaths) > 0;
    }
}
