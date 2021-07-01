<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules\GraphQLAPICacheItemContainerConfigurationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurationService = new GraphQLAPICacheItemContainerConfigurationService(
        $containerConfigurator,
        dirname(__DIR__, 5)
    );
    $containerConfigurationService->configureContainer();
};
