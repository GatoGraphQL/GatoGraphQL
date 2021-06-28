<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-shared-hacks-ArrowFnMixedType.php';

/**
 * Hack to fix bug.
 *
 * `fn(mixed $foo)` requires 2 steps to be downgraded:
 * 
 *   1. function(mixed $foo)
 *   2. function($foo)
 * 
 * Because of chained rules not taking place, manually execute the 2nd rule
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);

    $monorepoDir = dirname(__DIR__, 2);
    $pluginDir = $monorepoDir . '/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp';

    // get parameters
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        $pluginDir . '/vendor/getpop/component-model/src/Schema/FieldQueryInterpreter.php',
        $pluginDir . '/vendor/getpop/api/src/Schema/FieldQueryConvertor.php',
    ]);
};
