<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-hacks-CacheItem-shared.php';

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
return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/vendor/symfony/cache/CacheItem.php',
    ]);
};
