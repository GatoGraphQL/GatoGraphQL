<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package\CustomPackageProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\PackageUtils;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class SkipMonorepoSplitPackagesProvider
{
    /**
     * @var string[]
     */
    private array $skipMonorepoSplitPackages = [];

    public function __construct(
        private CustomPackageProvider $customPackageProvider,
        ParameterProvider $parameterProvider,
        private PackageUtils $packageUtils
    ) {
        $this->skipMonorepoSplitPackages = $parameterProvider->provideArrayParameter(Option::SKIP_MONOREPO_SPLIT_PACKAGES);
    }

    /**
     * @return string[]
     */
    public function provideSkipMonorepoSplitPackages(): array
    {
        $packageEntries = [];
        $packages = $this->customPackageProvider->provide();
        foreach ($packages as $package) {
            $packageRelativePath = $package->getRelativePath();
            if ($this->skipMonorepoSplitPackages !== [] && !$this->packageUtils->doesPackageContainAnyPath($packageRelativePath, $this->skipMonorepoSplitPackages)) {
                continue;
            }
            $packageEntries[] = $packageRelativePath;
        }

        return $packageEntries;
    }
}
