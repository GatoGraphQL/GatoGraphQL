<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\CodeQuality\Configurators\CodeQualityContainerConfigurationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurationService = new CodeQualityContainerConfigurationService(
        $containerConfigurator,
        dirname(__DIR__, 3)
    );
    $containerConfigurationService->configureContainer();
};
