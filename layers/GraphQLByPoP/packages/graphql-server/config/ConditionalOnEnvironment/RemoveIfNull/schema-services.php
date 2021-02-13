<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    $services->load(
        'GraphQLByPoP\\GraphQLServer\\ConditionalOnEnvironment\\RemoveIfNull\\SchemaServices\\',
        '../../../src/ConditionalOnEnvironment/RemoveIfNull/SchemaServices/*'
    );
};
