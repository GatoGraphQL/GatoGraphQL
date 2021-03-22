<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\CustomPackage;
use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class CustomPackageProvider
{
    public function __construct(
        private ComposerJsonProvider $composerJsonProvider,
        private JsonFileManager $jsonFileManager
    ) {
    }

    /**
     * @return CustomPackage[]
     */
    public function provideWithTests(): array
    {
        return array_filter($this->provide(), function (CustomPackage $package): bool {
            return $package->hasTests();
        });
    }

    /**
     * @return CustomPackage[]
     */
    public function provide(): array
    {
        $packages = [];
        foreach ($this->composerJsonProvider->getPackagesComposerFileInfos() as $packagesComposerFileInfo) {
            $packageName = $this->detectNameFromFileInfo($packagesComposerFileInfo);
            $packages[] = new CustomPackage($packageName, $packagesComposerFileInfo);
        }

        usort($packages, function (CustomPackage $firstPackage, CustomPackage $secondPackage): int {
            return $firstPackage->getShortName() <=> $secondPackage->getShortName();
        });

        return $packages;
    }

    private function detectNameFromFileInfo(SmartFileInfo $smartFileInfo): string
    {
        $json = $this->jsonFileManager->loadFromFileInfo($smartFileInfo);

        if (! isset($json['name'])) {
            throw new ShouldNotHappenException();
        }

        return (string) $json['name'];
    }
}
