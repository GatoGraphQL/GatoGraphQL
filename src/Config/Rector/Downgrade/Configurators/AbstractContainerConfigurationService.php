<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

abstract class AbstractContainerConfigurationService
{
    public function __construct(
        protected ContainerConfigurator $containerConfigurator,
        protected string $rootDirectory,
    ) {
    }
    
    abstract public function configureContainer(): void;
}
