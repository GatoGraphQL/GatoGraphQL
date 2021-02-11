<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PoPSchema\Posts\ConditionalOnEnvironment\AddPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\PostCustomPostTypeResolverPicker as UpstreamPostCustomPostTypeResolverPicker;
use PoPSchema\PostsWP\ConditionalOnEnvironment\AddPostTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers\PostCustomPostTypeResolverPicker;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();

    $services->set(
        UpstreamPostCustomPostTypeResolverPicker::class,
        PostCustomPostTypeResolverPicker::class
    );
};
