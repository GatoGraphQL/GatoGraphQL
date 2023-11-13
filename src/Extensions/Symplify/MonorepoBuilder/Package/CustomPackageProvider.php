<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Package;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\CustomPackage;
use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
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
            /** @var array<string,mixed> */
            $json = $this->jsonFileManager->loadFromFileInfo($packagesComposerFileInfo);
            $packageName = $this->getPackageName($json);
            $packages[] = new CustomPackage($json, $packageName, $packagesComposerFileInfo);
        }

        usort($packages, function (CustomPackage $firstPackage, CustomPackage $secondPackage): int {
            return $firstPackage->getShortName() <=> $secondPackage->getShortName();
        });

        return $packages;
    }

    /**
     * @param array<string,mixed> $json
     */
    private function getPackageName(array $json): string
    {
        if (! isset($json[ComposerJsonSection::NAME])) {
            throw new ShouldNotHappenException();
        }

        return (string) $json[ComposerJsonSection::NAME];
    }
}
