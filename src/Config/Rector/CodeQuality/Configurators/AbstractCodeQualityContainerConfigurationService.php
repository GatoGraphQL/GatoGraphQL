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
        $services = $this->containerConfigurator->services();
        $services->set(RemoveUselessParamTagRector::class);
        $services->set(RemoveUselessReturnTagRector::class);

        $parameters = $this->containerConfigurator->parameters();
        $parameters->set(Option::AUTO_IMPORT_NAMES, true);
        $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

        // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
        if ($bootstrapFiles = $this->getBootstrapFiles()) {
            $parameters->set(Option::BOOTSTRAP_FILES, $bootstrapFiles);
        }

        // files to process
        if ($paths = $this->getPaths()) {
            $parameters->set(Option::PATHS, $paths);
        }

        // files to skip
        if ($skip = $this->getSkip()) {
            $parameters->set(Option::SKIP, $skip);
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
