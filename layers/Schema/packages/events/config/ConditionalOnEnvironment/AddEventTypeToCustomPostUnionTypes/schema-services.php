<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire()
            ->autoconfigure();
    $services->load(
        'PoPSchema\\Events\\ConditionalOnEnvironment\\AddEventTypeToCustomPostUnionTypes\\SchemaServices\\',
        '../../../src/ConditionalOnEnvironment/AddEventTypeToCustomPostUnionTypes/SchemaServices/*'
    );
};
