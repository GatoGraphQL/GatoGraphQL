<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use Nette\Utils\Strings;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package\CustomPackageProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\CustomPackage;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;

final class LocalPackageOwnersProvider
{
    public function __construct(
        private CustomPackageProvider $customPackageProvider,
        private ComposerJsonProvider $composerJsonProvider,
    ) {
    }

    /**
     * To find out if it's PSR-4, check if the package has tests.
     * @return string[]
     */
    public function provideLocalPackageOwners(): array
    {
        $packages = $this->customPackageProvider->provide();
        $packageNames = array_map(
            function (CustomPackage $package): string {
                return $package->getName();
            },
            $packages
        );

        // Include also the Root Composer!
        /** @var string */
        $rootPackageName = $this->composerJsonProvider->getRootComposerJson()->getName();
        $packageNames[] = $rootPackageName;

        // Extract the owner, and return unique
        return array_values(array_unique(array_map(
            function (string $packageName): string {
                return (string) Strings::before($packageName, '/');
            },
            $packageNames
        )));
    }
}
