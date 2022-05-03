<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Configurators;

use Rector\Config\RectorConfig;

abstract class AbstractContainerConfigurationService
{
    public function __construct(
        protected RectorConfig $rectorConfig,
        protected string $rootDirectory,
    ) {
    }

    abstract public function configureContainer(): void;
}
