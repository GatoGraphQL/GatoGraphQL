<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Upgrades\Configurators;

use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\PHPUnit\Set\PHPUnitSetList;

abstract class AbstractPHPUnit10ContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        $this->rectorConfig->sets([
            PHPUnitSetList::PHPUNIT_100,
        ]);

        // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
        if ($bootstrapFiles = $this->getBootstrapFiles()) {
            $this->rectorConfig->bootstrapFiles($bootstrapFiles);
        }

        // files to process
        if ($paths = $this->getPaths()) {
            $this->rectorConfig->paths($paths);
        }

        // files to skip
        if ($skip = $this->getSkip()) {
            $this->rectorConfig->skip($skip);
        }
    }

    /**
     * @return string[]
     */
    protected function getBootstrapFiles(): array
    {
        return [
            $this->rootDirectory . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        ];
    }

    /**
     * @return string[]
     */
    protected function getPaths(): array
    {
        return [
            $this->rootDirectory . '/src/*',
            $this->rootDirectory . '/layers/*',
        ];
    }

    /**
     * @return string[]
     */
    protected function getSkip(): array
    {
        return [
            '*/vendor/*',
            '*/node_modules/*',
            '*/migrate-*',
        ];
    }
}
