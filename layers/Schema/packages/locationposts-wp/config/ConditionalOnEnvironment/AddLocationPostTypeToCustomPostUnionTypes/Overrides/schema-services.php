<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PoPSchema\LocationPosts\ConditionalOnEnvironment\AddLocationPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\LocationPostCustomPostTypeResolverPicker as UpstreamLocationPostCustomPostTypeResolverPicker;
use PoPSchema\LocationPostsWP\ConditionalOnEnvironment\AddLocationPostTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers\LocationPostCustomPostTypeResolverPicker;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();

    $services->set(
        UpstreamLocationPostCustomPostTypeResolverPicker::class,
        LocationPostCustomPostTypeResolverPicker::class
    );
};
