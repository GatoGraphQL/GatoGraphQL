<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once dirname(__DIR__, 2) . '/rector-test-scoping-shared.php';

/**
 * This Rector configuration imports the fully qualified classnames
 * using `use`, and removing it from the body.
 * Rule `AndAssignsToSeparateLinesRector` is not needed, but we need
 * to run at least 1 rule.
 */
return static function (RectorConfig $rectorConfig): void {
    // Shared configuration
    doCommonContainerConfiguration($rectorConfig);

    $monorepoDir = dirname(__DIR__, 3);
    $pluginDir = $monorepoDir . '/layers/GatoGraphQLForWP/plugins/gatographql';
    $autoloadFile = $pluginDir . '/vendor/scoper-autoload.php';

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $rectorConfig->bootstrapFiles([
        $autoloadFile,
        $pluginDir . '/vendor/jrfnl/php-cast-to-type/cast-to-type.php',
        $pluginDir . '/vendor/jrfnl/php-cast-to-type/class.cast-to-type.php',
    ]);

    // files to rector
    $rectorConfig->paths([
        $pluginDir . '/vendor',
    ]);

    // files to skip
    $rectorConfig->skip([
        '*/tests/*',

        // Starting from Rector v0.18.2, processing this file might throw errors, then skip it
        $autoloadFile,

        // The Gato GraphQL plugin does not require the REST package
        // So ignore all code depending on it, or it throws error:
        //   "Could not process
        //   "vendor/pop-schema/pages/src/ConditionalOnModule/RESTAPI/ComponentRoutingProcessors/EntryComponentRoutingProcessor.php" file, due to:
        //   "Analyze error: "Class PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor not found."
        '*/ConditionalOnModule/RESTAPI/*',

        // This library is used for testing the source; it is added under "require" so it must be excluded
        $pluginDir . '/vendor/fakerphp/faker/*',

        // Exclude tests from libraries
        /**
         * Gato GraphQL plugin: This code is commented out as it is
         * not needed anymore, because package `brain/cortex`
         * is not loaded
         *
         * @see layers/Engine/packages/root-wp/src/Module.php
         */
        // $pluginDir . '/vendor/nikic/fast-route/test/*',
        $pluginDir . '/vendor/psr/log/Psr/Log/Test/*',
        $pluginDir . '/vendor/symfony/dom-crawler/Test/*',
        $pluginDir . '/vendor/symfony/http-foundation/Test/*',
        $pluginDir . '/vendor/symfony/service-contracts/Test/*',
        $pluginDir . '/vendor/michelf/php-markdown/test/*',
        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        // $pluginDir . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        $pluginDir . '/vendor/symfony/cache/DataCollector/CacheDataCollector.php',
        $pluginDir . '/vendor/symfony/cache/DoctrineProvider.php',
        $pluginDir . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        $pluginDir . '/vendor/symfony/cache/Psr16Cache.php',
        $pluginDir . '/vendor/symfony/cache/Traits/Redis6Proxy.php',
        $pluginDir . '/vendor/symfony/cache/Traits/RedisCluster6Proxy.php',
        $pluginDir . '/vendor/symfony/dotenv/Command/DebugCommand.php',
        $pluginDir . '/vendor/symfony/dotenv/Command/DotenvDumpCommand.php',
        $pluginDir . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
        $pluginDir . '/vendor/symfony/yaml/Command/LintCommand.php',
    ]);
};
