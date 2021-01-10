<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\PackageUtils;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package\CustomPackageProvider;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PackageEntriesJsonProvider
{
    private CustomPackageProvider $customPackageProvider;
    private PackageUtils $packageUtils;

    /**
     * @var array<string, string>
     */
    private array $packageOrganizations = [];

    public function __construct(
        CustomPackageProvider $customPackageProvider,
        ParameterProvider $parameterProvider,
        PackageUtils $packageUtils
    ) {
        $this->customPackageProvider = $customPackageProvider;
        $this->packageOrganizations = $parameterProvider->provideArrayParameter(Option::PACKAGE_ORGANIZATIONS);
        $this->packageUtils = $packageUtils;
    }

    /**
     * @param string[] $fileListFilter
     * @return array<array<string,string>>
     */
    public function providePackageEntries(array $fileListFilter = []): array
    {
        $packageEntries = [];
        $packages = $this->customPackageProvider->provide();
        foreach ($packages as $package) {
            $packageRelativePath = $package->getRelativePath();
            // If provided, filter the packages to the ones containing the list of files.
            // Useful to launch GitHub runners to split modified packages only
            if ($fileListFilter !== [] && !$this->packageUtils->isPackageInFileList($packageRelativePath, $fileListFilter)) {
                continue;
            }
            $packageDirectory = dirname($packageRelativePath);
            $organization = $this->packageOrganizations[$packageDirectory] ?? null;
            if ($organization === null) {
                throw new ShouldNotHappenException(sprintf(
                    "The organization has not been set for package dir '%s' (when processing package '%s')",
                    $packageDirectory,
                    $packageRelativePath
                ));
            }
            $packageEntries[] = [
                'name' => $package->getShortName(),
                'path' => $packageRelativePath,
                'organization' => $organization,
            ];
        }

        return $packageEntries;
    }
}
