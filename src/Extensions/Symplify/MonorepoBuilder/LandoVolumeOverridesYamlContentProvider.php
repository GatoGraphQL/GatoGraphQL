<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder;

use Nette\Neon\Neon;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackagesProvider;
use Symplify\PackageBuilder\Neon\NeonPrinter;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class LandoVolumeOverridesYamlContentProvider
{
    /**
     * @var string
     */
    private const SERVICES = 'services';
    /**
     * @var string
     */
    private const APPSERVER = 'appserver';
    /**
     * @var string
     */
    private const OVERRIDES = 'overrides';
    /**
     * @var string
     */
    private const VOLUMES = 'volumes';

    public function __construct(
        private SourcePackagesProvider $sourcePackagesProvider,
        private NeonPrinter $neonPrinter
    ) {
    }

    public function provideContent(array $packagesToSkip = []): string
    {
        $sourcePackages = $this->sourcePackagesProvider->provideSourcePackages(false, $packagesToSkip);

        $phpStanNeon = [
            self::SERVICES => [
                self::APPSERVER => [
                    self::OVERRIDES => [
                        self::VOLUMES => $this->provideVolumeOverrides($sourcePackages),
                    ],
                ],
            ],
        ];

        return $this->neonPrinter->printNeon($phpStanNeon);
    }

    /**
     * @param string[] $sourcePackages
     * @return string[]
     */
    private function provideVolumeOverrides(array $sourcePackages): array
    {
        $landoVolumeOverrides = [];
        foreach ($sourcePackages as $package) {
            $packageComposerConfigFilePath = $package . DIRECTORY_SEPARATOR . 'composer.json';
            if (!file_exists($packageComposerConfigFilePath)) {
                throw new ShouldNotHappenException(sprintf(
                    'Composer.json file "%s" does not exist',
                    $packageComposerConfigFilePath
                ));
            }

            // Read the content, and extract the package name
            $packageComposerConfigFile = new SmartFileInfo($packageComposerConfigFilePath);
            $content = $packageComposerConfigFile->getContents();

            $data = (array) Neon::decode($content);
            $packageName = $data['name'] ?? '';
            
            // @todo Fix! All packages are added under "gatographql", but some belong to "testing-schema" and others
            $packageSmartFileInfo = new SmartFileInfo($package);
            $landoVolumeOverrides[] = sprintf(
                '%s:%s',
                '../../' . $packageSmartFileInfo->getRelativeFilePathFromCwd(),
                '/app/wordpress/wp-content/plugins/gatographql/vendor/' . $packageName
            );
        }
        return $landoVolumeOverrides;
    }
}
