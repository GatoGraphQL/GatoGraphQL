<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use Symplify\MonorepoBuilder\Package\PackageProvider;
use Symplify\MonorepoBuilder\ValueObject\Package;

final class PackageCodePathsProvider
{
    /**
     * @var PackageProvider
     */
    private $packageProvider;

    public function __construct(
        PackageProvider $packageProvider
    ) {
        $this->packageProvider = $packageProvider;
    }

    /**
     * Code paths are src/ and tests/ folders.
     * But not all packages have them, so check if these folders exist.
     * Since a Package already has function `hasTests`, and since every
     * package that has tests/ also has src/, then using this function
     * already does the job.
     * @return string[]
     */
    public function providePackageCodePaths(): array
    {
        $packageCodePaths = [];
        $packagesWithCode = array_values(array_filter(
            $this->packageProvider->provide(),
            function (Package $package): bool {
                return $package->hasTests();
            }
        ));
        foreach ($packagesWithCode as $package) {
            $packageRelativePath = $package->getRelativePath();
            $packageCodePaths[] = $packageRelativePath . DIRECTORY_SEPARATOR . 'src';
            $packageCodePaths[] = $packageRelativePath . DIRECTORY_SEPARATOR . 'tests';
        }

        return $packageCodePaths;
    }
}
