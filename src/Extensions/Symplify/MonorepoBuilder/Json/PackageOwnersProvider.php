<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use Nette\Utils\Strings;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package\CustomPackageProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\CustomPackage;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;

final class PackageOwnersProvider
{
    public function __construct(
        private CustomPackageProvider $customPackageProvider,
        private ComposerJsonProvider $composerJsonProvider,
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
        $packageNames = array_map(
            function (CustomPackage $package): string {
                return $package->getName();
            },
            $packages
        );

        // Include also the Root Composer!
        $packageNames[] = $this->composerJsonProvider->getRootComposerJson()->getName();

        // Extrac the owner, and return unique
        return array_values(array_unique(array_map(
            function (string $packageName): string {
                return (string) Strings::before($packageName, '/');
            },
            $packageNames
        )));
    }
}
