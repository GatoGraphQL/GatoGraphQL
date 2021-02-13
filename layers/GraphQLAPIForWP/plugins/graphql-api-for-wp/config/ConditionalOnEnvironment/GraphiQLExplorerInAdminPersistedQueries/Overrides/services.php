<?php

declare(strict_types=1);

use GraphQLAPI\GraphQLAPI\Blocks\PersistedQueryGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\GraphiQLExplorerInAdminPersistedQueries\Overrides\Services\Blocks\PersistedQueryGraphiQLWithExplorerBlock;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    $services->set(
        PersistedQueryGraphiQLBlock::class,
        PersistedQueryGraphiQLWithExplorerBlock::class
    );
};
