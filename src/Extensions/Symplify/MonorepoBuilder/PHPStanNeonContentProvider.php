<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Nette\Neon\Neon;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackagesProvider;
use Symplify\PackageBuilder\Neon\NeonPrinter;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PHPStanNeonContentProvider
{
    /**
     * @var string
     */
    private const INCLUDES_KEY = 'includes';

    private SourcePackagesProvider $sourcePackagesProvider;
    private NeonPrinter $neonPrinter;

    public function __construct(
        SourcePackagesProvider $sourcePackagesProvider,
        NeonPrinter $neonPrinter
    ) {
        $this->sourcePackagesProvider = $sourcePackagesProvider;
        $this->neonPrinter = $neonPrinter;
    }

    /**
     * Merge all common includes together, and all paths to package configs
     */
    public function provideContent(bool $skipUnmigrated): string
    {
        $sourcePackages = $this->sourcePackagesProvider->provideSourcePackages(true, $skipUnmigrated);

        $phpStanNeon = [
            self::INCLUDES_KEY => $this->provideIncludes($sourcePackages),
        ];

        return $this->neonPrinter->printNeon($phpStanNeon);
    }

    /**
     * Provide a list with all included extensions for all packages,
     * extracted from reading each package's phpstan.neon
     *
     * @param string[] $sourcePackages
     * @return string[]
     */
    private function provideIncludes(array $sourcePackages): array
    {
        $phpstanConfigIncludes = [];
        foreach ($sourcePackages as $package) {
            $packagePHPStanConfigFilePath = $package . DIRECTORY_SEPARATOR . 'phpstan.neon';
            if (!file_exists($packagePHPStanConfigFilePath)) {
                throw new ShouldNotHappenException(sprintf(
                    'PHPStan config file "%s" does not exist',
                    $packagePHPStanConfigFilePath
                ));
            }

            // Read the content, and extract all included files
            $packagePHPStanConfigFile = new SmartFileInfo($packagePHPStanConfigFilePath);
            $content = $packagePHPStanConfigFile->getContents();

            $data = (array) Neon::decode($content);
            $includes = $data[self::INCLUDES_KEY] ?? [];

            // If the included file is under vendor/, reference it directly
            // Otherwise, add the path to the package (eg: for phpstan.neon.dist)
            $includes = array_map(
                fn ($path) => str_starts_with($path, 'vendor/') ? $path : $package . DIRECTORY_SEPARATOR . $path,
                $includes
            );

            $phpstanConfigIncludes = array_merge(
                $phpstanConfigIncludes,
                $includes
            );
        }
        // Remove duplicates
        $phpstanConfigIncludes = array_values(array_unique($phpstanConfigIncludes));
        return $phpstanConfigIncludes;
    }
}
