<?php

declare(strict_types=1);

use PoP\PoP\Config\Symplify\MonorepoBuilder\Configurators\ContainerConfigurationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurationService = new ContainerConfigurationService(
        $containerConfigurator,
        __DIR__
    );
    $containerConfigurationService->configureContainer();
};
