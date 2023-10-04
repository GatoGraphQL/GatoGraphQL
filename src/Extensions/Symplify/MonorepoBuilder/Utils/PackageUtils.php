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
        $matchingPackages = array_filter(
            $fileListFilter,
            fn (string $file) => str_starts_with($file, $package)
        );
        return count($matchingPackages) > 0;
    }

    /**
     * @param string[] $pathListFilter
     */
    public function isPackageInPathList(string $package, array $pathListFilter): bool
    {
        $matchingPackages = array_filter(
            $pathListFilter,
            fn (string $file) => str_starts_with($file, $package)
        );
        return count($matchingPackages) > 0;
    }
}
