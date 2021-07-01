<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

class GraphQLAPIContainerConfigurationService extends AbstractPluginContainerConfigurationService
{
    protected function getPluginRelativePath(): string
    {
        return 'layers/GraphQLAPIForWP/plugins/graphql-api-for-wp';
    }
    
    public function configureContainer(): void
    {
        parent::configureContainer();

        /**
         * This constant is defined in wp-load.php, but never loaded.
         * It is read when resolving class WP_Upgrader in Plugin.php.
         * Define it here again, or otherwise Rector fails with message:
         *
         * "PHP Warning:  Use of undefined constant ABSPATH -
         * assumed 'ABSPATH' (this will throw an Error in a future version of PHP)
         * in .../graphql-api-for-wp/vendor/wordpress/wordpress/wp-admin/includes/class-wp-upgrader.php
         * on line 13"
         */
        /** Define ABSPATH as this file's directory */
        if (!defined('ABSPATH')) {
            define('ABSPATH', $this->pluginDir . '/vendor/wordpress/wordpress/');
        }
    }

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
                //   "Analyze error: "Class PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor not found."
                '*/ConditionalOnComponent/RESTAPI/*',
    
                // Even when downgrading all packages, skip Symfony's polyfills
                $this->pluginDir . '/vendor/symfony/polyfill-*',
    
                // Skip since they are not needed and they fail
                $this->pluginDir . '/vendor/composer/*',
                $this->pluginDir . '/vendor/lkwdwrd/wp-muplugin-loader/*',
    
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
                $this->pluginDir . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
                $this->pluginDir . '/vendor/symfony/cache/DoctrineProvider.php',
                $this->pluginDir . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
                $this->pluginDir . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
            ]
        );
    }
}
