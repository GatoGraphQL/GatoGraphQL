<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\ContainerConfigurationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-shared.php';

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurationService = new ContainerConfigurationService(
        $containerConfigurator,
        dirname(__DIR__, 2)
    );
    $containerConfigurationService->configureContainer();
};
