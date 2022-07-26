<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting;

class Environment
{
    public final const PHPUNIT_SUPPORTED_PLUGIN_NAMESPACES = 'PHPUNIT_SUPPORTED_PLUGIN_NAMESPACES';

    /**
     * Provide the plugin namespaces (eg: also for extensions) for which
     * testing is enabled. Eg: only the corresponding custom post types
     * can be modified, for extra security.
     *
     * @return string[]
     */
    public static function getSupportedPluginNamespaces(): array
    {
        return getenv('PHPUNIT_SUPPORTED_PLUGIN_NAMESPACES') !== false
            ? array_map(trim(...), explode(',', getenv('PHPUNIT_SUPPORTED_PLUGIN_NAMESPACES')))
            : ['GraphQLAPI\GraphQLAPI'];
    }
}
