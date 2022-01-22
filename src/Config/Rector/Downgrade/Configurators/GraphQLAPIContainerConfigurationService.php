<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

class GraphQLAPIContainerConfigurationService extends AbstractMainPluginDowngradeContainerConfigurationService
{
    use GraphQLAPIContainerConfigurationServiceTrait;

    /**
     * @return string[]
     */
    protected function getSkip(): array
    {
        return array_merge(
            parent::getSkip(),
            [
                // The GraphQL API plugin does not require the REST package
                // So ignore all code depending on it, or it throws error:
                //   "Could not process
                //   "vendor/pop-schema/pages/src/ConditionalOnComponent/RESTAPI/RouteModuleProcessors/EntryRouteModuleProcessor.php" file, due to:
                //   "Analyze error: "Class PoPAPI\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor not found."
                '*/ConditionalOnComponent/RESTAPI/*',

                // Even when downgrading all packages, skip Symfony's polyfills
                $this->pluginDir . '/vendor/symfony/polyfill-*',

                // Skip since they are not needed and they fail
                $this->pluginDir . '/vendor/composer/*',
                $this->pluginDir . '/vendor/boxuk/wp-muplugin-loader/*',

                // These are skipped in the .sh since it's faster
                // // All the "migrate" folders
                // $this->pluginDir . '/vendor/getpop/migrate-*/*',
                // $this->pluginDir . '/vendor/pop-schema/migrate-*/*',
                // $this->pluginDir . '/vendor/graphql-by-pop/migrate-*/*',
                // // For local dependencies, skip the tests
                // $this->pluginDir . '/vendor/getpop/*/tests/*',
                // $this->pluginDir . '/vendor/pop-schema/*/tests/*',
                // $this->pluginDir . '/vendor/graphql-by-pop/*/tests/*',

                // Ignore errors from classes we don't have in our environment,
                // or that come from referencing a class present in DEV, not PROD
                // $this->pluginDir . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
                $this->pluginDir . '/vendor/symfony/cache/DataCollector/CacheDataCollector.php',
                $this->pluginDir . '/vendor/symfony/cache/DoctrineProvider.php',
                $this->pluginDir . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
                $this->pluginDir . '/vendor/symfony/dotenv/Command/DebugCommand.php',
                $this->pluginDir . '/vendor/symfony/dotenv/Command/DotenvDumpCommand.php',
                $this->pluginDir . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
            ]
        );
    }
}
