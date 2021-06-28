<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Hack to fix bug.
 *
 * `fn(int | string $foo)` requires 2 steps to be downgraded:
 * 
 *   1. function(int | string $foo)
 *   2. function($foo)
 * 
 * Because of chained rules not taking place, manually execute the 2nd rule
 */
function doCommonContainerConfiguration(ContainerConfigurator $containerConfigurator): void
{
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $services = $containerConfigurator->services();
    $services->set(DowngradeUnionTypeDeclarationRector::class);

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
};
