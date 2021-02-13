<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PoPSchema\Pages\ConditionalOnEnvironment\AddPageTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\PageCustomPostTypeResolverPicker as UpstreamPageCustomPostTypeResolverPicker;
use PoPSchema\PagesWP\ConditionalOnEnvironment\AddPageTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers\PageCustomPostTypeResolverPicker;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();

    $services->set(
        UpstreamPageCustomPostTypeResolverPicker::class,
        PageCustomPostTypeResolverPicker::class
    );
};
