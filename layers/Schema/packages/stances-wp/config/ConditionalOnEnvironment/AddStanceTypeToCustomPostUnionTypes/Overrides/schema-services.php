<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PoPSchema\Stances\ConditionalOnEnvironment\AddStanceTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\StanceCustomPostTypeResolverPicker as UpstreamStanceCustomPostTypeResolverPicker;
use PoPSchema\StancesWP\ConditionalOnEnvironment\AddStanceTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers\StanceCustomPostTypeResolverPicker;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();

    $services->set(
        UpstreamStanceCustomPostTypeResolverPicker::class,
        StanceCustomPostTypeResolverPicker::class
    );
};
