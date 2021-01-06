<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\MonorepoBuilder\Package\PackageProvider;
use Symplify\MonorepoBuilder\ValueObject\Package;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PackageCodePathsJsonProvider
{
    /**
     * @var PackageProvider
     */
    private $packageProvider;

    /**
     * @var array<string, mixed[]>
     */
    private $packageOrganizations = [];

    public function __construct(
        PackageProvider $packageProvider,
        ParameterProvider $parameterProvider
    ) {
        $this->packageProvider = $packageProvider;
        $this->packageOrganizations = $parameterProvider->provideArrayParameter(Option::PACKAGE_ORGANIZATIONS);
    }

    /**
     * Code paths are src/ and tests/ folders.
     * But not all packages have them, so check if these folders exist.
     * Since a Package already has function `hasTests`, and since every
     * package that has tests/ also has src/, then using this function
     * already does the job.
     * @return array<string[]>
     */
    public function providePackageCodePaths(): array
    {
        $packageCodePaths = [];
        $packagesWithTests = array_filter(
            $this->packageProvider->provide(),
            function (Package $package): bool {
                return $package->hasTests();
            }
        );
        foreach ($packagesWithTests as $package) {
            $packageRelativePath = $package->getRelativePath();
            $packageCodePaths[] = $packageRelativePath . DIRECTORY_SEPARATOR . 'src';
            $packageCodePaths[] = $packageRelativePath . DIRECTORY_SEPARATOR . 'tests';
        }

        return $packageCodePaths;
    }
}
