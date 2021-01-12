<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackagesProvider;
use Symplify\PackageBuilder\Neon\NeonPrinter;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PHPStanNeonContentProvider
{
    private SourcePackagesProvider $sourcePackagesProvider;

    /**
     * @var NeonPrinter
     */
    private $neonPrinter;

    public function __construct(
        SourcePackagesProvider $sourcePackagesProvider,
        NeonPrinter $neonPrinter
    ) {
        $this->sourcePackagesProvider = $sourcePackagesProvider;
        $this->neonPrinter = $neonPrinter;
    }

    public function provideContent(bool $skipUnmigrated): string
    {
        $sourcePackages = $this->sourcePackagesProvider->provideSourcePackages(true, $skipUnmigrated);

        $phpstanConfigIncludes = [];
        foreach ($sourcePackages as $package) {
            $packagePHPStanConfigFilePath = $package . DIRECTORY_SEPARATOR . 'phpstan.neon.dist';
            if (!file_exists($packagePHPStanConfigFilePath)) {
                throw new ShouldNotHappenException(sprintf(
                    'PHPStan config file "%s" does not exist',
                    $packagePHPStanConfigFilePath
                ));
            }

            $phpstanConfigIncludes[] = $packagePHPStanConfigFilePath;
        }
        // Sort entries
        sort($phpstanConfigIncludes);
        // Add entry at the beginning
        array_unshift($phpstanConfigIncludes, 'vendor/szepeviktor/phpstan-wordpress/extension.neon');
        $phpStanNeon = [
            'includes' => $phpstanConfigIncludes,
        ];

        return $this->neonPrinter->printNeon($phpStanNeon);
    }
}
