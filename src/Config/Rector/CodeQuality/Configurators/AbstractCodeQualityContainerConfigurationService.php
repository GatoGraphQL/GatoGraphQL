<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\CodeQuality\Configurators;

use PoP\PoP\Config\Rector\Configurators\AbstractContainerConfigurationService;
use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;

abstract class AbstractCodeQualityContainerConfigurationService extends AbstractContainerConfigurationService
{
    public function configureContainer(): void
    {
        $this->rectorConfig->rule(RemoveUselessParamTagRector::class);
        $this->rectorConfig->rule(RemoveUselessReturnTagRector::class);

        $this->rectorConfig->importNames();
        $this->rectorConfig->importShortClasses();

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
            // Skip tests
            '*/tests/*',
            '*/test/*',
            '*/Test/*',
            // Skip everything else
            '*/vendor/*',
            '*/node_modules/*',
            '*/migrate-*',
        ];
    }
}
