<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\MonorepoContainerConfigurationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurationService = new MonorepoContainerConfigurationService(
        $containerConfigurator,
        dirname(__DIR__, 2)
    );
    $containerConfigurationService->configureContainer();
};
