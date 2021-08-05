<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use Nette\Utils\Strings;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package\CustomPackageProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\CustomPackage;

final class PackageOwnersProvider
{
    public function __construct(
        private CustomPackageProvider $customPackageProvider,
    ) {
    }

    /**
     * To find out if it's PSR-4, check if the package has tests.
     * @param string[] $fileListFilter
     * @return string[]
     */
    public function providePackageOwners(): array
    {
        $packages = $this->customPackageProvider->provide();
        return array_values(array_unique(array_map(
            function (CustomPackage $package): string {
                return (string) Strings::before($package->getName(), '/');
            },
            $packages
        )));
    }
}
