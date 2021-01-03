<?php

declare(strict_types=1);

namespace PoP\PoP\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\MonorepoBuilder\Package\PackageProvider;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PackageEntriesJsonProvider
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
     * @return array<string[]>
     */
    public function providePackageEntries(): array
    {
        $packageEntries = [];
        foreach ($this->packageProvider->provide() as $package) {
            $packageRelativePath = $package->getRelativePath();
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
