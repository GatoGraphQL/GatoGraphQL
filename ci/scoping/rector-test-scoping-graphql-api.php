<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-test-scoping-shared.php';

/**
 * This Rector configuration imports the fully qualified classnames
 * using `use`, and removing it from the body.
 * Rule `AndAssignsToSeparateLinesRector` is not needed, but we need
 * to run at least 1 rule.
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);

    $monorepoDir = dirname(__DIR__, 2);
    $pluginDir = $monorepoDir . '/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp';

    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::BOOTSTRAP_FILES, [
        $pluginDir . '/vendor/scoper-autoload.php',
        $pluginDir . '/vendor/jrfnl/php-cast-to-type/cast-to-type.php',
        $pluginDir . '/vendor/jrfnl/php-cast-to-type/class.cast-to-type.php',
    ]);

    // files to rector
    $parameters->set(Option::PATHS, [
        $pluginDir . '/vendor',
    ]);

    // files to skip
    $parameters->set(Option::SKIP, [
        '*/tests/*',

        // The GraphQL API plugin does not require the REST package
        // So ignore all code depending on it, or it throws error:
        //   "Could not process
        //   "vendor/pop-schema/pages/src/ConditionalOnComponent/RESTAPI/RouteModuleProcessors/EntryRouteModuleProcessor.php" file, due to:
        //   "Analyze error: "Class PoPAPI\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor not found."
        '*/ConditionalOnComponent/RESTAPI/*',

        // // Exclude migrate libraries
        // $pluginDir . '/vendor/getpop/migrate-*',
        // $pluginDir . '/vendor/pop-schema/migrate-*',
        // Exclude tests from libraries
        $pluginDir . '/vendor/nikic/fast-route/test/*',
        $pluginDir . '/vendor/psr/log/Psr/Log/Test/*',
        $pluginDir . '/vendor/symfony/service-contracts/Test/*',
        $pluginDir . '/vendor/michelf/php-markdown/test/*',
        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        // $pluginDir . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        $pluginDir . '/vendor/symfony/cache/DataCollector/CacheDataCollector.php',
        $pluginDir . '/vendor/symfony/cache/DoctrineProvider.php',
        $pluginDir . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        $pluginDir . '/vendor/symfony/cache/Psr16Cache.php',
        $pluginDir . '/vendor/symfony/dotenv/Command/DebugCommand.php',
        $pluginDir . '/vendor/symfony/dotenv/Command/DotenvDumpCommand.php',
        $pluginDir . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
        $pluginDir . '/vendor/symfony/yaml/Command/LintCommand.php',
    ]);
};
