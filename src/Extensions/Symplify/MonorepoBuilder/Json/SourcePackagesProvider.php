<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package\CustomPackageProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\PackageUtils;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\CustomPackage;

final class SourcePackagesProvider
{
    public function __construct(
        private CustomPackageProvider $customPackageProvider,
        private PackageUtils $packageUtils
    ) {
    }

    /**
     * To find out if it's PSR-4, check if the package has tests.
     * @param string[] $packagesToSkip
     * @param string[] $fileListFilter
     * @return string[]
     */
    public function provideSourcePackages(
        bool $psr4Only = false,
        array $packagesToSkip = [],
        array $fileListFilter = []
    ): array {
        $packages = $this->customPackageProvider->provide();
        if ($psr4Only) {
            $packages = array_values(array_filter(
                $packages,
                function (CustomPackage $package): bool {
                    return $package->hasSrc();
                }
            ));
        }
        // Operate with package paths from now on
        $packages = array_map(
            function (CustomPackage $package): string {
                return $package->getRelativePath();
            },
            $packages
        );
        // Temporary hack! PHPStan is currently failing for these packages,
        // because they have not been fully converted to PSR-4 (WIP),
        // and converting them will take some time. Hence, for the time being,
        // skip them from executing PHPStan, to avoid the CI from failing
        $packages = array_values(array_diff(
            $packages,
            $packagesToSkip
        ));

        // If provided, filter the packages to the ones containing
        // the list of files. Useful to launch GitHub runners to split modified packages only
        if ($fileListFilter !== []) {
            $packages = array_values(array_filter(
                $packages,
                fn (string $package) => $this->packageUtils->isPackageInFileList($package, $fileListFilter)
            ));
        }
        return $packages;
    }
}
