<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    $services->load(
        'GraphQLByPoP\\GraphQLServer\\Conditional\\CacheControl\\Conditional\\AccessControl\\ConditionalOnEnvironment\\PrivateSchema\\SchemaServices\\',
        '../../../../../../../src/Conditional/CacheControl/Conditional/AccessControl/ConditionalOnEnvironment/PrivateSchema/SchemaServices/*'
    );
};
