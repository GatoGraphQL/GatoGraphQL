<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp74\Rector\ClassMethod\DowngradeSelfTypeDeclarationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Hack to fix bug.
 *
 * The function in CacheItem should be downgraded as:
 *     function tag($tags);
 * But is downgraded as:
 *     function tag($tags): self;
 *
 * @see https://github.com/rectorphp/rector/issues/5962
 */
function doCommonContainerConfiguration(ContainerConfigurator $containerConfigurator): void
{
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $services = $containerConfigurator->services();
    $services->set(DowngradeSelfTypeDeclarationRector::class);

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
};
