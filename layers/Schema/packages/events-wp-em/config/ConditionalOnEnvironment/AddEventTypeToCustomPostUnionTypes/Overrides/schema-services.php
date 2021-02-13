<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PoPSchema\Events\ConditionalOnEnvironment\AddEventTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\EventCustomPostTypeResolverPicker as UpstreamEventCustomPostTypeResolverPicker;
use PoPSchema\EventsWPEM\ConditionalOnEnvironment\AddEventTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers\EventCustomPostTypeResolverPicker;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();

    $services->set(
        UpstreamEventCustomPostTypeResolverPicker::class,
        EventCustomPostTypeResolverPicker::class
    );
};
