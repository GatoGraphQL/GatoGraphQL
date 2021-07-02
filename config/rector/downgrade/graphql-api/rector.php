<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\GraphQLAPIContainerConfigurationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurationService = new GraphQLAPIContainerConfigurationService(
        $containerConfigurator,
        dirname(__DIR__, 4)
    );
    $containerConfigurationService->configureContainer();
};
