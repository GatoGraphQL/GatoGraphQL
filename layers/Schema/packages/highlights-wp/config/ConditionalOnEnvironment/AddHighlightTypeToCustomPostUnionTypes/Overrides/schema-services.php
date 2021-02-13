<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PoPSchema\Highlights\ConditionalOnEnvironment\AddHighlightTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\HighlightCustomPostTypeResolverPicker as UpstreamHighlightCustomPostTypeResolverPicker;
use PoPSchema\HighlightsWP\ConditionalOnEnvironment\AddHighlightTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers\HighlightCustomPostTypeResolverPicker;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();

    $services->set(
        UpstreamHighlightCustomPostTypeResolverPicker::class,
        HighlightCustomPostTypeResolverPicker::class
    );
};
